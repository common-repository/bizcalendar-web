<?php
    class SetrioBizCal_BizMedicaServiceClient
    {
        private $webServiceURL = "";
		private $webServiceUser = "";
		private $webServicePass = "";
		private $cache = true;
		private $cache_time = 2592000;
		private $cache_on_fail = true;

		function __construct($debug_mode = false)
		{
			$wsAddress = get_option('setrio_bizcal_wsaddr', '');
			$wsUser = get_option('setrio_bizcal_wsuser', '');
			$wsPass = get_option('setrio_bizcal_wspass', '');
			$cache = (bool)get_option('setrio_bizcal_caching', false);
			$cache_on_fail = get_option('setrio_bizcal_caching_on_fail', 1);
			$cache_time = get_option('setrio_bizcal_caching_time', '');
			if('' === $cache_time){
				$cache_time = 2592000;
			}
			
			if($cache_time < 1){
				$cache = 0;
			}

			$this->webServiceURL = $wsAddress;
			$this->webServiceUser = $wsUser;
			$this->webServicePass = $wsPass;
			$this->cache = $cache;
			$this->cache_time = $cache_time;
			$this->cache_on_fail = $cache_on_fail;
		}
        
        function callCachedWebMethod($type, $method, $params = null, $cache_key=null)
        {
			$caching = (int)$this->cache;
			$cachingTime = (int)$this->cache_time;
			if($caching){
				if($this->cache_on_fail){
					$caching = 2;
				}
				if(isset($cache_key)){
					$cacheKeyCaching = get_option('setrio_bizcal_cache_type_' . $cache_key, '');
					if('' !== $cacheKeyCaching){
						$caching = (int)$cacheKeyCaching;
					}
					$cacheKeyTime = get_option('setrio_bizcal_cache_time_' . $cache_key, '');
					if('' !== $cacheKeyTime && (int)$cacheKeyTime >= 0){
						$cachingTime = (int)$cacheKeyTime;
					}
				}
			}
			$cached_response = false;
			
			
			if($caching){
				$key = 'setrio_' . $type . '_' . $method . (!$params ? '' : '.' . md5($params));
				$cached_response = get_transient($key);
				
				
				if (false !== $cached_response) {
					
					$decoded = json_decode($cached_response, true);
					if(!empty($decoded) && is_array($decoded) && array_key_exists('ErrorCode',$decoded) && empty($decoded['ErrorCode']) && array_key_exists('ErrorMessage',$decoded) && empty($decoded['ErrorMessage'])){
						$decoded['_cached'] = true;
						$cached_response = json_encode($decoded);
						if(1 == $caching){
							return $cached_response;
						}
					}
				}
			}
			
			$response =  $this->callWebMethod($type, $method, $params);
			$decoded = json_decode($response, true);
			if(!empty($decoded) && is_array($decoded) && array_key_exists('ErrorCode',$decoded) && empty($decoded['ErrorCode']) && array_key_exists('ErrorMessage',$decoded) && empty($decoded['ErrorMessage'])){
				if($caching){
					set_transient($key, $response, $cachingTime);
				}
			} elseif((2 == $caching) && (false !== $cached_response)){
				return $cached_response;
			}
			return $response;
		}
        function callWebMethod($type, $method, $params = null)
        {
            if (!($this->webServiceURL))
                return json_encode(array("ErrorCode" => 2000, "ErrorMessage" => setrio_bizcal_message('msgErrServiceAddressMissing')));
            if (!($this->webServiceUser))
                return json_encode(array("ErrorCode" => 2001, "ErrorMessage" => setrio_bizcal_message('msgErrServiceUserMissing')));
            if (!($this->webServicePass))
                return json_encode(array("ErrorCode" => 2002, "ErrorMessage" => setrio_bizcal_message('msgErrServicePasswordMissing')));          
            
            $type = strtoupper($type);
            $args = array(
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic '.base64_encode($this->webServiceUser.":".$this->webServicePass),
                    'cache-control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
                    ],
                'body' => $params,
                'sslverify' => false,
				'timeout' => 120.0
                );
            if ($type == "POST")
            {
                $args['headers']['Content-Type'] = 'application/json';
                if ($params)
                    $args['body'] = $params;
            }
                        
            global $wpdb;
			
			$ip = '';
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			
            $wpdb->insert($wpdb->prefix."bizcal_request_log",
                array(
                    'request_type' => $method,
                    'ip' => $ip,
                    'request_send_date' => gmdate("Y-m-d H:i:s"),
                    'message' => '' . $params
                ),
                array('%s', '%s', '%s'));
            $log_id = $wpdb->insert_id;
            $t0 = microtime(true);
			
            if ($type == 'POST')
                $result = wp_remote_post($this->webServiceURL."/$method", $args);
            else
                $result = wp_remote_get($this->webServiceURL."/$method", $args);
            $t1 = microtime(true);
            $resultErrorCode = 1000 + intval(wp_remote_retrieve_response_code($result)); // Adun cu 1000 ca sa nu existe conflicte cu codurile de eroare returnate de serviciu
            $resultErrorMessage = wp_remote_retrieve_response_message($result);
            $tdiff = $t1 - $t0;
            if (is_wp_error($result))
            {
                // In cazul in care apar erori de comunicatie, construiesc un raspuns pe aceeasi structura cu cea returnata de serviciu,
                // pentru a putea fi procesat mai usor
                $result = json_encode(array("ErrorCode" => $resultErrorCode, "ErrorMessage" => $resultErrorMessage, '_response_time' => $tdiff));
            }
            else
            {
                $result = wp_remote_retrieve_body($result);
            }
            $tdiff = $t1 - $t0;
            
            $wpdb->update($wpdb->prefix."bizcal_request_log",
                array(
                    'request_response_date' => gmdate("Y-m-d H:i:s"),
                    'response' => '' . $result
                ),
                array(
                    'id_request_log' => $log_id
                ),
                array('%s', '%s'),
                array('%d'));
				
			$arr = json_decode($result, true);
			if(!$arr || !is_array($arr)){
				 return json_encode(array("ErrorCode" => 2004, "ErrorMessage" => $type . ' ' . $method . ' Failed with no interpretable result 1 ' . $result , '_response_time' => $tdiff));
			}
			if(!array_key_exists('ErrorCode', $arr) || !array_key_exists('ErrorMessage', $arr)){
				return json_encode(array("ErrorCode" => 2005, "ErrorMessage" => $type . ' ' . $method . ' Failed with no interpretable result 2 ' . $result , '_response_time' => $tdiff));
			}
			if(empty($arr['ErrorCode']) && !empty($arr['ErrorMessage'])){
				return json_encode(array("ErrorCode" => 2006, "ErrorMessage" => $type . ' ' . $method . ' Failed with no interpretable result 3 ' . $result , '_response_time' => $tdiff));
			}
			$result = substr($result, 0, -1) . ',"_response_time": ' . $tdiff . '}';
            return $result;
        }
        
        function getMedicalSpecialitiesDate()
        {
            return $this->callCachedWebMethod("GET", "GetMedicalSpecialitiesDate");
        }

        function getMedicalSpecialities()
        {
            return $this->callCachedWebMethod("GET", "GetMedicalSpecialities",null,'speciality');
        }
        
        function getMedicalServicesDate()
        {
            return $this->callCachedWebMethod("GET", "GetMedicalServicesDate");
        }

        function getMedicalServices($params)
        {
            return $this->callCachedWebMethod("POST", "GetMedicalServices", $params,'service');
        }
        
        function getPhysiciansDate()
        {
            return $this->callCachedWebMethod("GET", "GetPhysiciansDate");
        }

        function getPhysicians($params)
        {
            return $this->callCachedWebMethod("POST", "GetPhysicians", $params,'physician');
        }
        
        function getMedicalServicesPriceListDate()
        {
            return $this->callCachedWebMethod("GET", "GetMedicalServicesPriceListDate");
        }

        function getMedicalServicesPriceList($params)
        {
            return $this->callCachedWebMethod("POST", "GetMedicalServicesPriceList", $params, 'service');
        }
        
        function getPaymentTypes()
        {
            return $this->callCachedWebMethod("GET", "GetPaymentTypes",null,'payment_type');
        }
        
        function getAllowedPaymentTypes($params)
        {
            return $this->callCachedWebMethod("POST", "GetAllowedPaymentTypes", $params,'payment_type');
        }
        
        function getAppointmentAvailabilities($params)
        {
            return $this->callCachedWebMethod("POST", "GetAppointmentAvailabilities", $params,'availability');
        }
		
        function getDateAvailabilities($params)
        {
            return $this->callCachedWebMethod("POST", "GetDateAvailabilities", $params,'availability');
        }
        
        function registerAppointment($params)
        {
            return $this->callWebMethod("POST", "RegisterAppointment", $params);
        }
        
        function getLocationsForWeb()
        {
            return $this->callCachedWebMethod("GET", "GetLocationsForWeb",null,'location');
        }
        
        function getLocationsForSpeciality($params)
        {
            return $this->callCachedWebMethod("POST", "GetLocationsForSpeciality", $params,'location');
        }
}
?>