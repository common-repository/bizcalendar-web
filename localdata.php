<?php

    defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

    class SetrioBizCal_LocalDataClient
    {
        private function is_valid_json($text)
        {
            if (strlen($text) == 0)
                return false;
            if ($text[0] != '{')
                return false;
            $result = json_decode($json);
            if ($result === FALSE)
                return false;
            else
                return true;
        }
        
        private function convert_service_date_to_mysql_date($date)
        {
            return substr($date, 0, 4).'-'.substr($date, 4, 2).'-'.substr($date, 6, 2).' '.str_replace('-', ':', substr($date, 9));
        }

        private function convert_service_date_minus_one_second_to_mysql_date($date)
        {
            $year = (int)substr($date, 0, 4);
            $month = (int)substr($date, 4, 2);
            $day = (int)substr($date, 6, 2);
            $hour = (int)substr($date, 9, 2);
            $minute = (int)substr($date, 12, 2);
            $second = (int)substr($date, 15, 2);
            
            $second--;
            if ($second < 0)
            {
                $second = 59;
                $minute--;
                if ($minute < 0)
                {
                    $minute = 59;
                    $hour--;
                    if ($hour < 0)
                    {
                        $hour = 23;
                        $day--;
                        if ($day < 0)
                        {
                            $month--;
                            if ($month < 0)
                            {
                                $month = 12;
                                $year--;
                            }
                            switch ($month)
                            {
                                case 1:
                                case 3:
                                case 5:
                                case 7:
                                case 8:
                                case 10:
                                case 12:
                                    $day = 31;
                                    break;
                                case 2:
                                    $day = ((($year % 4 == 0) && ($year % 100 != 0)) || ($year % 400 == 0)) ? 29 : 28;
                                    break;
                                default:
                                    $day = 30;
                            }
                        }
                    }
                }
            }
                        
            return str_pad($year, 4, '0', STR_PAD_LEFT).'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-'.str_pad($day, 2, '0', STR_PAD_LEFT).' '
                .str_pad($hour, 2, '0', STR_PAD_LEFT).':'.str_pad($minute, 2, '0', STR_PAD_LEFT).':'.str_pad($second, 2, '0', STR_PAD_LEFT);
        }
        
        function getMedicalSpecialitiesDate()
        {
            global $wpdb;
            $localDate = $wpdb->get_var("SELECT DATE_FORMAT(IFNULL(MAX(start_date), '1900-01-01'), '%Y%m%dT%H-%i-%s') FROM ".$wpdb->prefix."bizcal_medical_specialities");
            return $localDate;
        }
        
        function getMedicalSpecialities()
        {
            global $wpdb;
            
            $dbShowErrorsEnabled = $wpdb->show_errors(false);
            
            $result = array(
                "ErrorCode" => 0,
                "ErrorMessage" => '',
                "CachedData" => true,
                "Specialities" => array()
            );
            
            $localData = $wpdb->get_results("SELECT
                                                 speciality_code,
                                                 speciality_name
                                             FROM ".$wpdb->prefix."bizcal_medical_specialities
                                             WHERE end_date IS NULL
                                             ORDER BY speciality_name", OBJECT);
            if (!$wpdb->last_error)
            {
                foreach ($localData as $item)
                {
                    $result["Specialities"][] = array(
                        "Code" => $item->speciality_code,
                        "Name" => $item->speciality_name
                    );
                }
            }
            else
            {
                $result["ErrorCode"] = -3000;
                $result["ErrorMessage"] = $wpdb->last_error;
            }
            
            $wpdb->show_errors($dbShowErrorsEnabled);
            
            return json_encode($result);
        }
        
        function updateMedicalSpecialities($localDate, $remoteDate, $json_data)
        {
            global $wpdb;

            if ($this->is_valid_json($json_data))
            {
                $data = json_decode($json_data);
                if (($data->ErrorCode == 0) && ($data->ErrorMessage == ""))
                {
                    foreach ($data->Specialities as $speciality)
                    {
                        $wpdb->insert($wpdb->prefix."bizcal_medical_specialities",
                            array(
                                'speciality_code' => $speciality->Code,
                                'speciality_name' => $speciality->Name,
                                'start_date' => $this->convert_service_date_to_mysql_date($remoteDate),
                                'end_date' => null
                            ),
                            array('%s', '%s', '%s', '%s'));
                    }
                    $wpdb->update($wpdb->prefix."bizcal_medical_specialities",
                        array(
                            'end_date' => $this->convert_service_date_minus_one_second_to_mysql_date($remoteDate)
                        ),
                        array(
                            'start_date' => $this->convert_service_date_to_mysql_date($localDate)
                        ),
                        array('%s'),
                        array('%s'));
                }
            }
        }
        
        function getMedicalServicesDate($speciality_code)
        {
            global $wpdb;
            $localDate = $wpdb->get_var("
                SELECT
                    DATE_FORMAT(IFNULL(MAX(start_date), '1900-01-01'), '%Y%m%dT%H-%i-%s')
                FROM ".$wpdb->prefix."bizcal_medical_specialities s
                    JOIN ".$wpdb->prefix."bizcal_medical_services r ON r.
                ");
            return $localDate;
        }

        function getMedicalServices($speciality_code)
        {
            return $this->callWebMethod("POST", "GetMedicalServices", $params);
        }
        
        function updateMedicalServices($speciality_code, $localDate, $remoteDate, $json_data)
        {
            
        }
    }
?>
