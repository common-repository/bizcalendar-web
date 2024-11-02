function SetrioBizCalendarVueModal(elem, data){
	var index = elem;
	if(typeof elem === undefined){
		index = this.parentNode.getAttribute('data-setrio-vue-index');
		if(undefined === index || null === index){
			index = this.parentNode.parentNode.getAttribute('data-setrio-vue-index');
		}
	} else if(isNaN(elem)){
		index = elem.parentNode.getAttribute('data-setrio-vue-index');
		if(undefined === index || null === index){
			index = elem.parentNode.parentNode.getAttribute('data-setrio-vue-index');
		}
	}
	if(undefined !== SetrioBizCalendarVueApps[parseInt(index)]){
		if(data && undefined !== data){
			SetrioBizCalendarVueApps[parseInt(index)].reload(data);
		}
		SetrioBizCalendarVueApps[parseInt(index)].dialog=true;
	}
}
function SetrioBizcalException(message, code) {
  const error = new Error(message);
  error.code = code;
  return error;
}
SetrioBizcalException.prototype = Object.create(Error.prototype);
function SetrioBizcalAbortException(message) {
  const error = new Error(message);
  return error;
}
SetrioBizcalAbortException.prototype = Object.create(Error.prototype);
var setrioBizcalVue = {
	'ajax_cancel': {},
	'ajax_loader_expiry': {},
	'ajax_loader': {},
	'filter_selection': function(key, data, context,_vue){
		// console.log(key, data);
		if('date_availability' === key){
			var dates = [];
			if(data.Dates && data.Dates.length){
				var sd = '' + data.Payload.StartDate;
				var start_date = new Date(sd.slice(0,4), parseInt(sd.slice(4,6))-1, sd.slice(-2));
				var days = (data.Payload && data.Payload.Days) && data.Payload.Days -1 || 0;
				if(!days && data.Payload.StartDate){
					var ed = '' + data.Dates[data.Dates.length -1];
					var end_date = new Date(ed.slice(0,4), parseInt(ed.slice(4,6))-1, ed.slice(-2));
					
					var Difference_In_Time = end_date.getTime() - start_date.getTime();
					days = Difference_In_Time / (1000 * 3600 * 24);
				}
				var d = new Date(start_date);
				for(i = 0; i<=days; i++){
					d.setDate(d.getDate() + 1);
					var dt = d.toISOString().replace(/T.*/,'');
					var dt2 = dt.replace(/-/g,'');
					dates.push((-1 == data.Dates.indexOf(dt2) ? '!' : '') + dt);
				}
			}
			context.commit(key + '_list', dates);
		} else {
			if(data.length){
				if('location' === key){
					data = data.filter((item) => ([true,'true'].indexOf(item['IsActive']) > -1) && ([true,'true'].indexOf(item['AllowWebAppointments']) > -1));
				}
				if('physician' === key){
					data = data.filter((item) => (context.state['payment_type_value'] == 2 && [true,'true'].indexOf(item['AllowCNAS']) > -1) || (context.state['payment_type_value'] == 1 && [true,'true'].indexOf(item['AllowPrivate']) > -1));
				}
				if('availability' === key){
					if(context.state['physician_value']){
						data = data.filter((item) => (context.state['physician_value'] == item['PhysicianUID']));
					}
				}
			}
			var value = context.state[key + '_value'];
			
			var selected_item;
			var new_value = null;
			if(data.length){
				var compare_value = value;
				var selected_start_time;
				var selected_end_time;
				var selected_location;
				if('availability' == key){
					if(value){
						var val_arr = value.split(',');
						compare_value = val_arr[0];
						selected_start_time = val_arr[1];
						selected_end_time = val_arr[2];
						selected_location = val_arr[3];
					}
				}
				if (null !== compare_value){
					let current_value_hash = ('' + compare_value).toUpperCase().trim();
					selected_item = data.filter(item => (item['value'] === current_value_hash) || (('' + item['value']).toUpperCase().trim() === current_value_hash)).shift();
				} else {
					var default_value = '' + (context.state.default[key] || '');
					if(default_value){
						let current_value_hash = ('' + default_value).toUpperCase().trim();
						let default_key = context.state.default[key + '_key'] && ['label', 'value'].indexOf(context.state.default[key + '_key'] > -1) ? '' + context.state.default[key + '_key'] : 'label';
						selected_item = data.filter(item => (item[default_key] === current_value_hash) || (('' + item[default_key]).toUpperCase().trim() === current_value_hash)).shift();
					}
				}
				
				if(!selected_item){
					if(context.state.default['autosel_' + key] && '' !== value){
						selected_item = data[0];
					}
				}
				
				if('physician' === key){
					data.unshift({
						value: '',
						label: context.state.default.txt_any_physician,
						Price: ''
					});
					if(!selected_item){
						new_value = '';
						selected_item = data[0];
					}
				}
				
				if(('location' === key) && context.state.default.enable_multiple_locations){
					data.unshift({
						value: '',
						label: context.state.default.txt_any_location,
					});
					if(!selected_item){
						new_value = '';
						selected_item = data[0];
					}
				}
				
				if('availability' === key){
					if(context.state.default.enable_multiple_locations){
						var loc_uids = (context.state['location_list']||[]).map(x => x.id).filter(x => x!='');
					}
					
					let recommendedDateAvailabilities = false;
					var selected_availability = false;
					data.forEach(item => {
						item['availabilities'] = item.RequestedDateAvailabilities || [];
						if(item.RecommandedDateAvailabilities && item.RecommandedDateAvailabilities.length){
							recommendedDateAvailabilities = true;
							item.RecommandedDateAvailabilities.forEach(availability => {
								availability['recommended'] = true;
							});
							item['availabilities'] = item['availabilities'].concat(item.RecommandedDateAvailabilities);
						}
						
						item['availabilities'].forEach(availability => {
							availability['start_datetime'] = availability.StartDate.slice(0,4) + '-' + availability.StartDate.slice(4,6) + '-' + availability.StartDate.slice(6,8) + ' ' +availability.StartDate.slice(9,11) + ':' + availability.StartDate.slice(12,14);
							[availability['start_date'], availability['start_time']] = availability['start_datetime'].split(' ');
							
							availability['end_datetime'] = availability.EndDate.slice(0,4) + '-' + availability.EndDate.slice(4,6) + '-' + availability.EndDate.slice(6,8) + ' ' +availability.EndDate.slice(9,11) + ':' + availability.EndDate.slice(12,14);
							[availability['end_date'], availability['end_time']] = availability['end_datetime'].split(' ');

							if(availability.LocationUID){
								availability['value'] = [item.PhysicianUID, availability.StartDate, availability.EndDate, availability.LocationUID].join(',');
							} else {
								availability['value'] = [item.PhysicianUID, availability.StartDate, availability.EndDate].join(',');
							}
							
							if(!selected_availability && selected_item && item.PhysicianUID == selected_item.value && (availability.StartDate == selected_start_time)){
								if(availability.LocationUID && selected_location == availability.LocationUID){
									selected_availability = [availability.StartDate, availability.EndDate, availability.LocationUID].join(',');
								} else {
									selected_availability = [availability.StartDate, availability.EndDate].join(',');
								}
							}
						});
						
						item['availabilities'] = item['availabilities'].sort((a, b) => ('' + a.start_date).localeCompare(b.start_date));
						item['day_availabilities'] = item['availabilities'].filter((a,i)=>a.start_date == item['availabilities'][0].start_date);
						if(context.state.default.enable_multiple_locations){
							item['day_locations'] = item['day_availabilities'].map(x => x['LocationUID']);
							item['day_locations'] = loc_uids.filter(x => item['day_locations'].includes(x));
						}
					});
					
					data = data.sort((a, b) => ('' + a.availabilities[0].start_date).localeCompare(b.availabilities[0].start_date));
					
					if(!selected_item){
						selected_item = data[0];
					}
					if(!selected_availability){
						if(selected_item['availabilities'][0].LocationUID){
							selected_availability = [selected_item['availabilities'][0].StartDate, selected_item['availabilities'][0].EndDate, selected_item['availabilities'][0].LocationUID].join(',');
						} else {
							selected_availability = [selected_item['availabilities'][0].StartDate, selected_item['availabilities'][0].EndDate].join(',');
						}
					}
					if(selected_item){
						new_value = selected_item['value'] + ',' + selected_availability;
						selected_item = null;
					}
				}
				
				if(selected_item){
					new_value = selected_item.value;
				}
				
				data.forEach(item => {item['id']=item.value});
				
			}
			
			if(data.length && new_value){
				data.filter(item => item.value === new_value).forEach(item => {item.selected=true;item.highlight=true;item.highlighted=true;});
			}
			
			context.commit(key + '_value', null);
			context.commit(key + '_list', JSON.parse(JSON.stringify(data)));
			context.commit(key + '_value', new_value);
			if(null !== value && new_value !== value){
				if('availability' !== key){
					context.commit('force_reload', key);
				}
			}
		}
		
		return new Promise(resolve => resolve({}));
	},
	'abort_ajax': function(){
		if(setrioBizcalVue.ajax_loader){
			setrioBizcalVue.ajax_loader.abort();
		}
	},
	'call_ajax': function(context, params){
		
		// console.warn(params, return_empty);
		var axios_data = params['params']||{};
		axios_data['action'] = axios_data['action'] || params.action;
		if(context.state['force_reload']){
			throw new SetrioBizcalAbortException('forced reload. aborting get');
		}
		axios_data['_ajax_nonce'] = axios_data['_ajax_nonce'] || context.state.default.nonce;
		
		const CancelToken = axios.CancelToken;
		// var loader_key = context.getters.el_context + '.' + (params['loader_key'] || axios_data['action']);
		var loader_key = '' + (params['loader_key'] || axios_data['action']);
		
		var cacheable = undefined === params['cacheable'] || params['cacheable'];
		var expiry = cacheable ? (undefined === params['expiry'] ? 60000 : params['expiry']) : 0;
		var cancellable = (undefined === params['cancellable'] ? cacheable : params['cancellable']);
		
		var return_empty = false;
		if(params['require']){
			for(require_key in params['require']) if (!return_empty && params['require'].hasOwnProperty(require_key)){
				var require_item = params['require'][require_key];
				var require_value =  axios_data[require_key] || context.state[require_key] || context.getters[require_key] || '';
				loader_key += '.' + require_value;
				
				if(Array.isArray(require_item) && require_item.indexOf(require_value) < 0){
					return_empty = true;
				} else if(require_item && !require_value){
					return_empty = true;
				} else {
					switch(require_key){
						case 'speciality_value':
							axios_data['speciality_code'] = require_value;
							break;
						case 'location_value':
							axios_data['location_uid'] = require_value;
							break;
						case 'physician_value':
							axios_data['physician_uid'] = require_value;
							break;
						case 'service_value':
							axios_data['service_uid'] = require_value;
							break;
						case 'payment_type_value':
							axios_data['payment_type'] = require_value;
							axios_data['payment_type_id'] = require_value;
							break;
						case 'date_value':
							axios_data['desired_date'] = require_value.replace(/-/g,'');
							break;
					}
				}
				if(return_empty){
					break;
				}
			}
		}
		if(return_empty){
			setrioBizcalVue.ajax_loader[loader_key] = new Promise(resolve => resolve([]));
		}
		
		var curtime = new Date().getTime();
		if(cacheable && undefined !== setrioBizcalVue.ajax_loader[loader_key] ){
			if(undefined === setrioBizcalVue.ajax_loader_expiry[loader_key] || setrioBizcalVue.ajax_loader_expiry[loader_key] > curtime){
				return setrioBizcalVue.ajax_loader[loader_key];
			}
		}
		if(expiry && expiry > 0){
			setrioBizcalVue.ajax_loader_expiry[loader_key] = curtime + expiry;
		} else if (undefined !== setrioBizcalVue.ajax_loader_expiry[loader_key]){
			delete setrioBizcalVue.ajax_loader_expiry[loader_key];
		}
		
		var axios_params = new setrioURLSearchParams(axios_data);

		var config = {
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			},
			validateStatus: function (status) {return status >= 200 && status < 400},
		};
		if(cancellable){
			config['cancelToken'] = new CancelToken(function executor(c) {
			  setrioBizcalVue.ajax_cancel[context.getters.el_context] = c;
			});
		} else {
			config['cancelToken'] = false;
		}
		var url = context.state.default.call_url;
		if('register_appointment' == axios_data['action']){
			var url = context.state.default.ajax_url;
		}
		var req = axios.post(url, axios_params.URLSearchParams, config)
			.then((response) => {
				var data = response.data;
				if(typeof response.data == 'string'){
					data = JSON.parse(response.data);
					if(!data){
						throw new SetrioBizcalException("Invalid response from server", 0);
					}
					if (!((data.ErrorCode == 0) && (!data.ErrorMessage))){
						throw new SetrioBizcalException(data.ErrorMessage, data.ErrorCode);
					}
				}
				if(undefined !== params['list_key']){
					if(undefined !== params['item_value_key']){
						return data[params.list_key].map(item => {
							item['value'] = item[params.item_value_key];  
							item['label'] = item[params.item_label_key];
							if(params['upper_label']){
								item['label'] = item['label'].toUpperCase();
							}
							return item; 
						});
					}
					return data[params.list_key];
				}
				if(undefined !== params['item_key'])
					return data[params.item_key];
		
				return data;
			});
		if(cacheable){
			req.catch((error) => {
				delete setrioBizcalVue.ajax_loader[loader_key];
				delete setrioBizcalVue.ajax_loader_expiry[loader_key];
			});
			return setrioBizcalVue.ajax_loader[loader_key] = req;
		}
		return req;
	},
	'cancel_ajax': function(el_context){
		if(setrioBizcalVue.ajax_cancel[el_context]){
			setrioBizcalVue.ajax_cancel[el_context]();
		}
	},
	'register_appointment': function(context, vue){
		var availability_arr =  (vue.objVal(context.state, 'availability_value') || '').split(',');
		var loc_uid = availability_arr[3] || context.state['location_value'];
		return setrioBizcalVue['call_ajax'](context,{
			'action': 'register_appointment',
			'cacheable': false,
			'cancellable': false,
			'params': {
				'notify_only': vue['notify_only'] ? 1 : 0,
				'physician_uid': availability_arr[0],
				'payment_type_id': context.state['payment_type_value'],
				'service_uid': context.state['service_value'],
				'location_uid': loc_uid,
				'speciality_code': context.state['speciality_value'],
				
				'first_name': vue['firstname'],
				'last_name': vue['lastname'],
				'email': vue['email'],
				'phone': vue['phone'],
				'observations': vue['observations'],
				
				'terms': vue['terms'] ? 1 : 0,
				'data_policy': vue['data_policy'] ? 1 : 0,
				'newsletter': vue['newsletter'] ? 1 : 0,
				
				'start_date': availability_arr[1],
				'end_date': availability_arr[2],
				'date': vue['date_value'],
				
				'recaptcha': vue['recaptcha'],
				
				'location_name': ((context.state['location_list'] || []).find(x=>x.UID == loc_uid) || {}).label || (vue.objVal(vue.$refs, 'location.selectedItems.0') || {}).label || '',
				'service_name': vue.objVal(vue.$refs, 'service.selectedItems.0.label') || '',
				'online_pay': vue['online_pay_value'],
				'speciality_name': vue.objVal(vue.$refs, 'speciality.selectedItems.0.label') || '',
				'payment_type_name': vue.objVal(vue.$refs, 'payment_type.selectedItems.0.label') || '',
				'physician_name': vue.objVal(context.state, 'Physician.PhysicianName') || vue.objVal(vue.$refs, 'physician.selectedItems.0.label') ||'',
				'price': vue.objVal(context.state, 'Physician.Price') || '',
				'error_code': vue.objVal(context.state, 'show_dialog_submit_fail.error.code'),
				'error_message': vue.objVal(context.state, 'show_dialog_submit_fail.error.message'),
			}
		});
	},
	'load_speciality_list': function(context){
		return setrioBizcalVue['call_ajax'](context,{
			'action': 'get_medical_specialities',
			'list_key': 'Specialities',
			'item_value_key': 'Code',
			'item_label_key': 'Name',
		});
	},
	'load_location_list': function(context){
		return setrioBizcalVue['call_ajax'](context,{
			'action': 'get_locations',
			'list_key': 'Locations',
			'item_value_key': 'UID',
			'item_label_key': 'LocationName',
			'require': {
				'speciality_value': 1,
			}
		});
	},
	'load_payment_type_list': function(context){
		return setrioBizcalVue['call_ajax'](context,{
			'action': 'get_allowed_payment_types',
			'list_key': 'PaymentTypeList',
			'item_value_key': 'ID',
			'item_label_key': 'Description',
			'require': {
				'speciality_value': 1,
			}
		});
	},
	'load_service_list': function(context, physician){
		return setrioBizcalVue['call_ajax'](context,{
			'action': context.state['physician_value'] ? 'get_prices' : 'get_medical_services',
			'list_key': 'MedicalServices',
			'item_value_key': 'UID',
			'item_label_key': 'Name',
			// 'upper_label': true,
			'require': {
				'speciality_value': 1,
				'location_value': false,
				'payment_type_value': [1],
				'physician_value': false,
			},
			params: {
				'physician_value': physician ? context.state.physician_value || context.state.PhysicianUID || '' : '',
			}
		});
	},
	// 'load_prices': function(context){
		// return setrioBizcalVue['call_ajax'](context,{
			// 'action': 'get_prices',
			// 'list_key': 'MedicalServices',
			// 'item_value_key': 'UID',
			// 'item_label_key': 'Name',
			// 'upper_label': true,
			// 'require': {
				// 'service_value': 1,
				// 'location_value': context.state.default.enable_multiple_locations ? 1 : false,
				// 'physician_value': 1,
			// }
		// });
	// },
	'load_physician_list': function(context){
		return setrioBizcalVue['call_ajax'](context,{
			'action': 'get_physicians',
			'list_key': 'Physicians',
			'item_value_key': 'UID',
			'item_label_key': 'Name',
			'require': {
				'speciality_value': 1,
				'payment_type_value': 1,
				'service_value': false,
				'location_value': false,
			}
		});
	},
	'load_price': function(context){
		var params = {
			'action': 'get_price_for_service',
			// 'item_key': 'Price',
			'require': {
				'service_value': 1,
				'physician_value': 1,
			},
			'params': {
				'physician_value': context.state.physician_value || context.state.PhysicianUID || '',
			}
		};
		// console.warn('asd', params);
		return setrioBizcalVue['call_ajax'](context,params);
	},
	'load_dates': function(context, dates){
		return setrioBizcalVue['call_ajax'](context,{
			'action': 'setrio_date_rel_abs',
			'loader_key': JSON.stringify(dates),
			'params': {
				dates: dates,
			},
		});
	},
	'load_availability_list': function(context){
		return setrioBizcalVue['call_ajax'](context,{
			'action': 'get_availability',
			'list_key': 'Availabilities',
			'item_value_key': 'PhysicianUID',
			'item_label_key': 'PhysicianName',
			'require': {
				'can_get_availability' : [true],
				'speciality_value': 1,
				'service_value': false,
				'location_value': false,
				'payment_type_value': 1,
				'physician_value': false,
				'date_value': 1,
			}
		});
	},
	'load_date_availability_list': function(context){
		// console.error('wazz');
		return setrioBizcalVue['call_ajax'](context,{
			'action': 'get_date_availabilities',
			'require': {
				'can_get_availability' : [true],
				'speciality_value': 1,
				'service_value': false,
				'location_value': false,
				'payment_type_value': 1,
				'physician_value': false,
				'date_value': 1,
			}
		});
	},
};

class setrioURLSearchParams {
	constructor(data){
		this.URLSearchParams = new URLSearchParams();
		if(data){
			for (const property in data) {
			  this.append(property, data[property]);
			}
		}
	}
	append(k, v){
		if(undefined === v || null === v){
			return;
		}
		this.URLSearchParams.append(k,v);
	}
	toString(){
		return this.URLSearchParams.toString();
	}
}