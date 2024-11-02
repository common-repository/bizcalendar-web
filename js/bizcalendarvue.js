var SetrioBizCalendarVueApps = [];
JSON.safeStringify = (obj, indent = 2) => {
  let cache = [];
  const retVal = JSON.stringify(
    obj,
    (key, value) =>
      typeof value === "object" && value !== null
        ? cache.includes(value)
          ? undefined // Duplicate reference found, discard key
          : cache.push(value) && value // Store value in our collection
        : value,
    indent
  );
  cache = null;
  return retVal;
};
jQuery(document).ready(function($){
	$('body').append('<div id="setrio-bizcal-vue-popup-wrapper" class="setrio setrio-bizcal setrio-bizcal-related bizcal-vue bizcal-vue-popup"><div id="setrio-bizcal-vue-popup-container" class="bizcal-vue-app"><div id="setrio-bizcal-vue-popup" class="v-application bizcal-vue-v-app bizcal-force v-application--is-ltr theme--light"></div></div></div>');

    var bizcalComponents = document.querySelectorAll('v-app.bizcal-vue-v-app');
	
	let loaded_config = false;
	if (bizcalComponents.length > 0){
	  if(undefined !== window['VueRecaptcha']){
		Vue.component('v-recaptcha', window.VueRecaptcha);
	  }
	  /* if(undefined !== window['VueTour']){
		Vue.component('v-tour', window.VueTour);
	  } */
	  // Vue.config.silent = true;
	  
      Vue.use(Vuex);
	  
	  Vue.directive('texts', {
		  bind: function (el, binding, vnode, a, b) {
			Object.assign(vnode.context['texts'],{
				[vnode.data.ref] : Object.assign(vnode.context['texts'][vnode.data.ref] || {},{
					[binding.arg] : vnode.data.attrs['data-text-' + binding.arg],
				}),
			});
		  }
		});
		var $vuetify = new Vuetify({
				theme: {
					options: { 
						// customProperties: true,
						/* minifyTheme: function(css) {
							var objVal = (obj, dot_path) => dot_path.split('.').reduce((o,i)=>undefined === o || null === o || undefined === o[i] ? undefined : o[i], obj);
							
							console.warn('MINIFY', $vuetify);
							var custom_css = '';
							var css_prefix = "\n.setrio.setrio-bizcal.setrio-bizcal-related.bizcal-vue .bizcal-vue-app";
							
							if(objVal(this.style,'default')){
								custom_css += css_prefix + " .v-application .v-btn {";
								if(val = objVal(this.style,'default.outline.size')){
									custom_css += "\n border-width:" + val + " !important;"
								}
								if(val = objVal(this.style,'default.outline.style')){
									custom_css += "\n border-style:" + val + " !important;"
								}
								custom_css += "}";
							}
							if(objVal(this.style,'button')){
								custom_css += css_prefix + " .v-application .v-btn {";
								if(val = objVal(this.style,'button.outline.size')){
									custom_css += "\n border-width:" + val + " !important;"
								}
								if(val = objVal(this.style,'button.outline.style')){
									custom_css += "\n border-style:" + val + " !important;"
								}
								custom_css += "\n}";
							}
							return css + custom_css;
						}, */
					},
					
					themes: {},
				}
			});
	  Vue.directive('initial', {
		  bind: function (el, binding, vnode, a, b) {
			vnode.context.mergeDeep(vnode.context.$data, binding.value);
			vnode.context.loadConfig(vnode.context.config_file);
		  }
		});
	}
    for (var bizcal_component_index = 0; bizcal_component_index < bizcalComponents.length; bizcal_component_index++){
		let bizcal_component = bizcalComponents[bizcal_component_index];
		if(!bizcal_component.id){
			bizcal_component.id = 'bizcal-vue-' + (bizcal_component_index+1);
		}
		if(!bizcal_component.hasAttribute('data-context')){
			bizcal_component.setAttribute('data-context', 'context-' + bizcal_component_index);
		}
		var parent_element = bizcal_component.parentNode;
		let vue_app;
		SetrioBizCalendarVueApps.push(vue_app);
		let vue_app_index = SetrioBizCalendarVueApps.length -1;
		parent_element.setAttribute('data-setrio-vue-index', SetrioBizCalendarVueApps.length-1);
		parent_element.parentNode.setAttribute('data-setrio-vue-index', SetrioBizCalendarVueApps.length-1);
		
		new Vue({
            el: bizcal_component,
			vuetify: $vuetify,
            store: function(){
				let vue_item = this;
				return new Vuex.Store({
                strict: true,
                state: {
                    'zero': 0,
                    'show_dialog_submit_fail_button': null,
                    'loading_ajax_key': false,
                    'filtering': false,
                    'loading_ajax': false,
                    'speciality_lock': true,
                    'location_lock': true,
                    'payment_type_lock': true,
                    'service_lock': true,
                    'physician_lock': true,
                    'date_lock': true,
                    'availability_lock': true,
                    'ajax': null,
                    'force_reload': false,
                    'speciality_value': null,
                    'speciality_list': [],
                    'default': {},
                    'location_value': null,
                    'location_list': [],
					
                    'payment_type_value': null,
                    'payment_type_list': [],
					
                    'service_value': null,
                    'service_list': [],
					
                    'expanded_panel': 0,
                    'PhysicianUID': null,
                    'Physician': null,
                    'physician_value': null,
                    'physician_list': [],
					
                    'price_value': null,
                    'online_pay_value': null,
                    'date_value': null,
					
					'start_date_value': null,
					'end_date_value': null,
					'availability': null,
					'availability_value': null,
                    'availability_list': [],
                    'date_availability_list': [],
                    'show_dialog_no_intervals_available': null,
                    'show_dialog_recommended_intervals': null,
                    'show_dialog_submit_fail': {
						open: false,
						error: {
							code: 0,
							message: "",
						},
					},
                    'show_dialog_notify': false,
                    'show_dialog_submit_errors': false,
                    'show_dialog_submit_success': {
						open: false,
						data: {},
					},
                    'show_dialog_submit_notified': {
						open: false,
						data: {},
					},
                },
                getters: {
					can_get_availability: state => state.date_value && 
								   state.speciality_value && 
								   state.payment_type_value && 
								   (state.payment_type_value == 2 ? 1 : null !== state.service_value) && 
								   (!state.default['allow_search_physician'] || null !== state.physician_value),
					el_context: state => bizcal_component.getAttribute('data-context'),
				},
                mutations: {
                    'ajax': function(state, value) {
                        state['loading_ajax'] = !!(state['ajax'] = value);
						
						state['speciality_lock'] = state['loading_ajax'];
						state['location_lock'] = state['loading_ajax'];
						state['service_lock'] = state['loading_ajax'];
						state['payment_type_lock'] = state['loading_ajax'];
						state['physician_lock'] = state['loading_ajax'];
						state['date_lock'] = state['loading_ajax'];
						state['availability_lock'] = state['loading_ajax'];
                    },
                    'ajax2': function(state, value) {
                        state['loading_ajax'] = !!(state['ajax'] = value);
						
						state['speciality_lock'] = state['loading_ajax'];
						state['location_lock'] = state['loading_ajax'];
						state['service_lock'] = state['loading_ajax'];
						state['payment_type_lock'] = state['loading_ajax'];
						state['physician_lock'] = state['loading_ajax'];
						state['date_lock'] = state['loading_ajax'];
						// state['availability_lock'] = state['loading_ajax'];
                    },
                    'show_dialog_submit_fail_button': function(state, value) {
                        state['show_dialog_submit_fail_button'] = !!value;
                    },
                    'default': function(state, value) {
                        state['default'] = value;
                    },
                    'force_reload': function(state, value) {
                        state['force_reload'] = value;
						setrioBizcalVue.cancel_ajax(this.getters.el_context);
                    },
                    'speciality_value': function(state, value) {
						state['speciality_lock'] = false;
						if(state['speciality_value'] === value) return;
                        state['speciality_value'] = value;
                        state['location_lock'] = true;
                        state['payment_type_lock'] = true;
                        state['service_lock'] = true;
                        state['physician_lock'] = true;
                        state['availability_lock'] = true;
                    },
                    'speciality_list': function(state, value) {
                        state['speciality_list'] = value || [];
						state['PhysicianUID'] = null;
						state['Physician'] = null;
                    },
                    'location_value': function(state, value) {
						state['location_lock'] = false;
						if(state['location_value'] === value) return;
						state['location_value'] = value;
						state['service_lock'] = true;
						state['availability_lock'] = true;
                    },
                    'location_list': function(state, value) {
						state['location_list'] = value || [];
						state['PhysicianUID'] = null;
						state['Physician'] = null;
                    },
                    'payment_type_value': function(state, value) {
						state['payment_type_lock'] = false;
						if(state['payment_type_value'] === value) return;
						state['payment_type_value'] = value;
						state['availability_lock'] = true;
						state['physician_lock'] = true;
                    },
                    'payment_type_list': function(state, value) {
						state['payment_type_list'] = value || [];
						state['PhysicianUID'] = null;
						state['Physician'] = null;
                    },
                    'service_value': function(state, value) {
						state['service_lock'] = false;
						if(state['service_value'] === value) return;
						state['service_value'] = value;
						
						state['availability_lock'] = true;
                    },
                    'service_list': function(state, value) {
                        state['service_list'] = value || [];
						state['PhysicianUID'] = null;
						state['Physician'] = null;
                    },
                    'physician_value': function(state, value) {
						state['physician_lock'] = false;
						if(state['physician_value'] === value) return;
                        state['physician_value'] = value;
						
						state['availability_lock'] = true;
                    },
                    'physician_list': function(state, value) {
                        state['physician_list'] = value || [];
						state['PhysicianUID'] = null;
						state['Physician'] = null;
                    },
					
                    'price_value': function(state, value) {
                        state['price_value'] = value;
                    },
                    'online_pay_value': function(state, value) {
                        state['online_pay_value'] = value;
                    },
                    'filtering': function(state, value) {
                        state['filtering'] = value;
                    },
                    'expanded_panel': function(state, value) {
                        state['expanded_panel'] = value;
                    },
                    'loading_ajax_key': function(state, value) {
                        state['loading_ajax_key'] = value;
                    },
                    'date_value': function(state, value) {
						state['date_lock'] = false;
						if(state['date_value'] === value) return;
                        state['date_value'] = value;
						state['availability_lock'] = true;
                    },
					
                    'availability_value': function(state, value) {
						state['availability_value'] = value;
						state['availability_lock'] = false;
						if(!value){
							state['PhysicianUID'] = value;
							state['start_date_value'] = value;
							state['end_date_value'] = value;
							state['Physician'] = value;
							state['availability'] = value;
						} else {
							var val_arr = value.split(',');
							state['PhysicianUID'] = val_arr[0];
							state['start_date_value'] = val_arr[1];
							state['end_date_value'] = val_arr[2];
							state['Physician'] = null;
							state['availability'] = null;
							
							if(state['availability_list']){
								(state.availability_list || []).forEach((item, index) => {
									if(item.id == state['PhysicianUID'] ){
										state['expanded_panel'] = index;
										state['Physician'] = item;
										(item.availabilities || []).forEach((av, av_index) => {
											if(state['start_date_value'] == av.StartDate && state['end_date_value'] == av.EndDate){
												state['availability'] = av;
												return false;
											}
											if(state['availability']){
												return false;
											}
										});
										return false;
									}
									if(state['Physician']){
										return false;
									}
								});
							}
						}
						
						
                    },
                    'availability_list': function(state, value) {
						state['expanded_panel'] = 0;
                        state['availability_list'] = value || [];
						
						if(state['availability_list'] && state['availability_list'].length){
							
						}
                    },
					
                    'date_availability_list': function(state, value) {
                        state['date_availability_list'] = value || [];
                    },
					
                    'show_dialog_no_intervals_available': function(state, value) {
                        state['show_dialog_no_intervals_available'] = value;
                    },
                    'show_dialog_recommended_intervals': function(state, value) {
                        state['show_dialog_recommended_intervals'] = value;
                    },
                    'show_dialog_submit_errors': function(state, value) {
                        state['show_dialog_submit_errors'] = value;
                    },
                    'show_dialog_submit_success': function(state, value) {
						state['show_dialog_submit_success'].open = !!value;
						if(value){
							state['show_dialog_submit_success'].data = value;
						}
                    },
                    'show_dialog_submit_notified': function(state, value) {
						state['show_dialog_submit_notified'].open = !!value;
						if(value && true !== value){
							state['show_dialog_submit_notified'].data = value;
						}
                    },
                    'show_dialog_notify': function(state, value) {
						state['show_dialog_notify'] = !!value;
                    },
                    'show_dialog_submit_fail': function(state, value) {
						state['show_dialog_submit_fail'].open = !!value;
						state['show_dialog_submit_fail_button'] = !!value;
						if(value && true !== value){
							if(undefined !== value['open']){
								state['show_dialog_submit_fail'].open = !!value['open'];
							}
							state['show_dialog_submit_fail'].error.code = value.code;
							state['show_dialog_submit_fail'].error.message = value.message;
						}
                    },
                },
                actions: {
                    'action_lists' : function(context) {
						if(!context.state.default.ajax_url) return;
						if(!context.state.default.call_url) return;
						if(!this._vue_app.can_action_list()){
							return;
						}
						if(!context.state['force_reload']){
							this._vue_app.resetValidation();
						}
						if(!context.state['loading_ajax']){
							context.dispatch('action_set_online_pay_value', false);
							context.dispatch('show_dialog_submit_fail', false);
							// context.commit('show_dialog_submit_fail_button', false);
							var load_physician_first = ('1' == '' + context.state.default.appointment_param_order) && !context.state['physician_value'];
							context.commit('ajax', setrioBizcalVue['load_speciality_list'](context)
								.then((data) => setrioBizcalVue['filter_selection']('speciality', data, context, this))
								.catch((error) => {
									context.commit('speciality_list', []);
								})
								.then(setrioBizcalVue['load_location_list'].bind(null,context))
								.then((data) => setrioBizcalVue['filter_selection']('location', data, context, this))
								.catch((error) => {
									context.commit('location_list', []);
								})
								.then(setrioBizcalVue['load_payment_type_list'].bind(null,context))
								.then((data) => setrioBizcalVue['filter_selection']('payment_type', data, context, this))
								.catch((error) => {
									context.commit('payment_type_list', []);
								})
								.then(setrioBizcalVue['load_' + (load_physician_first ? 'physician' : 'service') + '_list'].bind(null,context))
								.then((data) => setrioBizcalVue['filter_selection']((load_physician_first ? 'physician' : 'service'), data, context, this))
								.catch((error) => {
									context.commit((load_physician_first ? 'physician' : 'service') + '_list', []);
								})
								.then(setrioBizcalVue['load_' + (!load_physician_first ? 'physician' : 'service') + '_list'].bind(null,context))
								.then((data) => setrioBizcalVue['filter_selection']((!load_physician_first ? 'physician' : 'service'), data, context, this))
								.catch((error) => {
									context.commit((!load_physician_first ? 'physician' : 'service') + '_list', []);
								})
								.then(setrioBizcalVue['load_price'].bind(null,context)).then(function(data){
									context.commit('price_value', data.Price);
								})
								.then(setrioBizcalVue['load_date_availability_list'].bind(null,context))
								.then((data) => {/* console.warn('ldal', data); */ return setrioBizcalVue['filter_selection']('date_availability', data, context, this)})
								.catch((error) => {
									// console.error('xzcvzxcvzxcv', error);
									context.commit('date_availability_list', []);
								})
								.then(setrioBizcalVue['load_availability_list'].bind(null,context))
								.then((data) => {/* console.error('lal', data); */ return setrioBizcalVue['filter_selection']('availability', data, context, this)})
								.catch((error) => {
									context.commit('availability_list', []);
								})
								.catch((error) => {
									if (('forced reload. aborting get' == error.message) || axios.isCancel(error)) {
										// console.log("post Request canceled", error);
									} else {
										context.dispatch('show_dialog_submit_fail', {
											open: false,
											code: error.code,
											message: error.message,
										}); 

										console.warn("other axios error", error);
									}
								})
								.finally(() => {
									// console.error('asdf');
									context.commit('ajax', null);
									
									if(context.state.force_reload){
										context.commit('force_reload', false);
										return this.dispatch('action_lists');
									} 
									if(!context.state.show_dialog_submit_fail_button){
										if(!context.state.speciality_list.length){
											context.dispatch('show_dialog_submit_fail', {
												open: false,
												code: -1,
												message: context.state.default.texts.txt_no_specialities,
											});
										} else if (context.state.speciality_value){
											if(!context.state.payment_type_list.length){
												context.dispatch('show_dialog_submit_fail', {
													open: false,
													code: -2,
													message: context.state.default.texts.txt_no_payment_types,
												});
											} else if(context.state.default.enable_multiple_locations && !context.state.location_list.length){
												context.dispatch('show_dialog_submit_fail', {
													open: false,
													code: -3,
													message: context.state.default.texts.txt_no_locations,
												});
											} else if( context.state.payment_type_value == 1 && !context.state.service_list.length && (!context.state.default.enable_multiple_locations || context.state.location_value)){
												context.dispatch('show_dialog_submit_fail', {
													open: false,
													code: -4,
													message: context.state.default.texts.txt_no_services,
												});
											} else if( context.state.payment_type_value && !context.state.physician_list.length){
												context.dispatch('show_dialog_submit_fail', {
													open: false,
													code: -5,
													message: context.state.default.texts.txt_no_physicians,
												});
											} else if( context.getters.can_get_availability && !context.state.availability_list.length){
												context.dispatch('show_dialog_submit_fail', {
													open: false,
													code: 10,
													message: context.state.default.texts.txt_no_availabilities,
												});
											}
										}
									}
									
									// context.dispatch('action_set_online_pay_value', ((context.state.service_list || []).find(v => v.id == (context.state.service_value || '')) || {}).OnlinePay || false); 
									
									return new Promise(resolve => resolve([]));
								}));
						} else {
							// console.error('FORCE RELOAD');
							context.commit('force_reload', true);
						}
						return context.state['ajax'];
                    },
                    'default' : function(context, value) {
                        context.commit('default', JSON.parse(JSON.stringify(value)));
                    },
                    'action_set_speciality_value' : function(context, value) {
						if(context.state['speciality_value'] === value){
							return;
						}
                        context.commit('speciality_value', value);
						this.dispatch('action_lists');
                    },
                    'action_set_location_value' : function(context, value) {
						if(context.state['location_value'] === value){
							return;
						}
                        context.commit('location_value', value);
						this.dispatch('action_lists');
                    },
                    'action_set_payment_type_value' : function(context, value) {
						if(context.state['payment_type_value'] === value){
							return;
						}
                        context.commit('payment_type_value', value);
						this.dispatch('action_lists');
                    },
                    'action_set_service_value' : function(context, value) {
						if(context.state['service_value'] === value){
							return;
						}
						context.commit('service_value', value);
						this.dispatch('action_lists');
                    },
                    'action_set_physician_value' : function(context, value) {
						if(context.state['physician_value'] === value){
							return;
						}
                        context.commit('physician_value', value);
						this.dispatch('action_lists');
                    },
                    'action_set_price_value' : function(context, value) {
						if(context.state['price_value'] === value){
							return;
						}
                        context.commit('price_value', value);
                    },
                    'action_set_online_pay_value' : function(context, value) {
						if(context.state['online_pay_value'] === value){
							return;
						}
                        context.commit('online_pay_value', value);
                    },
                    'action_set_date_value' : function(context, value) {
						if(context.state['date_value'] === value){
							return;
						}
                        context.commit('date_value', value);
						this.dispatch('action_lists');
                    },
                    'action_load_physician' : function(context) {
						context.commit('online_pay_value', false);
						// console.warn('should load_physician');
						// if(context.state.physician_value) return;
						if(!context.state.PhysicianUID) return;
						if(!context.state.service_value) return;
						context.commit('ajax2', setrioBizcalVue['load_service_list'](context, true).then(function(data){
							var service = data.filter(v => v.UID == context.state.service_value)[0];
							// console.warn('loading_service_for_physician', service, service.OnlinePay ? true : false);
							context.commit('online_pay_value', service.OnlinePay ? true : false);
							// console.warn('load_service_list', data.filter(v => v.UID == context.state.PhysicianUID)[0]);
						}).finally(() => {
							context.commit('ajax2', null);
						}));
						
                    },
                    'action_set_availability_value' : function(context, value) {
						if(context.state['availability_value'] === value){
							return;
						}
						
						context.commit('availability_value', value);
						
                    },
                    'expanded_panel' : function(context, value) {
                        context.commit('expanded_panel', value);
                    },
                    'show_dialog_submit_fail_button' : function(context, value) {
                        context.commit('show_dialog_submit_fail_button', value);
                    },
                    'show_dialog_no_intervals_available' : function(context, value) {
                        context.commit('show_dialog_no_intervals_available', value);
                    },
                    'show_dialog_recommended_intervals' : function(context, value) {
                        context.commit('show_dialog_recommended_intervals', value);
                    },
                    'show_dialog_submit_errors' : function(context, value) {
                        context.commit('show_dialog_submit_errors', value);
                    },
                    'show_dialog_submit_success' : function(context, value) {
                        context.commit('show_dialog_submit_success', value);
                    },
                    'show_dialog_submit_notified' : function(context, value) {
                        context.commit('show_dialog_submit_notified', value);
                    },
                    'show_dialog_submit_fail' : function(context, value) {
                        context.commit('show_dialog_submit_fail', value);
                    },
                    'show_dialog_notify' : function(context, value) {
                        context.commit('show_dialog_notify', value);
                    },
                }
            })
			},
            data: function() {
                return {
					app_index: vue_app_index,
					cached_props: {},
					ready: false,
					type: 'inline',
					bindings: {},
					dialog: false,
					step: 1,
					valid: true,
					config_file: null,
					notify_only: false,
					submitting: false,
					autosel: {},
					texts: {},
					lastname: null,
					firstname: null,
					email: null,
					phone: null,
					recaptcha: null,
					observations: null,
					terms: true,
					/* step_1_tour_steps: [
					  {
						target: '.sbc-speciality',  // We're using document.querySelector() under the hood
						params: {
						  placement: 'right',
						},
						header: {
						  title: 'Specialitatea',
						},
						content: `Alegeti din lista derulanta specialitatea la care doriti programare.`
					  },
					  {
						target: '.sbc-location',  // We're using document.querySelector() under the hood
						params: {
						  placement: 'right',
						},
						header: {
						  title: 'Locatia',
						},
						content: `Alegeti din lista derulanta locatia la care doriti programare.`
					  },
					], */
					data_policy: true,
					newsletter: true,
					recaptcha_error: 'validate',
					theme: new Proxy({
						isDark : this.$vuetify.theme.isDark,
						themes : this.$vuetify.theme.themes,
					}, {
						get: function(target, name, a,b) {
							if('toJSON' !== name && 'null' !== name && typeof name === 'string' && 0 !== name.indexOf('_') && undefined === target[name]){
								Vue.set(target, name, {});
							}
							ret = target[name];
							return ret;
						}
					}),
					demo: {
						rounded: [
							null,
							'0',
							'sm',
							'md',
							'lg',
							'xl',
							'pill',
							'circle',
						],
					},
                }
            },
            methods: {
				/* startTour() {
					setTimeout(() => this.$refs.step_1_tour.start(), 500);
				}, */
				objVal:(obj, dot_path) => dot_path.split('.').reduce((o,i)=>undefined === o || null === o || undefined === o[i] ? undefined : o[i], obj),
				localeDateTime:(datetime, params) => new Date(datetime).toLocaleDateString('ro-RO', params || {
					timeZone: 'UTC',
				}),
				recaptchaVerified(response) {
					this.recaptcha = response;
					this.recaptcha_error = false;
				},
				setData(type,data,ref) {
					if(undefined !== type){
						// console.log('setData');
						if (undefined !== this.bindings[type]){
							return;
						}
						this.bindings[type] = 1;
					}
					var by_ref = undefined !== ref;
					if(!by_ref){
						ref = this.$data;
					}
					if(undefined == data){
						delete(ref);
						return;
					}
					if(null === data){
						ref = null;
					} else if('object' !== typeof(data)){
						ref = data;
					} else {
						for(i in data) {
							if(data.hasOwnProperty(i)) {
								if(undefined === ref[i]){
									ref[i] = data[i];
								} else {
									if(undefined === data[i]){
										delete(ref[i]);
									} else {
										ref[i] = this.setData(undefined, data[i], ref[i]);
									}
								}
							}
						}
					}
					if(by_ref){
						return ref;
					}
					Object.assign(this.$data, ref);
				},
				can_action_list() {
					return (this.type == 'popup' && this.dialog) || (this.type != 'popup');
				},
				recaptchaExpired(response) {
					this.recaptcha_error = 'expired';
				},
				recaptchaError(response) {
					this.recaptcha_error = 'error';
				},
				recaptchaRender(response) {
				},
				async notifyAdmin() {
					this.notify_only = true;
					return this.submit();
				},
				canStep (where) {
					if(this.loading_ajax) return false;
					switch(where){
						case -1:
							if(this.step === 1) return false;
						break;
						default:
							if(this.step === 2 && !this.availability_value) return false;
							if(this.step === 3 && !this.validateStep3()) return false;
							if(this.step === 4 || this.loading_ajax) return false;
							if(this.step === 1 && !this.show_dialog_submit_fail_button && (!this.availability_list || !this.availability_list.length)) return false;
							
						break;
					}
					return true;
				},
				validateStep3 () {
					return (this.$refs.firstname && this.$refs.firstname.valid)
					&& (this.$refs.lastname && this.$refs.lastname.valid)
					&& (this.$refs.phone && this.$refs.phone.valid)
					&& (this.$refs.email && this.$refs.email.valid)
					&& (this.$refs.observations && this.$refs.observations.valid)
				},
				validate () {
					this.$refs.form.validate()
				},
				async submit () {
					this.submitting = true;
					await this.validate();
					if(!this.valid){
						this.$store.dispatch('show_dialog_submit_errors', true); 
						this.submitting = false;
						this.show_dialog_notify = false;
						this.notify_only = false;
						return;
					}
					
					setrioBizcalVue['register_appointment'](this.$store, this).then((data) => {
						console.warn('register_appointment', data);
						// if (!((data.ErrorCode == 0) && (data.ErrorMessage == ""))){
							// throw 'Server error code (' + data.ErrorCode + '): ' + data.ErrorMessage;
						// }
						if(typeof data.form !== 'undefined'){
							$('#bizcal-payment-form-wrapper').remove();
							$('body').append('<div id="bizcal-payment-form-wrapper" style="display:none !important;"/>');
							$('#bizcal-payment-form-wrapper').append(data.form);
							$('#bizcal-payment-form-wrapper form').submit();
						} else if(typeof data.redirect !== 'undefined'){
							if(data.redirect.match(/^\?/)){
								window.location.search = window.location.search + '&' + data.redirect.substring(1) + '&sba_hash=' + encodeURIComponent(data.sba_hash);
							} else if(data.redirect.match(/^[&#]/)){
								window.location.search = window.location.search + data.redirect + '&sba_hash=' + encodeURIComponent(data.sba_hash);
							} else {
								window.location.href = data.redirect.replace(/^(.*?)(#.*?)?$/, function(m0,m1,m2){
									return m1 + ((m1.indexOf('?') > -1) ? '&' : '?') + 'sba_hash=' + encodeURIComponent(data.sba_hash) + (m2||'');
								});
							}
							return;
							
						}
						// console.log('submit success', this.notify_only, data);
						if(this.notify_only){
							this.$store.dispatch('show_dialog_submit_notified', data); 
						} else {
							this.$store.dispatch('show_dialog_submit_success', data); 
						}
						this.show_dialog_submit_fail_button=false;
						this.reset();
						this.dialog = false; 
						this.show_dialog_notify = false;
						this.show_dialog_submit_fail.code = 0;
						this.show_dialog_submit_fail.message = "";
					}).catch((error,a,b,c) => {
						this.show_dialog_submit_fail_button = true;
						this.$store.dispatch('show_dialog_submit_fail', {
							code: error.code,
							message: error.message,
						}); 
					}).finally(() => {
						if(this.$refs['recaptcha']){
							this.$refs['recaptcha'].reset();
						}
						this.submitting = false;
						this.notify_only = false;
					});
				},
				reset () {
					[
						'firstname',
						'lastname',
						'observations',
						'email',
						'phone',
						'terms',
						'data_policy',
						'newsletter',
						'recaptcha',
					].forEach((v,k) => {
						if(!!this.$refs[v]){
							this.$refs[v].reset();
						}
					})
					setrioBizcalVue.ajax_loader = {};
					this.$store.dispatch('action_lists');
				},
				reload (data) {
					var default_data = JSON.parse(JSON.stringify(this.$store.state.default));
					if(!data){
						data = this.$data;
						default_data = data;
					} else {
						this.mergeDeep(default_data, data, true);
						this.mergeData(data, true);
					}
					setrioBizcalVue.cancel_ajax(bizcal_component.getAttribute('data-context'));
					this.$store.dispatch('default', default_data);
					this.loadDates([this.$data.date_base, this.$data.min_date_base]).then((a,b) => {
						this.$data['date'] = a[0];
						this.$data['min_date'] = a[1];
					}).finally((a) => {
						this.date_value = this.$data['date_value'] || this.$data['date'] || null;
						this.$store.dispatch('action_lists');
					});
				},
				resetValidation () {
					if(!this.ready) return;
					this.$refs.form.resetValidation()
				},
				isObject(obj) {
				  return (obj !== null) && ('object' === typeof obj);
				},
				vuetifyGenerateCss: function() {
					this.$forceUpdate();
					this.$vuetify.theme.themes.__ob__.dep.notify()
				},
				mergeData(data, skip_null) {
					this.mergeDeep(this, data, skip_null);
				},
				mergeDeep(source, target, skip_null) {
				  if (source === void 0) {
					source = {};
				  }

				  if (target === void 0) {
					target = {};
				  }

				  for (var key in target) {
					var sourceProperty = source[key];
					var targetProperty = target[key]; // Only continue deep merging if
					// both properties are objects
					
					if(null === targetProperty && skip_null){
						continue;
					}

					if (this.isObject(sourceProperty) && this.isObject(targetProperty)) {
					  source[key] = this.mergeDeep(sourceProperty, targetProperty);
					  continue;
					}
					source[key] = targetProperty;
				  }

				  return source;
				},
				async loadConfig(url) {
					let obj = null;
					if(!loaded_config){
						loaded_config = true;
						if(url){
							try {
								obj = await (await fetch(url)).json();
								this.mergeDeep(this.$data, obj);
							} catch(e) {
								console.log(e);
							}
						}
						// console.log('loadConfig');
					}
					setTimeout(() => this.ready = true, 400);
					// this.ready = true;
					
					this.vuetifyGenerateCss();
					return obj;
				},
				async loadDates(dates) {
					var url = this.call_url;
					let obj = null;
					return setrioBizcalVue.load_dates(this.$store,dates);
				},
				
				get_props(type) {
			if(!type){
				type='default';
			}
			var val;
			var classes = [];
			var props = {};
			var checks = ['default'];
			if(-1 !== ['text-field','textarea','autocomplete'].indexOf(type)){
				checks.push('input');
			}
			if(0 === type.indexOf('alert-')){
				checks.push('alert');
			}
			if(0 === type.indexOf('button-')){
				checks.push('button');
			}
			// if(type === 'expansion-panel'){
				// checks.push('card');
			// }
			// if(type === 'expansion-panel-header'){
				// checks.push('card-title');
			// }
			// if(type === 'expansion-panel-content'){
				// checks.push('card-text');
			// }
			if(type !== 'default'){
				checks.push(type);
			}
			var defs = {};
			checks.forEach(ctype => {
				var cust_props = {...this.objVal(this.theme.themes.style,ctype + '.props') || {}};
				for(prop in cust_props){
					if(!cust_props.hasOwnProperty(prop)) continue;
					if(!cust_props[prop]) continue;
					if(-1 !== ['class'].indexOf(prop)){
						props[prop] = (props[prop] || []).concat(cust_props[prop]);
						props[prop] = props[prop].filter((item, pos) => props[prop].indexOf(item) === pos);
						continue;
					}
					if(('object' === typeof cust_props[prop])){
						if(!cust_props[prop].length) continue;
						props[prop] = (props[prop] ? props[prop] + ' ' : '') + cust_props[prop].join(' ');
						continue;
					}
					if('number' === typeof cust_props[prop]){
						props[prop] = !!(cust_props[prop]-1);
					} else {
						props[prop] = cust_props[prop];
					}
				}
				var val;
				if(val = this.objVal(this.theme.themes.style,ctype + '.outline.radius')){
					defs['radius'] = val;
				}
				if(val = this.objVal(this.theme.themes.style,ctype + '.outline.elevation')){
					defs['elevation'] = val;
				}
				if(val = this.objVal(this.theme.themes.style,ctype + '.props.font-family')){
					defs['style'] = this.mergeDeep(defs['style'], {'font-family': val});
				}
				// if(val = this.objVal(this.theme.themes.style,ctype + '.outline.style')){
					// defs['style'] = this.mergeDeep(defs['style'], {'border-style': val});
				// }
			});
			
			if(undefined !== defs['style']){
				props['style'] = defs['style'];
			}
			
			if(undefined !== defs['radius']){
				classes.push('rounded' + (('md' !== this.demo.rounded[defs['radius']]) ? '-' + this.demo.rounded[defs['radius']] : ''));
			}
			if(undefined !== defs['elevation']){
				props['elevation'] = defs['elevation'] - 1;
				classes.push('elevation-' + (defs['elevation'] - 1));
			}
			if(classes.length){
				props['class'] = (props['class'] || []).concat(classes);
			}
			// if(props['expand-icon']){
				// props['expand-icon'] = props['expand-icon'].join(' ');
			// }
			// if(props['icon']){
				// props['icon'] = props['icon'].join(' ');
			// }
			// console.log(props);
			return props;
		},
				props(type) {
					if(undefined === this.cached_props[type]){
						this.cached_props[type] = this.get_props(type);
					}
					return this.cached_props[type];
				},
            },
            computed: {
                Location: {
                    get() { 
						if(this.$store.state['availability'] && this.$store.state['availability']['LocationUID']){
							loc_uid = this.$store.state['availability']['LocationUID'];
						} else {
							loc_uid = this.$store.state['location_value'];
						}
						return (this.$store.state['location_list'] || []).find(x=>x.UID == loc_uid);
					},
                },
                console: {
                    get() { return console },
                },
                datepicker_events: {
                    get() {
						var event_list = {};
						var changed_date = false;
						if(!this.loading_ajax && this.can_get_availability){
							this.days_list.available.forEach(d => {
								if(this.date_value == d) return;
								
								event_list[d] = {color:'green'};
								if(this.availability_list && this.availability_list.length && (!this.availability_list[0].RequestedDateAvailabilities || !this.availability_list[0].RequestedDateAvailabilities.length) && this.availability_list[0].RecommandedDateAvailabilities && this.availability_list[0].RecommandedDateAvailabilities.length){
									if(d < this.availability_list[0].RecommandedDateAvailabilities[0].start_date){
										event_list[d] = {color:'red'};
									}
								}
							});
							this.days_list.unavailable.forEach(d => {
								if(this.date_value == d) return;
								event_list[d] = {color:'red'};
							});
							
							if(this.availability_list && this.availability_list.length && this.availability_list[0].RequestedDateAvailabilities && this.availability_list[0].RequestedDateAvailabilities.length){
								this.$refs['date_picker'].monthClick(this.availability_list[0].RequestedDateAvailabilities[0].start_date);
								changed_date = true;
							} else if(this.availability_list && this.availability_list.length && this.availability_list[0].RecommandedDateAvailabilities && this.availability_list[0].RecommandedDateAvailabilities.length){
								this.$refs['date_picker'].monthClick(this.availability_list[0].RecommandedDateAvailabilities[0].start_date);
								changed_date = true;
							} else if(this.$refs['date_picker'] && this.days_list.available[0]){
								// this.$refs['date_picker'].monthClick(this.days_list.available[0]);
								// changed_date = true;
							}
							if(!changed_date && this.date_value){
								if(this.$refs['date_picker']){
									this.$refs['date_picker'].monthClick(this.date_value);
								}
							}
						}
						
						return event_list;
					},
                },
                days_list: {
					get() {
						days_list = {
							available: [],
							unavailable: [],
						};
						if(this.date_availability_list && this.date_availability_list.length){
							this.date_availability_list.forEach((d,i) => {
								var n = 0 === ('' + d).indexOf('!');
								var sd = !n ? '' + d : ('' + d).slice(1);
								if(!n){
									days_list.available.push(sd);
								} else {
									days_list.unavailable.push(sd);
								}
							})
						}
						days_list.available = days_list.available.sort();
						days_list.unavailable = days_list.unavailable.sort();
						return days_list;
					}
				},
                datepicker_days: {
                    get() {
						var event_list = {};
						event_list[this.date_value] = {color:'primary'};
						if(!this.loading_ajax && this.can_get_availability){
							var cur_date = this.date_value.split('-');
							var cur_year = parseInt(cur_date[0]);
							var cur_month = parseInt(cur_date[1]);
							var cur_day = parseInt(cur_date[2]);
							var date_with_last_day = new Date(cur_year, cur_month-1, 0);
							var last_day_of_month = date_with_last_day.getDate();
							
							event_list[this.date_value] = {color:'error'};
							if(!this.loading_ajax && this.availability_list && this.availability_list.length && this.availability_list[0].RequestedDateAvailabilities && this.availability_list[0].RequestedDateAvailabilities.length){
								event_list[this.date_value] = {color:'success'};
							} else if(!this.loading_ajax && this.availability_list && this.availability_list.length && this.availability_list[0].RecommandedDateAvailabilities && this.availability_list[0].RecommandedDateAvailabilities.length){
								event_list[this.availability_list[0].RecommandedDateAvailabilities[0].start_date] = {color:'success'};
							}
						}
						return event_list;
					},
                },
                datepicker_color: {
                    get() {
						if(!this.loading_ajax && this.can_get_availability){
							if(!this.availability_list || !this.availability_list.length){
								return 'error';
							}
							if('success' == this.objVal(this.datepicker_days, this.date_value + '.color')){
								return 'success';
							}
							return 'warning';
						}
						return undefined;
					},
                },
                speciality_value: {
                    get() { return this.$store.state['speciality_value']; },
                    set(value) {
						this.$store.dispatch('action_set_speciality_value', value); 
					}
                },
                speciality: {
					get(value) { return this._data.speciality },
                    set(value) {
						this.$store.dispatch('action_set_speciality_value', null); 
					}
                },
                location_value: {
                    get() { return this.$store.state['location_value']; },
                    set(value) { 
						this.$store.dispatch('action_set_location_value', value || ''); 
					}
                },
				location: {
					get(value) { return this._data.location },
                    set(value) {
						this.$store.dispatch('action_set_location_value', null); 
					}
                },
                payment_type_value: {
                    get() { return this.$store.state['payment_type_value']; },
                    set(value) { 
						this.$store.dispatch('action_set_payment_type_value', value); 
					}
                },
                payment_type: {
					get(value) { return this._data.payment_type },
                    set(value) {
						this.$store.dispatch('action_set_payment_type_value', null); 
					}
                },
                service_value: {
                    get() { return this.$store.state['service_value']; },
                    set(value) { 
						this.$store.dispatch('action_set_service_value', value); 
					}
                },
				service: {
					get(value) { return this._data.service },
                    set(value) {
						this.$store.dispatch('action_set_service_value', null); 
					}
                },
                expanded_panel: {
                    get() { return this.$store.state['expanded_panel']; },
                    set(value) { this.$store.dispatch('expanded_panel', value); }
                },
                physician_value: {
                    get() { return this.$store.state['physician_value']; },
                    set(value) { this.$store.dispatch('action_set_physician_value', value || ''); }
                },
				physician: {
                    get(value) { return this._data.physician },
                    set(value) {
						this.$store.dispatch('action_set_physician_value', null); 
					}
                },
                price_value: {
                    get() { return this.$store.state['price_value']; },
                    set(value) { this.$store.dispatch('action_set_price_value', value); }
                },
                online_pay_value: {
                    get() { return this.$store.state['online_pay_value']; },
                    set(value) { this.$store.dispatch('action_set_online_pay_value', value); }
                },
                date_value: {
                    get() { return this.$store.state['date_value']; },
                    set(value) { this.$store.dispatch('action_set_date_value', value); }
                },
                show_dialog_submit_fail_button: {
                    get() { return this.$store.state['show_dialog_submit_fail_button']; },
                    set(value) { this.$store.dispatch('show_dialog_submit_fail_button', value); }
                },
                availability_value: {
                    get() { return this.$store.state['availability_value']; },
                    set(value) { 
						this.$store.dispatch('action_set_availability_value', value); 
					}
                },
                show_dialog_no_intervals_available: {
                    get() { 
						return false === this.$store.state['show_dialog_no_intervals_available'] ? false : (this.can_get_availability && !this.$store.state.availability_lock && null !== this.$store.state.availability_list && !this.$store.state.availability_list.length); 
					},
                    set(value) { this.$store.dispatch('show_dialog_no_intervals_available', value); }
                },
                show_dialog_recommended_intervals: {
                    get() { 
						return false === this.$store.state['show_dialog_recommended_intervals'] ? false : (this.$store.state.availability_list && this.$store.state.availability_list.length  && !this.$store.state.availability_list[0]['RequestedDateAvailabilities']);
					},
                    set(value) { this.$store.dispatch('show_dialog_recommended_intervals', value); }
                },
                show_dialog_submit_errors: {
                    get() { 
						return this.$store.state['show_dialog_submit_errors'];
					},
                    set(value) { this.$store.dispatch('show_dialog_submit_errors', value); }
                },
                show_dialog_submit_success: {
                    get() { 
						return this.$store.state['show_dialog_submit_success'];
					},
                    set(value) { this.$store.dispatch('show_dialog_submit_success', value); this.step = 1; }
                },
				show_dialog_submit_notified: {
                    get() { 
						return this.$store.state['show_dialog_submit_notified'];
					},
                    set(value) { this.$store.dispatch('show_dialog_submit_notified', value); this.step = 1; }
                },
                show_dialog_submit_fail: {
                    get() { 
						return this.$store.state['show_dialog_submit_fail'];
					},
                    set(value) { this.$store.dispatch('show_dialog_submit_fail', value); }
                },
                show_dialog_notify: {
                    get() { 
						return this.$store.state['show_dialog_notify'];
					},
                    set(value) { this.$store.dispatch('show_dialog_notify', value); }
                },
                ...Vuex.mapState({
					loading_ajax: state => state.loading_ajax,
					force_reload: state => state.force_reload,
					speciality_list: state => state.speciality_list,
					location_list: state => state.location_list,
					payment_type_list: state => state.payment_type_list,
					service_list: state => state.service_list,
					physician_list: state => state.physician_list,
					availability_list: state => state.availability_list,
					date_availability_list: state => state.date_availability_list,
					zero: state => state.zero,
					speciality_lock: state => state.speciality_lock,
					service_lock: state => state.service_lock,
					payment_type_lock: state => state.payment_type_lock,
					location_lock: state => state.location_lock,
					physician_lock: state => state.physician_lock,
					date_lock: state => state.date_lock,
					Physician: state => state.Physician,
					PhysicianUID: state => state.PhysicianUID,
					availability_lock: state => state.availability_lock,
					start_date_value: state => state.start_date_value,
					end_date_value: state => state.end_date_value,
					availability: state => state.availability,
					filtering: state => state.filtering,
				}),
				...Vuex.mapGetters(['can_get_availability','el_context'])
            },
            beforeMount: function() {
				// console.log('BeforeMount', this.config_file);
            },
            mounted: function() {
				// console.log('mounted', this.config_file);
				this.vuetifyGenerateCss();
				
				this.resetValidation();
				this.reload()
            },
            created: function() {
				// console.log(JSON.safeStringify(this));
				// console.log('created', this.config_file);
            },
            beforeCreate: function(a,b,c) {
				
				// console.log('beforeCreate', this.config_file);
				vue_app = this.$store['_vue_app'] = this;
				SetrioBizCalendarVueApps[vue_app_index] = this;
            },
            watch: {
				'step': {
					immediate: true,
					handler:function(val, oldVal) {
						if(2 == val && !(this.can_get_availability && this.availability_list && this.availability_list.length)){
							if(oldVal < val) this.step++;
							else this.step--;
						}
					},
				},
				'availability_value': {
					immediate: true,
					handler:function(val, oldVal) {
						if(val && (val !== oldVal)){
							this.$store.dispatch('action_load_physician');
						}
					},
				},
				'dialog': {
					immediate: true,
					handler:function(val, oldVal) {
						if(val && (!val !== !oldVal)){
							this.$store.dispatch('action_lists');
						}
						if(!val && (!val !== !oldVal)){
							setrioBizcalVue.cancel_ajax(bizcal_component.getAttribute('data-context'));
						}
					},
				},
				/* 'theme.style' : {
					immediate: true,
					deep: true,
					handler:function(val, oldVal) {
						console.log('watch');
						this.vuetifyGenerateCss();
					},
				}, */
            }
        });
        
    }  
});