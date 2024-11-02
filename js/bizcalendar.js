// Sprintf
!function(){"use strict";var g={not_string:/[^s]/,not_bool:/[^t]/,not_type:/[^T]/,not_primitive:/[^v]/,number:/[diefg]/,numeric_arg:/[bcdiefguxX]/,json:/[j]/,not_json:/[^j]/,text:/^[^\x25]+/,modulo:/^\x25{2}/,placeholder:/^\x25(?:([1-9]\d*)\$|\(([^)]+)\))?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-gijostTuvxX])/,key:/^([a-z_][a-z_\d]*)/i,key_access:/^\.([a-z_][a-z_\d]*)/i,index_access:/^\[(\d+)\]/,sign:/^[+-]/};function y(e){return function(e,t){var r,n,i,s,a,o,p,c,l,u=1,f=e.length,d="";for(n=0;n<f;n++)if("string"==typeof e[n])d+=e[n];else if("object"==typeof e[n]){if((s=e[n]).keys)for(r=t[u],i=0;i<s.keys.length;i++){if(null==r)throw new Error(y('[sprintf] Cannot access property "%s" of undefined value "%s"',s.keys[i],s.keys[i-1]));r=r[s.keys[i]]}else r=s.param_no?t[s.param_no]:t[u++];if(g.not_type.test(s.type)&&g.not_primitive.test(s.type)&&r instanceof Function&&(r=r()),g.numeric_arg.test(s.type)&&"number"!=typeof r&&isNaN(r))throw new TypeError(y("[sprintf] expecting number but found %T",r));switch(g.number.test(s.type)&&(c=0<=r),s.type){case"b":r=parseInt(r,10).toString(2);break;case"c":r=String.fromCharCode(parseInt(r,10));break;case"d":case"i":r=parseInt(r,10);break;case"j":r=JSON.stringify(r,null,s.width?parseInt(s.width):0);break;case"e":r=s.precision?parseFloat(r).toExponential(s.precision):parseFloat(r).toExponential();break;case"f":r=s.precision?parseFloat(r).toFixed(s.precision):parseFloat(r);break;case"g":r=s.precision?String(Number(r.toPrecision(s.precision))):parseFloat(r);break;case"o":r=(parseInt(r,10)>>>0).toString(8);break;case"s":r=String(r),r=s.precision?r.substring(0,s.precision):r;break;case"t":r=String(!!r),r=s.precision?r.substring(0,s.precision):r;break;case"T":r=Object.prototype.toString.call(r).slice(8,-1).toLowerCase(),r=s.precision?r.substring(0,s.precision):r;break;case"u":r=parseInt(r,10)>>>0;break;case"v":r=r.valueOf(),r=s.precision?r.substring(0,s.precision):r;break;case"x":r=(parseInt(r,10)>>>0).toString(16);break;case"X":r=(parseInt(r,10)>>>0).toString(16).toUpperCase()}g.json.test(s.type)?d+=r:(!g.number.test(s.type)||c&&!s.sign?l="":(l=c?"+":"-",r=r.toString().replace(g.sign,"")),o=s.pad_char?"0"===s.pad_char?"0":s.pad_char.charAt(1):" ",p=s.width-(l+r).length,a=s.width&&0<p?o.repeat(p):"",d+=s.align?l+r+a:"0"===o?l+a+r:a+l+r)}return d}(function(e){if(p[e])return p[e];var t,r=e,n=[],i=0;for(;r;){if(null!==(t=g.text.exec(r)))n.push(t[0]);else if(null!==(t=g.modulo.exec(r)))n.push("%");else{if(null===(t=g.placeholder.exec(r)))throw new SyntaxError("[sprintf] unexpected placeholder");if(t[2]){i|=1;var s=[],a=t[2],o=[];if(null===(o=g.key.exec(a)))throw new SyntaxError("[sprintf] failed to parse named argument key");for(s.push(o[1]);""!==(a=a.substring(o[0].length));)if(null!==(o=g.key_access.exec(a)))s.push(o[1]);else{if(null===(o=g.index_access.exec(a)))throw new SyntaxError("[sprintf] failed to parse named argument key");s.push(o[1])}t[2]=s}else i|=2;if(3===i)throw new Error("[sprintf] mixing positional and named placeholders is not (yet) supported");n.push({placeholder:t[0],param_no:t[1],keys:t[2],sign:t[3],pad_char:t[4],align:t[5],width:t[6],precision:t[7],type:t[8]})}r=r.substring(t[0].length)}return p[e]=n}(e),arguments)}function e(e,t){return y.apply(null,[e].concat(t||[]))}var p=Object.create(null);"undefined"!=typeof exports&&(exports.sprintf=y,exports.vsprintf=e),"undefined"!=typeof window&&(window.sprintf=y,window.vsprintf=e,"function"==typeof define&&define.amd&&define(function(){return{sprintf:y,vsprintf:e}}))}();

// Globale

bizcalMedicalSpecialitiesInitialized = false;
bizcalLocationsInitialized = false;
bizcalMedicalServicesInitialized = false;
bizcalPaymentTypesInitialized = false;
bizcalPhysiciansInitialized = false;
bizcalAppointmentDateSelected = false;
bizcalDisableAutoRefreshAppointmentAvailability = false;
bizcalShowPreferredPhysicianBox = true;

bizcalReqGetMedicalSpecialities = null;
bizcalReqGetLocations = null;
bizcalReqGetMedicalServices = null;
bizcalReqGetPhysicians = null;
bizcalReqGetPaymentTypes = null;
bizcalReqGetAppointmentAvailabilities = null;
bizcalReqRegisterAppointment = null;
bizcalReqGetPriceForService = null;

bizcalIsMobileVersion = false;

bizcalDefaultSpeciality = "";
bizcalDefaultService = "";
bizcalDefaultPhysician = "";
bizcalDefaultLocation = "";

// Utile

String.prototype.formatBizStrSprintf = String.prototype.formatBizStrSprintf || function ()
{
    "use strict";
	console.log('loading str');
	
	if (!arguments.length) {
		return this.toString();
	}
	var args = (["string","number"].indexOf(typeof arguments[0])>-1) ?
		Array.prototype.slice.call(arguments)
		: arguments[0];
		
	if(jQuery.isEmptyObject(args)){
		return;
	}
	var data_by_index = [''];
	jQuery.each(args, function(){
		data_by_index.push(this);
	});
    var str = this.toString();
	var index = 0;
	var f = function (m0,m1,m2,m3) {
		if(['number','string'].indexOf(typeof args[m1]) < 0){
			if(undefined === typeof args[m1] && 'number' === typeof m1 && ['number','string'].indexOf(typeof args[parseFloat(m1)])){
				return args[parseInt(m1)];
			}
			return m0;
		}
		return args[m1];
	};
	str = str.replace(/{{\s*(\w+)\s*}}/g, f);
	str = str.replace(/{\s*(\w+)\s*}/g, f);
	str = str.replace(/(%*)%([\'\*\-\.\d\w]+?)?(\d+)?(\$)?([bcdeEufFgGosxX])/g, function (m0,m1,m2,m3,m4,m5,m6,m7,m8) {
		if(m1 && (m1.length % 2)){
			return m0.slice(0,m0.length/2) + m0.replace(/^%+/,'');
		}
		var has_dollar_sign = !!m4;
		var should_decrement = false;
		var needed_index;
		if(has_dollar_sign){
			if(m3 === undefined){
				m3 = m2;
				m2 = '';
			}
			needed_index = parseInt(m3);
		} else {
			needed_index = ++index;
			should_decrement = true;
		}
		
		if(['number','string'].indexOf(typeof data_by_index[needed_index]) < 0){
			if (should_decrement) {
				index--;
			};
			return m0;
		}
		
		var to_return = sprintf('' + (m1 === undefined ? '' : m1) + '%' + (m2 === undefined ? '' : m2) + (m3 === undefined ? '' : m3) + (m5 === undefined ? '' : m5), data_by_index[needed_index]);
		
		if (false === to_return) {
			if (should_decrement) {
				index--;
			};
			return m0;
		}
		return to_return;
		
	});
	return str;
};


String.prototype.formatBizStr = String.prototype.formatBizStr || String.prototype.formatBizStrSprintf;
/* 
String.prototype.formatBizStr = String.prototype.formatBizStr || function ()
{
    "use strict";
    var str = this.toString();
    if (arguments.length) {
        var t = typeof arguments[0];
        var key;
        var args = ("string" === t || "number" === t) ?
            Array.prototype.slice.call(arguments)
            : arguments[0];

        for (key in args) {
            str = str.replace(new RegExp("\\{" + key + "\\}", "gi"), args[key]);
        }
    }

    return str;
};
 */
function BizcalRenderCaptcha()
{
	grecaptcha.render('bizcal-g-recaptcha', {'sitekey' : setrio_bizcal_ajax.g_site_key});
}

function compareMedicalSpecialities(a, b)
{
    if (a.text > b.text)
        return 1;
    else if (a.text < b.text)
        return -1;
    else
        return 0;
}

function checkLength(object, description)
{
    if (object.val().trim().length == 0)
    {
        object.addClass("ui-state-error");
        updateTips(setrio_bizcal_ajax.msg_field_missing + " " + description + "!");
        return false;
    }
    else
    {
        return true;
    }
}

function checkChecked(object, description)
{
    if (!object || !object.length){
		return true;
	}
    if (!object.is(':checked'))
    {
        object.addClass("ui-state-error");
        updateTips(setrio_bizcal_ajax.msg_field_missing + " " + description + "!");
        return false;
    }
    else
    {
        return true;
    }
}

function checkRegexp(object, regexp, description)
{
    if ( !( regexp.test(object.val() ) ) )
    {
        object.addClass("ui-state-error");
        updateTips(description);
        return false;
    }
    else
    {
        return true;
    }
}

function isFormWithPopups()
{
    return false;
    /*var numButtons = jQuery("bizcal-check-availability").length;
    return (numButtons > 0);*/
}

// Mesaje popup standard

function showInfoMessage(message, title)
{              
    jQuery("#bizcal-info-dialog").html(message);
    jQuery("#bizcal-info-dialog").dialog({
        modal: true,
        title: title,
        width: "80%",
        dialogClass: 'bizcal-dialog-info',
        buttons: {
            Ok: function() {
                jQuery(this).dialog("close");
            }
        },
        appendTo: '.bizcal-main-box',
        //'open': function () { jQuery('.ui-dialog').wrap('<div class="bizcal-main-box"></div>'); }
    });
}

function showWarningMessage(message, title)
{
    jQuery("#bizcal-warning-dialog").html(message);
    jQuery("#bizcal-warning-dialog").dialog({
        modal: true,
        title: title,
        width: "80%",
        dialogClass: 'bizcal-dialog-warning',
        buttons: {
            Ok: function() {
                jQuery(this).dialog("close");
            }
        },
        appendTo: '.bizcal-main-box',
        //'open': function () { jQuery('.ui-dialog').wrap('<div class="bizcal-main-box"></div>'); }
    });
}

function showErrorMessage(message, title)
{
    jQuery("#bizcal-error-dialog").html(message);
    jQuery("#bizcal-error-dialog").dialog({
        modal: true,
        title: title,
        width: "80%",
        dialogClass: 'bizcal-dialog-error',
        classes: {
            'ui-dialog': 'ui-dialog',
            'ui-dialog-titlebar': 'ui-dialog-titlebar',
        },
        buttons: {
            Ok: function() {
                jQuery(this).dialog("close");
            }
        },
        appendTo: '.bizcal-main-box',
        //'open': function () { jQuery('.ui-dialog').wrap('<div class="bizcal-main-box"></div>'); }
    });
}

function showYesNoMessage(message, title)
{
    console.log("INIT YESNO POPUP...");
    jQuery("#bizcal-warning-dialog").html(message);
    console.log("YESNO POPUP - AFTER SETTING MESSAGE");
    jQuery("#bizcal-warning-dialog").dialog({
        modal: true,
        title: title,
        width: "80%",
        dialogClass: 'bizcal-dialog-warning',
        buttons: {
            "Da": function() {
                jQuery("#bizcal-sel-date").datepicker("setDate", realRecommendedDate);
                jQuery(this).dialog("close");
                if ( (jQuery("#setrio-bizcal-popup-btn-next").length > 0) && (jQuery("#setrio-bizcal-page-1").is(":visible")) )
                {
                    console.log("VARIANA POPUP ACTIVA!");
                    setrioBizcalPopupContinue();
                }
                else
                    console.log("VARIANA POPUP INACTIVA!");
            },
            "Nu": function() {
                resetAvailability(true);
                jQuery(this).dialog("close");
            }
        },
        close: function( event, ui ) {
            if (event.originalEvent) // E un obiect daca s-a apasat Esc sau s-a inchis de la [X]
            {
                //console.log(event);
                resetAvailability(true);
            }
        },
        appendTo: '.bizcal-main-box',
        //'open': function () { jQuery('.ui-dialog').wrap('<div class="bizcal-main-box"></div>'); }
    });
    console.log("YESNO POPUP - AFTER SHOW");
}

// Metode apel webservice

function setrioBizcalSelect2Destroy(d){
	var $d = jQuery(d);
	if(!$d.length){
		console.warn('select2 elem does not exist');
		return;
	}
	if($d.data('select2')){
		$d.select2('destroy');
		return;
	}
}
function setrioBizcalSelect2JqueryUI(d){
	if(!setrio_bizcal_ajax['enableCustomJQueryUI']){
		return;
	}
	var $d = jQuery(d);
	if(!$d.length){
		console.warn('select2 elem does not exist');
		return;
	}
	console.warn($d[0].id, $d);
	var ds = $d.data('select2');
	if(!ds){
		console.warn('noselect2');
		return;
	}
	console.log(ds);
	ds.dropdown.$search.addClass('text ui-widget-content ui-corner-all');
	ds.$dropdown.addClass('setrio-bizcal-related').children().addClass('ui-menu ui-corner-all ui-widget ui-widget-content');
	ds.$dropdown.find('.select2-results__options').addClass('ui-corner-all ui-widget ui-widget-content');
	if(!ds.$selection.children('.select2-selection__arrow').length){
		console.warn('noselect2 selectionarrow found');
	}
	ds.$selection.children('.select2-selection__arrow').addClass('ui-icon ui-icon-triangle-1-s');
	console.log(ds.$selection.children('.select2-selection__arrow'));
	$d.on('select2:opening',function(a,b,c){
		var ds = $d.data('select2');
		ds.$selection.children('.select2-selection__arrow').removeClass('ui-icon ui-icon-triangle-1-s').addClass('ui-icon ui-icon-triangle-1-n');
	}).on('select2:close',function(a,b,c){
		var ds = $d.data('select2');
		ds.$selection.children('.select2-selection__arrow').removeClass('ui-icon ui-icon-triangle-1-n').addClass('ui-icon ui-icon-triangle-1-s');
	}).on('change',function(a,b,c){
		var ds = $d.data('select2');
		if(!ds) return;
		ds.$selection.children('.select2-selection__arrow').removeClass('ui-icon ui-icon-triangle-1-n').addClass('ui-icon ui-icon-triangle-1-s');
	});
	
	ds.$dropdown.on('mouseenter','.select2-results__option', function(){
		if(jQuery(this).hasClass('select2-results__message')){
			return;
		}
		jQuery(this).siblings().removeClass('ui-state-active');
		jQuery(this).addClass('ui-state-active');
	})
}
			
function wsGetMedicalSpecialities()
{
    if (bizcalReqGetMedicalSpecialities != null)
    {
        bizcalReqGetMedicalSpecialities.abort();
        bizcalReqGetMedicalSpecialities = null;
    }
    
    bizcalReqGetMedicalSpecialities = jQuery.post({
            url: setrio_bizcal_ajax.ajax_url,
            data: {
                _ajax_nonce: setrio_bizcal_ajax.nonce,
                action: "get_medical_specialities",
            },
            dataType: "json"
        })
        .done(function(data){
            var selectData = [];
            data = JSON.parse(data);

            if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
            {
                if (!setrio_bizcal_ajax.autosel_speciality)
                {
                    newItem = { "id": "", "text": "", selected: (bizcalDefaultSpeciality == "")}
                    selectData.push(newItem);
                }
                
                for (var medSpeciality in data.Specialities)
                {
                    newItem = {
                        "id": data.Specialities[medSpeciality].Code,
                        "text": data.Specialities[medSpeciality].Name,
                        "selected": data.Specialities[medSpeciality].Code.toUpperCase().trim() == bizcalDefaultSpeciality.toUpperCase().trim()
                        };
                    selectData.push(newItem);
                }
            }
            else
            {
                showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
            }
			if(!setrio_bizcal_ajax.speciality_order || !parseInt(Number(setrio_bizcal_ajax.speciality_order))){
				selectData.sort(compareMedicalSpecialities);
			}
			
			setrioBizcalSelect2Destroy(".bizcal-sel-spec");
            jQuery(".bizcal-sel-spec").html("");
            jQuery(".bizcal-sel-spec").select2({
                theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
                placeholder: setrio_bizcal_ajax.msg_medical_speciality_placeholder,
                minimumResultsForSearch: bizcalIsMobileVersion ? -1 : 0,
                data: selectData,
				// templateResult: function(result,container) {
					// container.className += ' ui-menu-item-wrapper';
					// return result.text;
				// }
            });
			setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-spec"));
            
            console.log("SELECTED SPECIALITY", getSelectedMedicalSpecialityCode().toUpperCase().trim());
            console.log(bizcalDefaultSpeciality.toUpperCase().trim());
            
            if ((bizcalDefaultSpeciality.length > 0) && (getSelectedMedicalSpecialityCode().toUpperCase().trim() == bizcalDefaultSpeciality.toUpperCase().trim()))
                jQuery(".bizcal-sel-spec").prop("disabled", true);
            else
                jQuery(".bizcal-sel-spec").prop("disabled", false);

            bizcalMedicalSpecialitiesInitialized = true;
            
            doOnSelectMedicalSpeciality();
                       
            bizcalReqGetMedicalSpecialities = null;
        })
        .fail(function(req){
            if ((req.status !== 0) || (req.statusText !== "abort"))
            {
                showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
            }
        });   
}

function getMedicalSpecialitiesFromService(context)
{
    const params = new URLSearchParams();
    params.append('_ajax_nonce', setrio_bizcal_ajax.nonce);
    params.append('action', 'get_medical_specialities');

    const config = {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    };

    axios.post(setrio_bizcal_ajax.ajax_url, params, config)
        .then((response) => {
            if (response.status = 200)
            {
                var selectData = [];
                var selectedItem = null;
                var firstItem = null;

                var data = JSON.parse(response.data);

                if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
                {
                    for (var medSpeciality in data.Specialities)
                    {
                        newItem = {
                            value: data.Specialities[medSpeciality].Code,
                            label: data.Specialities[medSpeciality].Name,
                        };
                        
                        if (!firstItem)
                            firstItem = newItem;
                        if (newItem.value.toUpperCase().trim() == bizcalDefaultSpeciality.toUpperCase().trim())
                            selectedItem = newItem;
                        
                        selectData.push(newItem);
                    }
					if(!setrio_bizcal_ajax.speciality_order || !parseInt(Number(setrio_bizcal_ajax.speciality_order))){
						selectData.sort(compareMedicalSpecialities);
					}
                    
                    context.commit('setSpecialities', selectData);
                    
                    if (selectedItem != null)
                    {
                        context.selectedSpeciality = selectedItem;
                    }
                    else if ((setrio_bizcal_ajax.autosel_speciality) && (firstItem != null))
                    {
                        context.selectedSpeciality = firstItem;
                    }
                }
                else
                {
                    showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
                    context.commit('setSpecialities', []);
                    context.selectedSpeciality = null;
                }
            }
            else
            {
                showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
                context.commit('setSpecialities', []);
                context.selectedSpeciality = null;
            }
        })
        .catch((err) => {
            alert(err);
            /*if ((req.status !== 0) || (req.statusText !== "abort"))
            {
                showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
            }
            context.commit('setSpecialities', []);
            context.selectedSpeciality = null;*/
        });    
}

function wsGetLocations(speciality_code)
{
    if (bizcalReqGetLocations != null)
    {
        bizcalReqGetLocations.abort();
        bizcalReqGetLocations = null;
    }
    
	setrioBizcalSelect2Destroy(".bizcal-sel-location");
    jQuery(".bizcal-sel-location").val(null).trigger("change");
    jQuery(".bizcal-sel-location").attr("data-placeholder", "Se încarcă...");
    jQuery(".bizcal-sel-location").select2({
		theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
	});
	
	setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-location"));

    bizcalReqGetLocations = jQuery.post({
            url: setrio_bizcal_ajax.ajax_url,
            data: {
                _ajax_nonce: setrio_bizcal_ajax.nonce,
                action: "get_locations",
                speciality_code: speciality_code,
            },
            dataType: "json"
        })
        .done(function(data){
            var selectData = [];
            data = JSON.parse(data);
            
            console.log(data.ErrorCode);
            console.log(data.ErrorMessage);
            console.log(data);

			var selectedLocationUID = null;
			// bizcalDefaultLocation;
            if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
            {
                if (!setrio_bizcal_ajax.autosel_location)
                {
                    newItem = { "id": "", "text": ""}
                    selectData.push(newItem);
                }
                
                for (var medLocation in data.Locations)
                {
                    if ((data.Locations[medLocation].IsActive) && (data.Locations[medLocation].AllowWebAppointments))
                    {
                        newItem = {
                            "id": data.Locations[medLocation].UID,
                            "text": data.Locations[medLocation].LocationName
                            };
						if(undefined !== bizcalDefaultLocation && '' !== bizcalDefaultLocation && null === selectedLocationUID){
							if(setrioBizcalSafeText(data.Locations[medLocation].LocationName) === bizcalDefaultLocation){
								selectedLocationUID = data.Locations[medLocation].UID;
							}
						}
                        selectData.push(newItem);
                    }
                }
            }
            else
            {
                showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
            }
            
            selectData.sort(compareMedicalSpecialities);
			
			setrioBizcalSelect2Destroy(".bizcal-sel-location");
                    
            jQuery(".bizcal-sel-location").html("");
            jQuery(".bizcal-sel-location").val(null).trigger("change");
            jQuery(".bizcal-sel-location").attr("data-placeholder", setrio_bizcal_ajax.msg_location_placeholder);
            jQuery(".bizcal-sel-location").select2({
				theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
                placeholder: setrio_bizcal_ajax.msg_location_placeholder,
                minimumResultsForSearch: bizcalIsMobileVersion ? -1 : 0,
                data: selectData,
            });
			
			setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-location"));

            if (!setrio_bizcal_ajax.autosel_location)
            {
				
                jQuery(".bizcal-sel-location").val(selectedLocationUID).trigger('change');
                jQuery(".bizcal-sel-location").attr("data-placeholder", setrio_bizcal_ajax.msg_location_placeholder);
                jQuery("span#select2-bizcal-sel-location-container span.select2-selection__placeholder").text(setrio_bizcal_ajax.msg_location_placeholder);
				if(null !== selectedLocationUID){
					jQuery(".bizcal-sel-location").prop("disabled", true);
				} else {
					jQuery(".bizcal-sel-location").prop("disabled", false);
				}
            }

            doOnSelectLocation();
            
            bizcalLocationsInitialized = true;
            console.log("LOC", getSelectedLocationUID());
            
            bizcalReqGetLocations = null;
        })
        .fail(function(req){
            if ((req.status !== 0) || (req.statusText !== "abort"))
            {
                showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
            }
            
            bizcalReqGetLocations = null;
        });   
}

function getLocationsFromService(context)
{   
    if (context.state.selectedSpeciality == null)
    {
        context.commit({type: 'setLocations', locations: [], selectedLocation: null});
        return;
    }
    
    const params = new URLSearchParams();
    params.append('_ajax_nonce', setrio_bizcal_ajax.nonce);
    params.append('action', 'get_locations');
    params.append('speciality_code', context.state.selectedSpeciality.value);

    const config = {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    };

    axios.post(setrio_bizcal_ajax.ajax_url, params, config)
        .then((response) => {
            if (response.status = 200)
            {  
                var selectData = [];
                var selectedItem = null;
                var firstItem = null;
                data = JSON.parse(response.data);
            
                if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
                {
                    for (var medLocation in data.Locations)
                    {
                        if ((data.Locations[medLocation].IsActive) && (data.Locations[medLocation].AllowWebAppointments))
                        {
                            newItem = {
                                value: data.Locations[medLocation].UID,
                                label: data.Locations[medLocation].LocationName
                                };
                                
                            if (!firstItem)
                                firstItem = newItem;
                            //if (newItem.value.toUpperCase().trim() == bizcalDefaultSpeciality.toUpperCase().trim())
                            //    selectedItem = newItem;
                                
                            selectData.push(newItem);
                        }
                    }

                    var item = null;
                    if (selectedItem != null)
                        item = selectedItem;
                    else if ((setrio_bizcal_ajax.autosel_location) && (firstItem != null))
                        item = firstItem;

                    console.log('selecting location', item);
                    context.commit({type: 'setLocations', locations: selectData, selectedLocation: item});

                    /*if ((focusWhenDone) && (appointmentApp.$refs.locationCombo))
                    {
                        appointmentApp.$refs.locationCombo.focus();
                        if (appointmentApp.selectedLocation == null)
                            appointmentApp.$refs.locationCombo.showPopup();
                    }               */
                }
                else
                {
                    showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
                    context.commit({type: 'setLocations', locations: [], selectedLocation: null});
                }
            }
            else
            {
                showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
                context.commit({type: 'setLocations', locations: [], selectedLocation: null});
            }
        })
        .catch((err) => {
            showErrorMessage(err, setrio_bizcal_ajax.msg_error);
            context.commit({type: 'setLocations', locations: [], selectedLocation: null});
        });   
}

function getAllowedPaymentTypesFromService(context)
{
    if (context.state.selectedSpeciality == null)
    {
        context.commit({type: 'setPaymentTypes', paymentTypes: [], selectedPaymentType: null});
        return;
    }
    if ((setrio_bizcal_ajax.enable_multiple_locations) && (context.state.selectedLocation == null))
    {
        context.commit({type: 'setPaymentTypes', paymentTypes: [], selectedPaymentType: null});
        return;
    }

    const params = new URLSearchParams();
    params.append('_ajax_nonce', setrio_bizcal_ajax.nonce);
    params.append('action', 'get_allowed_payment_types');
    params.append('speciality_code', context.state.selectedSpeciality.value);

    const config = {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    };

    axios.post(setrio_bizcal_ajax.ajax_url, params, config)
        .then((response) => {
            if (response.status = 200)
            { 
                var selectData = [];
                var selectedItem = null;
                var firstItem = null;
                data = JSON.parse(response.data);
                
                if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
                {
                    if (data.PaymentTypeList != null)
                    {
                        for (var index in data.PaymentTypeList)
                        {
                            newItem = {
                                value: data.PaymentTypeList[index].ID,
                                label: data.PaymentTypeList[index].Description
                            };
                            
                            if (!firstItem)
                                firstItem = newItem;
                            
                            selectData.push(newItem);
                        }

                        var item = null;
                        if (selectedItem != null)
                            item = selectedItem;
                        else if ((setrio_bizcal_ajax.autosel_payment_type) && (firstItem != null))
                            item = firstItem;
                        else if ((selectData.length <= 1) && (firstItem != null))
                            item = firstItem;
                        console.log('select payment type => ', item);
                        context.commit({ type: 'setPaymentTypes', paymentTypes: selectData, selectedPaymentType: item });
                    }
                    else
                    {
                        context.commit({type: 'setPaymentTypes', paymentTypes: [], selectedPaymentType: null});
                    }
                }
                else
                {
                    showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
                    context.commit({type: 'setPaymentTypes', paymentTypes: [], selectedPaymentType: null});
                }
            }
            else
            {
                showErrorMessage(response.statusText, setrio_bizcal_ajax.msg_error);
                context.commit({type: 'setPaymentTypes', paymentTypes: [], selectedPaymentType: null});
            }

        })
        .catch((err) => {
            showErrorMessage(err, setrio_bizcal_ajax.msg_error);
            context.commit({type: 'setPaymentTypes', paymentTypes: [], selectedPaymentType: null});
        }); 
}

function getMedicalServicesFromService(context)
{   
    if (context.state.selectedSpeciality == null)
    {
        context.commit({type: 'setServices', services: [], selectedService: null});
        return;
    }
    if ((setrio_bizcal_ajax.enable_multiple_locations) && (context.state.selectedLocation == null))
    {
        context.commit({type: 'setServices', services: [], selectedService: null});
        return;
    }
    if (context.state.selectedPaymentType == null)
    {
        context.commit({type: 'setServices', services: [], selectedService: null});
        return;
    }

    const params = new URLSearchParams();

    if ((setrio_bizcal_ajax.appointment_param_order == 0)
        || (context.state.selectedPreferredPhysician == null)
        || ((context.state.selectedPreferredPhysician != null) && (context.state.selectedPreferredPhysician.value == "")))
    {
        params.append('_ajax_nonce', setrio_bizcal_ajax.nonce);
        params.append('action', 'get_medical_services');
        params.append('speciality_code', context.state.selectedSpeciality.value);
        params.append('physician_uid', context.state.selectedPreferredPhysician.value);
        if (context.state.selectedLocation != null)
            params.append('location_uid', context.state.selectedLocation.value);

        const config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        };

        axios.post(setrio_bizcal_ajax.ajax_url, params, config)
            .then((response) => {
                if (response.status = 200)
                {
                    var selectData = [];
                    var selectedItem = null;
                    var firstItem = null;
                    data = JSON.parse(response.data);
                
                    if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
                    {
                        for (var index in data.MedicalServices)
                        {
                            newItem = {
                                value: data.MedicalServices[index].UID,
                                label: setrio_bizcal_ajax.med_serv_all_caps ? data.MedicalServices[index].Name.toUpperCase() : data.MedicalServices[index].Name,
                                price: data.MedicalServices[index].Price
                            };
                                
                            if (firstItem == null)
                                firstItem = newItem;
                            if (data.MedicalServices[index].Name.toUpperCase().trim() == bizcalDefaultService.toUpperCase().trim())
                                selectedItem = newItem;
                                
                            selectData.push(newItem);
                        }

                        selectData.sort(compareMedicalSpecialities);

                        var item = null;
                        if (selectedItem != null)
                        {
                            item = selectedItem;
                        }
                        else if ((setrio_bizcal_ajax.autosel_service) && (firstItem != null))
                        {
                            item = firstItem;
                        }

                        console.log('setting services...');

                        context.commit({type: 'setServices', services: selectData, selectedService: item});
                    }
                    else
                    {
                        showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
                        context.commit({type: 'setServices', services: [], selectedService: null});
                    }
                }
                else
                {
                    showErrorMessage(response.statusText, setrio_bizcal_ajax.msg_error);
                    context.commit({type: 'setServices', services: [], selectedService: null});
                }
            })
            .catch((err) => {
                showErrorMessage(err, setrio_bizcal_ajax.msg_error);
                context.commit({type: 'setServices', services: [], selectedService: null});
            });
    }
    else
    {
        // Preiau serviciile cu pret exact
        params.append('_ajax_nonce', setrio_bizcal_ajax.nonce);
        params.append('action', 'get_prices');
        params.append('speciality_code', context.state.selectedSpeciality.value);
        params.append('physician_uid', context.state.selectedPreferredPhysician.value);
        params.append('location_uid', ((context.state.selectedLocation != null) ? context.state.selectedLocation.value : null));

        const config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        };

        axios.post(setrio_bizcal_ajax.ajax_url, params, config)
            .then((response) => {
                if (response.status = 200)
                {
                    var selectData = [];
                    var selectedItem = null;
                    var firstItem = null;
                    data = JSON.parse(data);

                    if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
                    {
                        for (var index in data.MedicalServices)
                        {
                            newItem = {
                                value: data.MedicalServices[index].UID,
                                label: setrio_bizcal_ajax.med_serv_all_caps ? data.MedicalServices[index].Name.toUpperCase() : data.MedicalServices[index].Name,
                                price: data.MedicalServices[index].Price
                            };
                                
                            if (firstItem == null)
                                firstItem = newItem;
                            if (data.MedicalServices[index].Name.toUpperCase().trim() == bizcalDefaultService.toUpperCase().trim())
                                selectedItem = newItem;
                                
                            selectData.push(newItem);
                        }
                        
                        selectData.sort(compareMedicalSpecialities);

                        var item = null;
                        if (selectedItem != null)
                        {
                            item = selectedItem;
                        }
                        else if ((setrio_bizcal_ajax.autosel_service) && (firstItem != null))
                        {
                            item = firstItem;
                        }
                        
                        context.commit({type: 'setServices', services: selectData, selectedService: item});
                    }
                    else
                    {
                        showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
                        context.commit({type: 'setServices', services: [], selectedService: null});
                    }
                }
                else
                {
                    showErrorMessage(response.statusText, setrio_bizcal_ajax.msg_error);
                    context.commit({type: 'setServices', services: [], selectedService: null});
                }
            })
            .catch((err) => {
                showErrorMessage(err, setrio_bizcal_ajax.msg_error);
                context.commit({type: 'setServices', services: [], selectedService: null});
            });
    }
}

function wsGetMedicalServices(speciality_code)
{
    selectedLocationUID = null;
    if (setrio_bizcal_ajax.enable_multiple_locations)
    {
        console.log("SISTEMUL CU MAI MULTE LOCATII ESTE ACTIV!");
        selectedLocationUID = getSelectedLocationUID();
        if (selectedLocationUID == "")
        {
            jQuery(".bizcal-sel-serv").attr("data-placeholder", setrio_bizcal_ajax.msg_medical_service_placeholder);
            jQuery("span#select2-bizcal-sel-serv-container span.select2-selection__placeholder").text(setrio_bizcal_ajax.msg_medical_service_placeholder);
            return;
        }
    }
    else
        console.log("SISTEMUL CU MAI MULTE LOCATII NU ESTE ACTIV!");
    
    if (bizcalReqGetMedicalServices != null)
    {
        bizcalReqGetMedicalServices.abort();
        bizcalReqGetMedicalServices = null;
    }

    initialSelectedServiceUID = getSelectedMedicalServiceUID();
	
	setrioBizcalSelect2Destroy(".bizcal-sel-serv");
    
    jQuery(".bizcal-sel-serv").val(null).trigger("change");
    jQuery(".bizcal-sel-serv").attr("data-placeholder", "Se încarcă...");
    jQuery(".bizcal-sel-serv").select2({theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',});
	
	setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-serv"));
    
    var selectedPhysicianUID = getPreferredPhysicianUID();

    if ((setrio_bizcal_ajax.appointment_param_order == 0) || (selectedPhysicianUID == ""))
    {
        console.log('varianta 1...', selectedPhysicianUID);
        bizcalReqGetMedicalServices = jQuery.post({
                url: setrio_bizcal_ajax.ajax_url,
                data: {
                    _ajax_nonce: setrio_bizcal_ajax.nonce,
                    action: "get_medical_services",
                    speciality_code: speciality_code,
                    physician_uid: selectedPhysicianUID != "" ? selectedPhysicianUID : null,
                    location_uid: selectedLocationUID
                },
                dataType: "json"
            })
            .done(function(data){
                var selectData = [];
                data = JSON.parse(data);

                console.log("DEFAULT SERVICE", bizcalDefaultService);
                
                if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
                {
                    if (!setrio_bizcal_ajax.autosel_service)
                    {
                        newItem = { "id": "", "text": "", "price": "", selected: (bizcalDefaultService == "") };
                        selectData.push(newItem);
                    }  
                    
                    for (var index in data.MedicalServices)
                    {
                        newItem = {
                            "id": data.MedicalServices[index].UID,
                            "text": setrio_bizcal_ajax.med_serv_all_caps ? data.MedicalServices[index].Name.toUpperCase() : data.MedicalServices[index].Name,
                            "price": data.MedicalServices[index].Price,
                            "selected": ( (data.MedicalServices[index].Name.toUpperCase().trim() == bizcalDefaultService.toUpperCase().trim())
                                || (data.MedicalServices[index].UID == initialSelectedServiceUID) )
                            };
                        selectData.push(newItem);
                    }
                }
                else
                {
                    showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
                }
                
                selectData.sort(compareMedicalSpecialities);
               
                selected_service_uid = getSelectedMedicalServiceUID();
				setrioBizcalSelect2Destroy(".bizcal-sel-serv");
                jQuery(".bizcal-sel-serv").html("");
                jQuery(".bizcal-sel-serv").attr("data-placeholder", setrio_bizcal_ajax.msg_medical_service_placeholder);
                jQuery(".bizcal-sel-serv").select2({
					theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
                    data: selectData,
                    placeholder: setrio_bizcal_ajax.msg_medical_service_placeholder,
                    minimumResultsForSearch: bizcalIsMobileVersion ? -1 : 0,
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    dropdownCssClass: setrio_bizcal_ajax.custom_dropdown_class,
                    templateResult: function(result) {
                        if (result['price'] === undefined) {
                            return result.text;
                        }
                        return "<div style='width: 100%; display: inline-table'><div style='width:100%; display: table-row'><div style='display: table-cell; width: 100%;'>" + result.text
                            + "</div><div style='display: table-cell; white-space: nowrap' class='price'>"
                            + result['price']
                            + "</div></div></div>";
                    },
                    templateSelection: function(result) {
                        if (result['price'] === undefined) {
                            return result.text;
                        }
                        return "<div style='width: 100%; display: inline-table'><div style='width:100%; display: table-row'>"
                            + "<div style='display: block; width: 100%; overflow: hidden; white-space: pre-line; max-height: 34px;'>" + result.text
                            + "</div><div style='display: table-cell; white-space: nowrap' class='bizcal-sel-serv-selected-price'>"
                            + result['price']
                            + "</div></div></div>";
                    }
                });
				
				setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-serv"));
                           
                if (selected_service_uid != "")
                {
                    var itemService = jQuery('.bizcal-sel-serv option[value="' + selected_service_uid + '"]');
                    if (itemService.length > 0)
                        jQuery(".bizcal-sel-serv").val(selected_service_uid).trigger('change');
                }
                else
                {
                    jQuery(".bizcal-sel-serv").attr("data-placeholder", setrio_bizcal_ajax.msg_medical_service_placeholder);
                    //console.log('place-1');
                    jQuery("span#select2-bizcal-sel-serv-container span.select2-selection__placeholder").text(setrio_bizcal_ajax.msg_medical_service_placeholder);
                    //console.log('place0');
                    /*jQuery(".bizcal-sel-serv").select2({
						theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
                        placeholder: setrio_bizcal_ajax.msg_medical_service_placeholder,
                    }).val("").trigger('change');*/
                    //console.log('place1');
                    //jQuery('.bizcal-sel-serv').val("").trigger('change');
                    //console.log('place2');
                }
                
                if (
                        (getSelectedMedicalServiceName().toUpperCase().trim() == bizcalDefaultService.toUpperCase().trim())
                        &&
                        (bizcalDefaultService.toUpperCase().trim().length > 0)
                    )
                    jQuery("#bizcal-sel-serv").prop("disabled", true);
                else
                    jQuery("#bizcal-sel-serv").prop("disabled", false);

                if ( (!isFormWithPopups()) && (!bizcalDisableAutoRefreshAppointmentAvailability) )
                {
                    checkAvailability(true);
                }           
                
                bizcalDisableAutoRefreshAppointmentAvailability = false;

                bizcalMedicalServicesInitialized = true;
                
                bizcalReqGetMedicalServices = null;
            })
            .fail(function(req){
                if ((req.status !== 0) || (req.statusText !== "abort"))
                {
                    showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
                }
            });
    }
    else
    {
        
        console.log('varianta 2...');
        bizcalReqGetMedicalServices = jQuery.post({
                url: setrio_bizcal_ajax.ajax_url,
                data: {
                    _ajax_nonce: setrio_bizcal_ajax.nonce,
                    action: "get_prices",
                    speciality_code: speciality_code,
                    physician_uid: selectedPhysicianUID,
                    location_uid: selectedLocationUID
                },
                dataType: "json"
            })
            .done(function(data){
                var selectData = [];
                data = JSON.parse(data);

                if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
                {
                    if (!setrio_bizcal_ajax.autosel_service)
                    {
                        newItem = { "id": "", "text": "", "price": "", "selected": (bizcalDefaultService == "") };
                        selectData.push(newItem);
                    }  
                    
                    for (var index in data.MedicalServices)
                    {
                        newItem = {
                            "id": data.MedicalServices[index].UID,
                            "text": setrio_bizcal_ajax.med_serv_all_caps ? data.MedicalServices[index].Name.toUpperCase() : data.MedicalServices[index].Name,
                            "price": data.MedicalServices[index].Price,
                            "selected": ( (data.MedicalServices[index].Name.toUpperCase().trim() == bizcalDefaultService.toUpperCase().trim())
                                || (data.MedicalServices[index].UID == initialSelectedServiceUID) )
                            };
                        selectData.push(newItem);
                    }
                }
                else
                {
                    showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
                }
                
                selectData.sort(compareMedicalSpecialities);
                
                selected_service_uid = getSelectedMedicalServiceUID();
				setrioBizcalSelect2Destroy(".bizcal-sel-serv");
                jQuery(".bizcal-sel-serv").html("");
                jQuery(".bizcal-sel-serv").attr("data-placeholder", setrio_bizcal_ajax.msg_medical_service_placeholder);
                jQuery(".bizcal-sel-serv").select2({
					theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
                    data: selectData,
                    placeholder: setrio_bizcal_ajax.msg_medical_service_placeholder,
                    minimumResultsForSearch: bizcalIsMobileVersion ? -1 : 0,
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    dropdownCssClass: setrio_bizcal_ajax.custom_dropdown_class,
                    templateResult: function(result) {
                        if (result['price'] === undefined) {
                            return result.text;
                        }
                        return "<div style='width: 100%; display: inline-table'><div style='width:100%; display: table-row'><div style='display: table-cell; width: 100%;'>" + result.text
                            + "</div><div style='display: table-cell; white-space: nowrap' class='price'>"
                            + result['price']
                            + "</div></div></div>";
                    },
                    templateSelection: function(result) {
                        if (result['price'] === undefined) {
                            return result.text;
                        }
                        return "<div style='width: 100%; display: inline-table'><div style='width:100%; display: table-row'>"
                            + "<div style='display: block; width: 100%; overflow: hidden; white-space: pre-line; max-height: 34px;'>" + result.text
                            + "</div><div style='display: table-cell; white-space: nowrap' class='bizcal-sel-serv-selected-price'>"
                            + result['price']
                            + "</div></div></div>";
                    }
                });
				setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-serv"));
                           
                if (selected_service_uid != "")
                {
                    var itemService = jQuery('.bizcal-sel-serv option[value="' + selected_service_uid + '"]');
                    if (itemService.length > 0)
                        jQuery(".bizcal-sel-serv").val(selected_service_uid).trigger('change');
                }
                else
                {
                    jQuery(".bizcal-sel-serv").attr("data-placeholder", setrio_bizcal_ajax.msg_medical_service_placeholder);
                    //console.log('place-1');
                    jQuery("span#select2-bizcal-sel-serv-container span.select2-selection__placeholder").text(setrio_bizcal_ajax.msg_medical_service_placeholder);
                    //console.log('place0');
                    /*jQuery(".bizcal-sel-serv").select2({
						theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
                        placeholder: setrio_bizcal_ajax.msg_medical_service_placeholder,
                    }).val("").trigger('change');*/
                    //console.log('place1');
                    //jQuery('.bizcal-sel-serv').val("").trigger('change');
                    //console.log('place2');
                }
                
                if (
                        (getSelectedMedicalServiceName().toUpperCase().trim() == bizcalDefaultService.toUpperCase().trim())
                        &&
                        (bizcalDefaultService.toUpperCase().trim().length > 0)
                    )
                    jQuery("#bizcal-sel-serv").prop("disabled", true);
                else
                    jQuery("#bizcal-sel-serv").prop("disabled", false);

                if ( (!isFormWithPopups()) && (!bizcalDisableAutoRefreshAppointmentAvailability) )
                {
                    checkAvailability(true);
                }           
                
                bizcalDisableAutoRefreshAppointmentAvailability = false;

                bizcalMedicalServicesInitialized = true;
                
                bizcalReqGetMedicalServices = null;
            })
            .fail(function(req){
                if ((req.status !== 0) || (req.statusText !== "abort"))
                {
                    showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
                }
            });
    }
}

function getPhysiciansFromService(context)
{
    const params = new URLSearchParams();

    params.append('_ajax_nonce', setrio_bizcal_ajax.nonce);
    params.append('action', 'get_physicians');
    params.append('speciality_code', context.state.selectedSpeciality.value);
    params.append('payment_type', context.state.selectedPaymentType.value);

    const config = {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    };

    axios.post(setrio_bizcal_ajax.ajax_url, params, config)
        .then((response) => {
            if (response.status = 200)
            {
                var selectData = [];
                var selectedItem = null;
                var firstItem = null;
                data = JSON.parse(response.data);
                
                var paymentType = 0;
                if (context.state.selectedPaymentType != null)
                    paymentType = context.state.selectedPaymentType.value;

                if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
                {
                    newItem = {
                        value: "0",
                        label: " " + setrio_bizcal_ajax.msg_any_available_physician,
                        price: "",
                        description: "",
                        picture_url: ""
                    };
                    
                    firstItem = newItem;
                    if (bizcalDefaultPhysician == "")
                        selectedItem = newItem;
                        
                    selectData.push(newItem);
                    
                    for (var index in data.Physicians)
                    {
                        console.log("NEW PHYSICIAN", data.Physicians[index]);
                        newItem = {
                            value: data.Physicians[index].UID,
                            label: data.Physicians[index].Name,
                            price: "",
                            description: data.Physicians[index].Description,
                            picture_url: data.Physicians[index].PictureURL
                        };
                                                    
                        if (paymentType == 2)
                        {
                            if (newItem.label == bizcalDefaultPhysician)
                                selectedItem = newItem;

                            if (data.Physicians[index].AllowCNAS == true)
                                selectData.push(newItem);
                        }
                        else
                        {
                            if (newItem.label == bizcalDefaultPhysician)
                                selectedItem = newItem;

                            if (data.Physicians[index].AllowPrivate == true)
                                selectData.push(newItem);
                        }
                    }
                    
                    selectData.sort(compareMedicalSpecialities);

                    var item = null;
                    if (selectedItem != null)
                    {
                        item = selectedItem;
                    }
                    else if (firstItem != null)
                    {
                        item = firstItem;
                    }

                    context.commit({type: 'setPreferredPhysicians', preferredPhysicians: selectData, selectedPreferredPhysician: item});
                }
                else
                {
                    showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
                    context.commit({type: 'setPreferredPhysicians', preferredPhysicians: [], selectedPreferredPhysician: null});
                }
            }
            else
            {
                showErrorMessage(response.statusText, setrio_bizcal_ajax.msg_error);
                context.commit({type: 'setPreferredPhysicians', preferredPhysicians: [], selectedPreferredPhysician: null});
            }
        })
        .catch((err) => {
            console.log("ERROR GETTING PHYSICIANS", err);
            showErrorMessage(err, setrio_bizcal_ajax.msg_error);
            context.commit({type: 'setPreferredPhysicians', preferredPhysicians: [], selectedPreferredPhysician: null});
        });
}

function wsGetPhysicians(speciality_code, payment_type, initialPreferredPhysicianUID)
{
    console.log("INIT - wsGetPhysicians");
    
    if (bizcalReqGetPhysicians != null)
    {
        bizcalReqGetPhysicians.abort();
        bizcalReqGetPhysicians = null;
        //console.log("wsGetPhysicians in progress, aborting request...");
    }

    console.log("INITIAL PHYSICIAN", initialPreferredPhysicianUID);
    
    bizcalReqGetPhysicians = jQuery.post({
            url: setrio_bizcal_ajax.ajax_url,
            data: {
                _ajax_nonce: setrio_bizcal_ajax.nonce,
                action: "get_physicians",
                speciality_code: speciality_code,
                payment_type: payment_type
            },
            dataType: "json"
        })
        .done(function(data){
            console.log("REQUEST DONE - wsGetPhysicians", initialPreferredPhysicianUID);
            
            var selectData = [];
            data = JSON.parse(data);
            
            var paymentType = getSelectedPaymentTypeID();

            newItem = { "id": "", "text": "" };
            selectData.push(newItem);

            if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
            {
                newItem = {
                    id: "0",
                    text: " " + setrio_bizcal_ajax.msg_any_available_physician,
                    price: "",
                    description: "",
                    picture_url: "",
                    selected: (bizcalDefaultPhysician == "")
                    };
                selectData.push(newItem);
                
                for (var index in data.Physicians)
                {
                    newItem = {
                        id: data.Physicians[index].UID,
                        text: data.Physicians[index].Name,
                        price: "",
                        description: data.Physicians[index].Description,
                        picture_url: data.Physicians[index].PictureURL,
                        selected: ( (data.Physicians[index].Name == bizcalDefaultPhysician) || (data.Physicians[index].UID == initialPreferredPhysicianUID) ),
                        };
                        
                    if (paymentType == 2)
                    {
                        if (data.Physicians[index].AllowCNAS == true)
                            selectData.push(newItem);
                    }
                    else
                    {
                        if (data.Physicians[index].AllowPrivate == true)
                            selectData.push(newItem);
                    }
                }
            }
            else
            {
                showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
            }
            
            selectData.sort(compareMedicalSpecialities);
			setrioBizcalSelect2Destroy(".bizcal-preferred-physician");
            jQuery(".bizcal-sel-preferred-physician").html("");
            jQuery(".bizcal-sel-preferred-physician").select2({
				theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
                data: selectData,
                minimumResultsForSearch: bizcalIsMobileVersion ? -1 : 0,
                escapeMarkup: function(markup) {
                        return markup;
                    },
                templateResult: function(result) {
                    if (typeof(result['price']) === "undefined") {
                        return result.text;
                    }
                    if (setrio_bizcal_ajax.show_physician_details)
                    {
                        return "<div style='width: 100%; display: inline-table'>"
                            + "<div style='width:100%; display: table-row'>"
                                + "<div style='display: table-cell; width: 100px; vertical-align: top; padding: 2px'>"
                                + "<img class='setrio-bizcal-picture-preview' src='" + result.picture_url + "' width='100' height='100' "
                                    + " style='max-height: 108px; width: auto; padding-right: 8px' align='top'>"
                                + "</div>"
                                + "<div style='display: block; width: 100%; overflow: hidden; white-space: pre-line; max-height: 100px; margin-top: 0px;'>"
                                + result.text
                                + "<br/><div class='bizcal-sel-med-small'>" + result.description + "</div>"
                                + "</div>"
                                + "<div style='display: table-cell; white-space: nowrap; vertical-align: middle;' class='bizcal-sel-med-selected-price'>"
                                + result['price']
                                + "</div></div></div>";                            
                    }
                    else
                    {
                        return "<div style='width: 100%; display: inline-table'><div style='width:100%; display: table-row'><div style='display: table-cell; width: 100%;'>" + result.text
                            + "</div><div style='display: table-cell; white-space: nowrap' class='price'>"
                            + result['price']
                            + "</div></div></div>";
                    }
                },
                templateSelection: function(result) {
                    if (result['price'] === undefined) {
                        return result.text;
                    }
                    return "<div style='width: 100%; display: inline-table'><div style='width:100%; display: table-row'>"
                        + "<div style='display: block; width: 100%; overflow: hidden; white-space: pre-line; max-height: 34px;'>" + result.text
                        + "</div><div style='display: table-cell; white-space: nowrap' class='bizcal-sel-med-selected-price'>"
                        + result['price']
                        + "</div></div></div>";                            
                }
            });
            
			setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-preferred-physician"));
			
            if ((bizcalDefaultPhysician == "") && (initialPreferredPhysicianUID == ""))
            {
                jQuery(".bizcal-sel-preferred-physician").val("0").trigger('change');
                jQuery(".bizcal-sel-preferred-physician").attr("data-placeholder", setrio_bizcal_ajax.msg_location_placeholder);
                jQuery("span#select2-bizcal-sel-preferred-physician-container span.select2-selection__placeholder").text(setrio_bizcal_ajax.msg_physician_placeholder);
            }
            else
            {
                jQuery(".bizcal-sel-preferred-physician").attr("data-placeholder", setrio_bizcal_ajax.msg_location_placeholder);
                jQuery("span#select2-bizcal-sel-preferred-physician-container span.select2-selection__placeholder").text(setrio_bizcal_ajax.msg_physician_placeholder);
                jQuery(".bizcal-sel-preferred-physician").trigger('change');
                doOnSelectPreferredPhysician();
            }
            
            //console.log("PREF_PHYS: " + getPreferredPhysicianName());
            if (bizcalDefaultPhysician != "")
            {
                jQuery(".bizcal-sel-preferred-physician").prop("disabled", getPreferredPhysicianName() == bizcalDefaultPhysician);
            }
            
            //jQuery('.bizcal-sel-preferred-physician').val(1).trigger('change');
            //jQuery(".bizcal-sel-preferred-physician").attr("data-placeholder", setrio_bizcal_ajax.msg_physician_placeholder);

            //var physicialManualSelectEnabled = setrio_bizcal_ajax.allow_search_physician;
            //var preferredPhysicianUID = getPreferredPhysicianUID();
            /*if ((physicialManualSelectEnabled) && (preferredPhysicianUID != ""))
            {
                var itemService = jQuery('.bizcal-sel-preferred-physician option[0]');
                if (itemService.length > 0)
                    jQuery(".bizcal-sel-preferred-physician").val(1).trigger('change');
                jQuery("#bizcal-sel-time-physician").css("display", "none");
            }*/
            
            if ( (!isFormWithPopups()) && (!bizcalDisableAutoRefreshAppointmentAvailability)
                && (setrio_bizcal_ajax.appointment_param_order == 0)
                && (bizcalDefaultPhysician == "") )
            {
                console.log('checking availability...');
                checkAvailability(true);
            }
            
            bizcalDisableAutoRefreshAppointmentAvailability = false;
            
            bizcalPhysiciansInitialized = true;
            
            bizcalReqGetPhysicians = null;
        })
        .fail(function(req){
            if ((req.status !== 0) || (req.statusText !== "abort"))
            {
                showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
            }
        });
}

function wsGetMedicalServicesPriceList(speciality_code, physician_uid)
{
    console.log("INIT - wsGetMedicalServicesPriceList");
    
    selectedLocationUID = null;
    if (setrio_bizcal_ajax.enable_multiple_locations)
    {
        selectedLocationUID = getSelectedLocationUID();
        if (selectedLocationUID == "")
        {
            jQuery(".bizcal-sel-serv").attr("data-placeholder", setrio_bizcal_ajax.msg_medical_service_placeholder);
            jQuery("span#select2-bizcal-sel-serv-container span.select2-selection__placeholder").text(setrio_bizcal_ajax.msg_medical_service_placeholder);
            return;
        }
    }
    
    if (bizcalReqGetMedicalServices != null)
    {
        bizcalReqGetMedicalServices.abort();
        bizcalReqGetMedicalServices = null;
    }
  
    bizcalReqGetMedicalServices = jQuery.post({
            url: setrio_bizcal_ajax.ajax_url,
            data: {
                _ajax_nonce: setrio_bizcal_ajax.nonce,
                action: "get_prices",
                speciality_code: speciality_code,
                physician_uid: physician_uid,
                location_uid: selectedLocationUID
            },
            dataType: "json"
        })
        .done(function(data){
            var selectData = [];
            data = JSON.parse(data);

            selected_service_uid = getSelectedMedicalServiceUID();
            var physician_uid = getSelectedPhysicianUID();
            var selected_physician_uid = getPreferredPhysicianUID();
            console.log("LOAD SERVICES - PHYSICIAN", selected_physician_uid);
            if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
            {
                if (!setrio_bizcal_ajax.autosel_service)
                {
                    newItem = { "id": "", "text": "", "price": ""};
                    selectData.push(newItem);
                }              
                
                for (var index in data.MedicalServices)
                {
                    newItem = {
                        "id": data.MedicalServices[index].UID,
                        "text": setrio_bizcal_ajax.med_serv_all_caps ? data.MedicalServices[index].Name.toUpperCase() : data.MedicalServices[index].Name,
                        "price": data.MedicalServices[index].Price,
                        };
                    selectData.push(newItem);

                    if (data.MedicalServices[index].UID == selected_service_uid)
                    {
                        if (physician_uid != selected_physician_uid)
                        {
                            jQuery("#bizcal-sel-time-service").html(setrio_bizcal_ajax.msg_price + " " + data.MedicalServices[index].Price);
                            jQuery("#bizcal-sel-time-service").css("display", "");
                        }
                        else
                        {
                            jQuery("#bizcal-sel-time-service").html("");
                            jQuery("#bizcal-sel-time-service").css("display", "none");
                        }
                    }
                }
            }
            else
            {
                showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
            }
            
            selectData.sort(compareMedicalSpecialities);
                
            selected_service_uid = getSelectedMedicalServiceUID();    
			
			setrioBizcalSelect2Destroy(".bizcal-sel-serv");
            jQuery(".bizcal-sel-serv").html("");
            jQuery(".bizcal-sel-serv").attr("data-placeholder", setrio_bizcal_ajax.msg_medical_service_placeholder);
            jQuery(".bizcal-sel-serv").select2({
				theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
                data: selectData,
                placeholder: setrio_bizcal_ajax.msg_medical_service_placeholder,
                minimumResultsForSearch: bizcalIsMobileVersion ? -1 : 0,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(result) {
                    if (result['price'] === undefined) {
                        return result.text;
                    }
                return "<div style='width: 100%; display: inline-table'><div style='width:100%; display: table-row'><div style='display: table-cell; width: 100%;'>" + result.text
                    + "</div><div style='display: table-cell; white-space: nowrap' class='price'>"
                    + result['price']
                    + "</div></div></div>";                   
                },
                templateSelection: function(result) {
                    if (result['price'] === undefined) {
                        return result.text;
                    }
                return "<div style='width: 100%; display: inline-table'><div style='width:100%; display: table-row'>"
                    + "<div style='display: block; width: 100%; overflow: hidden; white-space: pre-line; max-height: 34px;'>" + result.text
                    + "</div><div style='display: table-cell; white-space: nowrap' class='bizcal-sel-serv-selected-price'>"
                    + result['price']
                    + "</div></div></div>";
                }
            });
			
			setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-serv"));
            
            jQuery(".bizcal-sel-serv").attr("data-placeholder", setrio_bizcal_ajax.msg_medical_service_placeholder);
           
            if (selected_service_uid != "")
            {
                var itemService = jQuery('.bizcal-sel-serv option[value="' + selected_service_uid + '"]');
                if (itemService.length > 0)
                    jQuery(".bizcal-sel-serv").val(selected_service_uid).trigger('change');
            }
            else
            {
                jQuery(".bizcal-sel-serv").attr("data-placeholder", setrio_bizcal_ajax.msg_medical_service_placeholder);
                jQuery("span#select2-bizcal-sel-serv-container span.select2-selection__placeholder").text(setrio_bizcal_ajax.msg_medical_service_placeholder);
            }
            
            if ( (!isFormWithPopups()) && (!bizcalDisableAutoRefreshAppointmentAvailability) )
            {
                console.log("checking...");
                console.log(bizcalLocationsInitialized, getSelectedLocationUID());
                checkAvailability(true);
            }
          
            bizcalDisableAutoRefreshAppointmentAvailability = false;
            
            bizcalReqGetMedicalServices = null;
        })
        .fail(function(req){
            if ((req.status !== 0) || (req.statusText !== "abort"))
            {
                showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
            }
        });
}

function wsGetPaymentTypes()
{
    if (bizcalReqGetPaymentTypes != null)
    {
        bizcalReqGetPaymentTypes.abort();
        bizcalReqGetPaymentTypes = null;
    }
    
    bizcalReqGetPaymentTypes = jQuery.post({
            url: setrio_bizcal_ajax.ajax_url,
            data: {
                _ajax_nonce: setrio_bizcal_ajax.nonce,
                action: "get_payment_types"
            },
            dataType: "json"
        })
        .done(function(data){
            var selectData = [];
            data = JSON.parse(data);

            if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
            {
                if (!setrio_bizcal_ajax.autosel_payment_type)
                {
                    newItem = { "id": "", "text": ""}
                    selectData.push(newItem);
                }

                if (data.PaymentTypeList != null)
                {
                    for (var index in data.PaymentTypeList)
                    {
                        newItem = {
                            "id": data.PaymentTypeList[index].ID,
                            "text": ((getSelectedMedicalSpecialityCode() == 'MEDICINA DE FAMILIE') && (data.PaymentTypeList[index].ID == 2)) ? 'Gratuit - CNAS' : data.PaymentTypeList[index].Description,
                            };
                        selectData.push(newItem);
                    }
                }
            }
            else
            {
                showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
            }
			setrioBizcalSelect2Destroy(".bizcal-sel-payment");
            jQuery(".bizcal-sel-payment").html("");
            jQuery(".bizcal-sel-payment").select2({
				theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
                data: selectData,
                placeholder: setrio_bizcal_ajax.msg_payment_type_placeholder,
                minimumResultsForSearch: -1,
            });
			setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-payment"));
            
            jQuery(".bizcal-sel-payment").on("select2:select", doOnSelectPaymentType);
            
            if ( (!isFormWithPopups()) && (!bizcalDisableAutoRefreshAppointmentAvailability) )
            {
                checkAvailability(true);
            }
            
            if (data.PaymentTypeList == null)
                jQuery(".bizcal-sel-payment-box").css("display", "none");
            else if (data.PaymentTypeList.length == 1)
                jQuery(".bizcal-sel-payment-box").css("display", "none");
            else
                jQuery(".bizcal-sel-payment-box").css("display", "");
            
            bizcalPaymentTypesInitialized = true;
            
            bizcalReqGetPaymentTypes = null;
            
            doOnSelectPaymentType(null);
        })
        .fail(function(req){
            if ((req.status !== 0) || (req.statusText !== "abort"))
            {
                showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
            }
        });
}

function wsGetAllowedPaymentTypes(speciality_code)
{
    if (bizcalReqGetPaymentTypes != null)
    {
        bizcalReqGetPaymentTypes.abort();
        bizcalReqGetPaymentTypes = null;
    }
    
    bizcalReqGetPaymentTypes = jQuery.post({
            url: setrio_bizcal_ajax.ajax_url,
            data: {
                _ajax_nonce: setrio_bizcal_ajax.nonce,
                action: "get_allowed_payment_types",
                speciality_code: speciality_code,
            },
            dataType: "json"
        })
        .done(function(data){
            var selectData = [];

            data = JSON.parse(data);
            if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
            {
                if ((!setrio_bizcal_ajax.autosel_payment_type) && (data.PaymentTypeList != null) && (data.PaymentTypeList.length > 1))
                {
                    newItem = { "id": "", "text": ""}
                    selectData.push(newItem);
                }

                if (data.PaymentTypeList != null)
                {
                    for (var index in data.PaymentTypeList)
                    {
                        newItem = {
                            "id": data.PaymentTypeList[index].ID,
                            "text": ((speciality_code == 'MEDICINA DE FAMILIE') && (data.PaymentTypeList[index].ID == 2)) ? 'Gratuit - CNAS' : data.PaymentTypeList[index].Description
                            };
                        selectData.push(newItem);
                    }
                }
            }
            else
            {
                showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
            }
			setrioBizcalSelect2Destroy(".bizcal-sel-payment");
            jQuery(".bizcal-sel-payment").html("");
            jQuery(".bizcal-sel-payment").select2({
				theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
                data: selectData,
                placeholder: setrio_bizcal_ajax.msg_payment_type_placeholder,
                minimumResultsForSearch: -1,
            });
			setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-payment"));
            
            jQuery(".bizcal-sel-payment").on("select2:select", doOnSelectPaymentType);
            
            if ( (!isFormWithPopups()) && (!bizcalDisableAutoRefreshAppointmentAvailability) )
            {
                checkAvailability(true);
            }
            
            if (data.PaymentTypeList == null)
                jQuery(".bizcal-sel-payment-box").css("display", "none");
            else if (data.PaymentTypeList.length == 1)
                jQuery(".bizcal-sel-payment-box").css("display", "none");
            else
                jQuery(".bizcal-sel-payment-box").css("display", "");
            
            bizcalPaymentTypesInitialized = true;
            
            bizcalReqGetPaymentTypes = null;

            doOnSelectPaymentType(null)
        })
        .fail(function(req){
            if ((req.status !== 0) || (req.statusText !== "abort"))
            {
                showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
            }
        });
}

function getPhysicianAvailabilitiesText(data)
{
    var shownPhysician = "";
    var firstItemSelected = false;
    var result = "";
    for (var index in data)
    {
        if (shownPhysician != String(data[index].physician_uid))
        {
            if (setrio_bizcal_ajax.show_physician_details)
            {
                result += "<li class='setrio-bizcal-available-physician'>"
                    + "<div class='setrio-bizcal-available-physician-picture-container'><img class='setrio-bizcal-available-physician-picture' src='"
                    + (String(data[index].picture_url).length > 0
                      ? data[index].picture_url
                      : setrio_bizcal_ajax.plugins_url + "/css/images/physician-icon.png")
                    + "' width='100' height='100' align='top'></div>"
                    + "<div class='setrio-bizcal-available-physician-name'>" + data[index].text + "</div>"
                    + ((typeof(data[index].price) != "undefined") 
                      ? "<div class='setrio-bizcal-available-physician-price'><span class='label'>Tarif: </span>" + data[index].price + "</div>"
                      : ((getSelectedPaymentTypeID() == 2)
						 ? "<div class='setrio-bizcal-available-physician-price'><span class='label'>Tarif: </span>DECONTAT CNAS</div>"
						 : ""))
                    + "<div class='setrio-bizcal-available-physician-description'>" + data[index].description + "</div>"
                    + "</li>";
            }
            else
            {
                result += "<li class='setrio-bizcal-available-physician'>"
                    + "<div class='setrio-bizcal-available-physician-name'>" + data[index].text + "</div>"
				    + ((typeof(data[index].price) != "undefined")
                      ? "<div class='setrio-bizcal-available-physician-price'><span class='label'>Tarif: </span>" + data[index].price + "</div>"
					  : ((getSelectedPaymentTypeID() == 2)
						 ? "<div class='setrio-bizcal-available-physician-price'><span class='label'>Tarif: </span>DECONTAT CNAS</div>"
						 : ""))
                    + "</li>";
            }
            
            shownPhysician = String(data[index].physician_uid);
        }
        
        result = result + "<li class=\"ui-button" + (firstItemSelected ? "" : " ui-selected ui-state-active") 
            + " bizcal-sel-time-ph-" + String(data[index].physician_uid) + "\""
            + " data-physician-uid=\"" + String(data[index].physician_uid) + "\""
            + " data-physician-name=\"" + String(data[index].text) + "\""
            + " data-physician-price=\"" + String(data[index].price) + "\">"
            + String(data[index].start_date).substring(9,11)
            + ":" + String(data[index].start_date).substring(12,14) + " - "
            + String(data[index].end_date).substring(9,11)
            + ":" + String(data[index].end_date).substring(12,14) + "</li>";
        firstItemSelected = true;
    }
    
    return result;
}

function wsGetAppointmentAvailabilities(speciality_code, service_uid, physician_uid, payment_type_id, desired_date)
{
    if (!bizcalAppointmentDateSelected)
        return;
    
    startLoadingAvailability();
    
    selectedLocationUID = null;
    if (setrio_bizcal_ajax.enable_multiple_locations)
        selectedLocationUID = getSelectedLocationUID();
    
    if (bizcalReqGetAppointmentAvailabilities != null)
    {
        bizcalReqGetAppointmentAvailabilities.abort();
        bizcalReqGetAppointmentAvailabilities = null;
    }
console.log('ajax = getAvailabilities...', selectedLocationUID);
    bizcalReqGetAppointmentAvailabilities = jQuery.post({
            url: setrio_bizcal_ajax.ajax_url,
            data: {
                _ajax_nonce: setrio_bizcal_ajax.nonce,
                action: "get_availability",
                speciality_code: speciality_code,
                service_uid: service_uid,
                physician_uid: physician_uid,
                payment_type_id: payment_type_id,
                desired_date: desired_date,
                location_uid: selectedLocationUID
            },
            dataType: "json"
        })
        .done(function(data){
            var physician_uid = 0;
            var requestedDateAvailabilities = [];
            var recommendedDateAvailabilities = [];
            var canRegisterAppointment = true;
            var firstItemSelected = false;
            data = JSON.parse(data);

            if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
            {
                physician_uid = data.PhysicianUID;
                selected_physician_uid = "";/*getSelectedPhysicianUID();
                              
                if ((physician_uid != selected_physician_uid) && (selected_physician_uid.length > 0))
                {
                    showErrorMessage(setrio_bizcal_ajax.msg_err_get_appointment_hours, setrio_bizcal_ajax.msg_error);
                    resetAvailability(true);
                    canRegisterAppointment = false;                    
                }*/
                
                var physiciansData = [];
                var firstFoundPhysician = "";
                
                for (var availabilityIndex in data.Availabilities)
                {
                    if (!setrio_bizcal_ajax.autosel_physician)
                    {
                        newPhysicianItem = { "id": "", "text": "", "price": "" }
                        physiciansData.push(newPhysicianItem);
                    }
                    
                    var newPhysicianItem = {
                        "id": data.Availabilities[availabilityIndex].PhysicianUID,
                        "text": data.Availabilities[availabilityIndex].PhysicianName,
                        "price": data.Availabilities[availabilityIndex].Price,
                        "description": data.Availabilities[availabilityIndex].Description,
                        "picture_url": data.Availabilities[availabilityIndex].PictureURL,
                        };
                    physiciansData.push(newPhysicianItem);
                    
                    if (firstFoundPhysician == "")
                        firstFoundPhysician = newPhysicianItem.id;

                    if (typeof(data.Availabilities[availabilityIndex].RequestedDateAvailabilities) != "undefined")
                    {
                        for (var index in data.Availabilities[availabilityIndex].RequestedDateAvailabilities)
                        {
                            if ((parseInt(index) < parseInt(setrio_bizcal_ajax.max_availabilities)) || (parseInt(setrio_bizcal_ajax.max_availabilities) == 0))
                            {
                                var newTimeItem = {
                                    "physician_uid": data.Availabilities[availabilityIndex].PhysicianUID,
                                    "start_date": data.Availabilities[availabilityIndex].RequestedDateAvailabilities[index].StartDate,
                                    "end_date": data.Availabilities[availabilityIndex].RequestedDateAvailabilities[index].EndDate,
                                    "id": data.Availabilities[availabilityIndex].PhysicianUID,
                                    "text": data.Availabilities[availabilityIndex].PhysicianName,
                                    "price": data.Availabilities[availabilityIndex].Price,
                                    "description": data.Availabilities[availabilityIndex].Description,
                                    "picture_url": data.Availabilities[availabilityIndex].PictureURL
                                    };
                                requestedDateAvailabilities.push(newTimeItem);
                            }
                        }
                    }

                    if (typeof(data.Availabilities[availabilityIndex].RecommandedDateAvailabilities) != "undefined")
                    {
                        for (var index in data.Availabilities[availabilityIndex].RecommandedDateAvailabilities)
                        {
                            if ((parseInt(index) < parseInt(setrio_bizcal_ajax.max_availabilities)) || (parseInt(setrio_bizcal_ajax.max_availabilities) == 0))
                            {
                                var newTimeItem = {
                                    "physician_uid": data.Availabilities[availabilityIndex].PhysicianUID,
                                    "start_date": data.Availabilities[availabilityIndex].RecommandedDateAvailabilities[index].StartDate,
                                    "end_date": data.Availabilities[availabilityIndex].RecommandedDateAvailabilities[index].EndDate,
                                    "id": data.Availabilities[availabilityIndex].PhysicianUID,
                                    "text": data.Availabilities[availabilityIndex].PhysicianName,
                                    "price": data.Availabilities[availabilityIndex].Price,
                                    "description": data.Availabilities[availabilityIndex].Description,
                                    "picture_url": data.Availabilities[availabilityIndex].PictureURL
                                    };
                                recommendedDateAvailabilities.push(newTimeItem);
                            }
                        }
                    }
                }
                
                var shownPhysician = "";
                if (requestedDateAvailabilities.length > 0)
                {
                    var hours = getPhysicianAvailabilitiesText(requestedDateAvailabilities);

                    jQuery("#bizcal-sel-time").html(hours);
                    jQuery("#bizcal-sel-time").css("display", "");
                    jQuery("#bizcal-sel-time").selectable({filter: "li.ui-button",
					selected: function(event, ui){jQuery(ui.selected).addClass('ui-state-active');},unselected: function(event, ui){jQuery(ui.unselected).removeClass('ui-state-active');},});
                }
                else if (recommendedDateAvailabilities.length > 0)
                {
                    var hours = getPhysicianAvailabilitiesText(recommendedDateAvailabilities);
                    var recommendedDate = "";
                    for (var index in recommendedDateAvailabilities)
                    {
                        if (recommendedDate.length == 0)
                            recommendedDate = String(recommendedDateAvailabilities[index].start_date).substring(0,8)
                    }
                    
                    realRecommendedDate = new Date(parseInt(recommendedDate.substring(0,4)),
                                                   parseInt(recommendedDate.substring(4,6)) - 1,
                                                   parseInt(recommendedDate.substring(6,8)));
                    formattedRecommendedDate = recommendedDate.substring(6,8) + "." + recommendedDate.substring(4,6) + "." + recommendedDate.substring(0,4);

                    console.log("AVAILABILITES:", hours);

                    showYesNoMessage(setrio_bizcal_ajax.msg_warn_no_available_appointments.formatBizStr({data: formattedRecommendedDate}),
                                     setrio_bizcal_ajax.msg_warning);
                    
                    jQuery("#bizcal-sel-time").html(hours);
                    jQuery("#bizcal-sel-time").css("display", "");
                    jQuery("#bizcal-sel-time").selectable({filter: "li.ui-button",selected: function(event, ui){jQuery(ui.selected).addClass('ui-state-active');},unselected: function(event, ui){jQuery(ui.unselected).removeClass('ui-state-active');},});
                }
                else
                {
                    canRegisterAppointment = false;
                    showErrorMessage(setrio_bizcal_ajax.msg_err_no_available_appointments, setrio_bizcal_ajax.msg_warning);
                    resetAvailability(true);
                }

                /*jQuery(".bizcal-sel-med").html("");
                jQuery(".bizcal-sel-med").select2({
					theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
                    data: physiciansData,
                    placeholder: setrio_bizcal_ajax.msg_physician_placeholder,
                    minimumResultsForSearch: bizcalIsMobileVersion ? -1 : 0,
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    templateResult: function(result) {
                        if (result['price'] === undefined) {
                            return result.text;
                        }
                        if (setrio_bizcal_ajax.show_physician_details)
                        {
                            return "<div style='width: 100%; display: inline-table'>"
                                + "<div style='width:100%; display: table-row'>"
                                    + "<div style='display: table-cell; width: 100px; vertical-align: top; padding: 2px'>"
                                    + "<img class='setrio-bizcal-picture-preview' src='" + result.picture_url + "' width='100' height='100' "
                                        + " style='max-height: 108px; width: auto; padding-right: 8px' align='top'>"
                                    + "</div>"
                                    + "<div style='display: block; width: 100%; overflow: hidden; white-space: pre-line; max-height: 100px; margin-top: 0px;'>"
                                    + result.text
                                    + "<br/><div class='bizcal-sel-med-small'>" + result.description + "</div>"
                                    + "</div>"
                                    + "<div style='display: table-cell; white-space: nowrap; vertical-align: middle;' class='bizcal-sel-med-selected-price'>"
                                    + result['price']
                                    + "</div></div></div>";                            
                        }
                        else
                        {
                            return "<div style='width: 100%; display: inline-table'><div style='width:100%; display: table-row'><div style='display: table-cell; width: 100%;'>" + result.text
                                + "</div><div style='display: table-cell; white-space: nowrap' class='price'>"
                                + result['price']
                                + "</div></div></div>";
                        }
                    },
                    templateSelection: function(result) {
                        if (result['price'] === undefined) {
                            return result.text;
                        }
                        return "<div style='width: 100%; display: inline-table'><div style='width:100%; display: table-row'>"
                            + "<div style='display: block; width: 100%; overflow: hidden; white-space: pre-line; max-height: 34px;'>" + result.text
                            + "</div><div style='display: table-cell; white-space: nowrap' class='bizcal-sel-med-selected-price'>"
                            + result['price']
                            + "</div></div></div>";                            
                    }
                });
                
                var physicialManualSelectEnabled = setrio_bizcal_ajax.allow_search_physician;
                if (physicialManualSelectEnabled)
                {
                    console.log("First found physician:" + firstFoundPhysician);
                    var itemService = jQuery('.bizcal-sel-med');
                    if (itemService.length > 0)
                        jQuery(".bizcal-sel-med").val(firstFoundPhysician).trigger('change');
                    jQuery("#bizcal-sel-med-label").css("display", "none");
                    jQuery("#bizcal-ra-physician-uid-auto").val(firstFoundPhysician);
                }
                else
                    jQuery("#bizcal-sel-med-label").css("display", "");*/
                
                //doOnSelectPhysician(null);
              
                if (setrio_bizcal_ajax.autosel_physician)
                {
                    jQuery("#bizcal-ra-physician-uid-auto").val(physician_uid);
                }
            }
            else
            {
                canRegisterAppointment = false;
                showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
            }
            
            if (canRegisterAppointment)
                stopLoadingAvailability();
            else
                resetAvailability(true);
            
            if ((canRegisterAppointment) && (isFormWithPopups()))
            {
                dialogSelTime = jQuery("#bizcal-select-time-form").dialog({
                    autoOpen: false,
                    height: "500",
                    width: "95%",
                    modal: true,
                    buttons: {
                        "Continuă": function() {
                            dialogSelTime.dialog("close");
                            registerAppointment();
                        },
                        "Renunță": function() {
                            dialogSelTime.dialog("close");
                        }
                    },
                });
     
                dialogSelTime.dialog("open");
            }
            
            bizcalReqGetAppointmentAvailabilities = null;
        })
        .fail(function(req){
            if ((req.status !== 0) || (req.statusText !== "abort"))
            {
                showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
                resetAvailability(false);
            }
        });
}

function getPhysicianAvailabilitiesText(data)
{
    var shownPhysician = "";
    var firstItemSelected = false;
    var result = "";
    for (var index in data)
    {
        if (shownPhysician != String(data[index].physician_uid))
        {
            if (setrio_bizcal_ajax.show_physician_details)
            {
                result += "<li class='setrio-bizcal-available-physician'>"
                    + "<div class='setrio-bizcal-available-physician-picture-container'><img class='setrio-bizcal-available-physician-picture' src='"
                    + (String(data[index].picture_url).length > 0
                      ? data[index].picture_url
                      : setrio_bizcal_ajax.plugins_url + "/css/images/physician-icon.png")
                    + "' width='100' height='100' align='top'></div>"
                    + "<div class='setrio-bizcal-available-physician-name'>" + data[index].text + "</div>"
                    + ((typeof(data[index].price) != "undefined") 
                      ? "<div class='setrio-bizcal-available-physician-price'><span class='label'>Tarif: </span>" + data[index].price + "</div>"
                      : ((getSelectedPaymentTypeID() == 2)
						 ? "<div class='setrio-bizcal-available-physician-price'><span class='label'>Tarif: </span>DECONTAT CNAS</div>"
						 : ""))
                    + "<div class='setrio-bizcal-available-physician-description'>" + data[index].description + "</div>"
                    + "</li>";
            }
            else
            {
                result += "<li class='setrio-bizcal-available-physician'>"
                    + "<div class='setrio-bizcal-available-physician-name'>" + data[index].text + "</div>"
				    + ((typeof(data[index].price) != "undefined")
                      ? "<div class='setrio-bizcal-available-physician-price'><span class='label'>Tarif: </span>" + data[index].price + "</div>"
					  : ((getSelectedPaymentTypeID() == 2)
						 ? "<div class='setrio-bizcal-available-physician-price'><span class='label'>Tarif: </span>DECONTAT CNAS</div>"
						 : ""))
                    + "</li>";
            }
            
            shownPhysician = String(data[index].physician_uid);
        }
        
        result = result + "<li class=\"ui-button" + (firstItemSelected ? "" : " ui-selected ui-state-active") 
            + " bizcal-sel-time-ph-" + String(data[index].physician_uid) + "\""
            + " data-physician-uid=\"" + String(data[index].physician_uid) + "\""
            + " data-physician-name=\"" + String(data[index].text) + "\""
            + " data-physician-price=\"" + String(data[index].price) + "\">"
            + String(data[index].start_date).substring(9,11)
            + ":" + String(data[index].start_date).substring(12,14) + " - "
            + String(data[index].end_date).substring(9,11)
            + ":" + String(data[index].end_date).substring(12,14) + "</li>";
        firstItemSelected = true;
    }
    
    return result;
}

function getAppointmentAvailabilities(context)
{
    if (context.state.selectedSpeciality == null)
    {
        return;
    }

    if (setrio_bizcal_ajax.enable_multiple_locations)
    {
        if (context.state.selectedLocation == null)
        {
            return;
        }
    }

    if (context.state.selectedPaymentType == null)
    {
        return;
    }

    if (context.state.selectedService == null)
    {
        return;
    }

    context.commit('startLoadingAppointmentAvailabilities');

    const params = new URLSearchParams();
    params.append('_ajax_nonce', setrio_bizcal_ajax.nonce);
    params.append('action', 'get_availability');
    params.append('speciality_code', context.state.selectedSpeciality.value);
    if (setrio_bizcal_ajax.enable_multiple_locations)
        params.append('location_uid', context.state.selectedLocation != null ? context.state.selectedLocation.value : null);
    params.append('payment_type_id', context.state.selectedPaymentType.value);
    params.append('service_uid', context.state.selectedService.value);
    params.append('physician_uid', context.state.selectedPreferredPhysician != null ? context.state.selectedPreferredPhysician.value : null);
    params.append('desired_date', context.state.selectedAppointmentDate);

    const config = {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    };

    axios.post(setrio_bizcal_ajax.ajax_url, params, config)
        .then((response) => {
            if (response.status = 200)
            { 
                var physician_uid = 0;
                var requestedDateAvailabilities = [];
                var recommendedDateAvailabilities = [];
                var canRegisterAppointment = true;
                var firstItemSelected = false;
                data = JSON.parse(data);

                if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
                {
                    physician_uid = data.PhysicianUID;
                    selected_physician_uid = "";
                    
                    var physiciansData = [];
                    var firstFoundPhysician = "";
                    
                    for (var availabilityIndex in data.Availabilities)
                    {
                        if (!setrio_bizcal_ajax.autosel_physician)
                        {
                            newPhysicianItem = {
                                id: "",
                                text: "",
                                price: "",
                                description: "",
                                picture_url: ""
                                };
                            physiciansData.push(newPhysicianItem);
                        }
                        
                        var newPhysicianItem = {
                            id: data.Availabilities[availabilityIndex].PhysicianUID,
                            text: data.Availabilities[availabilityIndex].PhysicianName,
                            price: data.Availabilities[availabilityIndex].Price,
                            description: data.Availabilities[availabilityIndex].Description,
                            picture_url: data.Availabilities[availabilityIndex].PictureURL,
                            };
                        physiciansData.push(newPhysicianItem);
                        
                        if (firstFoundPhysician == "")
                            firstFoundPhysician = newPhysicianItem.id;

                        if (typeof(data.Availabilities[availabilityIndex].RequestedDateAvailabilities) != "undefined")
                        {
                            for (var index in data.Availabilities[availabilityIndex].RequestedDateAvailabilities)
                            {
                                if ((parseInt(index) < parseInt(setrio_bizcal_ajax.max_availabilities)) || (parseInt(setrio_bizcal_ajax.max_availabilities) == 0))
                                {
                                    var newTimeItem = {
                                        physician_uid: data.Availabilities[availabilityIndex].PhysicianUID,
                                        start_date: data.Availabilities[availabilityIndex].RequestedDateAvailabilities[index].StartDate,
                                        end_date: data.Availabilities[availabilityIndex].RequestedDateAvailabilities[index].EndDate,
                                        id: data.Availabilities[availabilityIndex].PhysicianUID,
                                        text: data.Availabilities[availabilityIndex].PhysicianName,
                                        price: data.Availabilities[availabilityIndex].Price,
                                        description: data.Availabilities[availabilityIndex].Description,
                                        picture_url: data.Availabilities[availabilityIndex].PictureURL
                                        };
                                    requestedDateAvailabilities.push(newTimeItem);
                                }
                            }
                        }

                        if (typeof(data.Availabilities[availabilityIndex].RecommandedDateAvailabilities) != "undefined")
                        {
                            for (var index in data.Availabilities[availabilityIndex].RecommandedDateAvailabilities)
                            {
                                if ((parseInt(index) < parseInt(setrio_bizcal_ajax.max_availabilities)) || (parseInt(setrio_bizcal_ajax.max_availabilities) == 0))
                                {
                                    var newTimeItem = {
                                        physician_uid: data.Availabilities[availabilityIndex].PhysicianUID,
                                        start_date: data.Availabilities[availabilityIndex].RecommandedDateAvailabilities[index].StartDate,
                                        end_date: data.Availabilities[availabilityIndex].RecommandedDateAvailabilities[index].EndDate,
                                        id: data.Availabilities[availabilityIndex].PhysicianUID,
                                        text: data.Availabilities[availabilityIndex].PhysicianName,
                                        price: data.Availabilities[availabilityIndex].Price,
                                        description: data.Availabilities[availabilityIndex].Description,
                                        picture_url: data.Availabilities[availabilityIndex].PictureURL
                                        };
                                    recommendedDateAvailabilities.push(newTimeItem);
                                }
                            }
                        }
                    }
                    
                    var shownPhysician = "";
                    if (requestedDateAvailabilities.length > 0)
                    {
                        var hours = getPhysicianAvailabilitiesText(requestedDateAvailabilities);

                        jQuery("#bizcal-sel-time").html(hours);
                        jQuery("#bizcal-sel-time").css("display", "");
                        jQuery("#bizcal-sel-time").selectable({filter: "li.ui-button",selected: function(event, ui){jQuery(ui.selected).addClass('ui-state-active');},unselected: function(event, ui){jQuery(ui.unselected).removeClass('ui-state-active');},});
                    }
                    else if (recommendedDateAvailabilities.length > 0)
                    {
                        var hours = getPhysicianAvailabilitiesText(recommendedDateAvailabilities);
                        var recommendedDate = "";
                        for (var index in recommendedDateAvailabilities)
                        {
                            if (recommendedDate.length == 0)
                                recommendedDate = String(recommendedDateAvailabilities[index].start_date).substring(0,8)
                        }
                        
                        realRecommendedDate = new Date(parseInt(recommendedDate.substring(0,4)),
                                                    parseInt(recommendedDate.substring(4,6)) - 1,
                                                    parseInt(recommendedDate.substring(6,8)));
                        formattedRecommendedDate = recommendedDate.substring(6,8) + "." + recommendedDate.substring(4,6) + "." + recommendedDate.substring(0,4);

                        console.log("AVAILABILITES:", hours);

                        showYesNoMessage(setrio_bizcal_ajax.msg_warn_no_available_appointments.formatBizStr({data: formattedRecommendedDate}),
                                        setrio_bizcal_ajax.msg_warning);
                        
                        jQuery("#bizcal-sel-time").html(hours);
                        jQuery("#bizcal-sel-time").css("display", "");
                        jQuery("#bizcal-sel-time").selectable({filter: "li.ui-button",selected: function(event, ui){jQuery(ui.selected).addClass('ui-state-active');},unselected: function(event, ui){jQuery(ui.unselected).removeClass('ui-state-active');},});
                    }
                    else
                    {
                        canRegisterAppointment = false;
                        showErrorMessage(setrio_bizcal_ajax.msg_err_no_available_appointments, setrio_bizcal_ajax.msg_warning);
                        resetAvailability(true);
                    }

                    /*jQuery(".bizcal-sel-med").html("");
                    jQuery(".bizcal-sel-med").select2({
						theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
                        data: physiciansData,
                        placeholder: setrio_bizcal_ajax.msg_physician_placeholder,
                        minimumResultsForSearch: bizcalIsMobileVersion ? -1 : 0,
                        escapeMarkup: function(markup) {
                            return markup;
                        },
                        templateResult: function(result) {
                            if (result['price'] === undefined) {
                                return result.text;
                            }
                            if (setrio_bizcal_ajax.show_physician_details)
                            {
                                return "<div style='width: 100%; display: inline-table'>"
                                    + "<div style='width:100%; display: table-row'>"
                                        + "<div style='display: table-cell; width: 100px; vertical-align: top; padding: 2px'>"
                                        + "<img class='setrio-bizcal-picture-preview' src='" + result.picture_url + "' width='100' height='100' "
                                            + " style='max-height: 108px; width: auto; padding-right: 8px' align='top'>"
                                        + "</div>"
                                        + "<div style='display: block; width: 100%; overflow: hidden; white-space: pre-line; max-height: 100px; margin-top: 0px;'>"
                                        + result.text
                                        + "<br/><div class='bizcal-sel-med-small'>" + result.description + "</div>"
                                        + "</div>"
                                        + "<div style='display: table-cell; white-space: nowrap; vertical-align: middle;' class='bizcal-sel-med-selected-price'>"
                                        + result['price']
                                        + "</div></div></div>";                            
                            }
                            else
                            {
                                return "<div style='width: 100%; display: inline-table'><div style='width:100%; display: table-row'><div style='display: table-cell; width: 100%;'>" + result.text
                                    + "</div><div style='display: table-cell; white-space: nowrap' class='price'>"
                                    + result['price']
                                    + "</div></div></div>";
                            }
                        },
                        templateSelection: function(result) {
                            if (result['price'] === undefined) {
                                return result.text;
                            }
                            return "<div style='width: 100%; display: inline-table'><div style='width:100%; display: table-row'>"
                                + "<div style='display: block; width: 100%; overflow: hidden; white-space: pre-line; max-height: 34px;'>" + result.text
                                + "</div><div style='display: table-cell; white-space: nowrap' class='bizcal-sel-med-selected-price'>"
                                + result['price']
                                + "</div></div></div>";                            
                        }
                    });
                    
                    var physicialManualSelectEnabled = setrio_bizcal_ajax.allow_search_physician;
                    if (physicialManualSelectEnabled)
                    {
                        console.log("First found physician:" + firstFoundPhysician);
                        var itemService = jQuery('.bizcal-sel-med');
                        if (itemService.length > 0)
                            jQuery(".bizcal-sel-med").val(firstFoundPhysician).trigger('change');
                        jQuery("#bizcal-sel-med-label").css("display", "none");
                        jQuery("#bizcal-ra-physician-uid-auto").val(firstFoundPhysician);
                    }
                    else
                        jQuery("#bizcal-sel-med-label").css("display", "");*/
                    
                    //doOnSelectPhysician(null);
                
                    if (setrio_bizcal_ajax.autosel_physician)
                    {
                        jQuery("#bizcal-ra-physician-uid-auto").val(physician_uid);
                    }
                }
                else
                {
                    canRegisterAppointment = false;
                    showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
                }
                
                if (canRegisterAppointment)
                    stopLoadingAvailability();
                else
                    resetAvailability(true);
                
                if ((canRegisterAppointment) && (isFormWithPopups()))
                {
                    dialogSelTime = jQuery("#bizcal-select-time-form").dialog({
                        autoOpen: false,
                        height: "500",
                        width: "95%",
                        modal: true,
                        buttons: {
                            "Continuă": function() {
                                dialogSelTime.dialog("close");
                                registerAppointment();
                            },
                            "Renunță": function() {
                                dialogSelTime.dialog("close");
                            }
                        },
                    });
        
                    dialogSelTime.dialog("open");
                }
                
                bizcalReqGetAppointmentAvailabilities = null;
            }
        })
        .fail(function(req){
            if ((req.status !== 0) || (req.statusText !== "abort"))
            {
                showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
                resetAvailability(false);
            }
        });
}

function wsRegisterAppointment()
{
    if (bizcalReqRegisterAppointment != null)
    {
        showErrorMessage(setrio_bizcal_ajax.msg_err_request_in_progress, setrio_bizcal_ajax.msg_error);
        return;
    }
    
    var valid = true;
    bizcalRAAllFields.removeClass( "ui-state-error" );
    resetTips();
    
    bizcalRAReCaptcha = jQuery("div#bizcal-g-recaptcha textarea.g-recaptcha-response");
 
    valid = checkLength(bizcalRAPhysicianUID, setrio_bizcal_ajax.msg_fld_physician) && valid;
    valid = ((bizcalRAPaymentTypeID.val() != 2) ? checkLength(bizcalRAServiceUID, setrio_bizcal_ajax.msg_fld_service) : true) && valid;
    valid = checkLength(bizcalRAPaymentTypeID, setrio_bizcal_ajax.msg_fld_payment_type) && valid;
    valid = checkLength(bizcalRAStartDate, setrio_bizcal_ajax.msg_fld_start_time) && valid;
    valid = checkLength(bizcalRAEndDate, setrio_bizcal_ajax.msg_fld_end_time) && valid;
    valid = checkLength(bizcalRALastName, setrio_bizcal_ajax.msg_fld_last_name) && valid;
    valid = checkLength(bizcalRAFirstName, setrio_bizcal_ajax.msg_fld_first_name) && valid;
    valid = checkLength(bizcalRAPhone, setrio_bizcal_ajax.msg_fld_phone) && valid;
	valid = checkChecked(bizcalRATerms, setrio_bizcal_ajax.msg_fld_terms_not_agreed) && valid;
    valid = checkChecked(bizcalRADataPolicy, setrio_bizcal_ajax.msg_fld_data_policy_not_agreed) && valid;
    valid = (bizcalRAPhone.val().trim().length > 0 ? 
        checkRegexp(bizcalRAPhone, /^([0-9])+$/, setrio_bizcal_ajax.msg_fld_phone_not_valid) : true) && valid;
    valid = (bizcalRAEmail.val().trim().length > 0 ?
        checkRegexp(bizcalRAEmail, emailRegex, setrio_bizcal_ajax.msg_fld_email_not_valid) : true) && valid;
 
    if (bizcalRAReCaptcha.val().length == 0)
    {
        valid = false;
        showErrorMessage(setrio_bizcal_ajax.msg_fld_recaptcha_not_valid, setrio_bizcal_ajax.msg_error);
    }
 
    if ( valid ) {
        jQuery("#bizcal-register-appointment-button").prop("disabled", true);
        var physician = getSelectedPhysicianName();// jQuery(".bizcal-sel-med").select2("data");
		var price = getSelectedPhysicianServicePrice();
        //physician = physician[0].text;
        var service = "-";
        if (getSelectedMedicalServiceUID() != "")
        {
            service = jQuery(".bizcal-sel-serv").select2("data");
            service = service[0].text;
        }
        var speciality = jQuery(".bizcal-sel-spec").select2("data");
        speciality = speciality[0].text;
        var specialityCode = getSelectedMedicalSpecialityCode();
        
        selectedLocationUID = null;
        selectedLocationName = "-";
        if (setrio_bizcal_ajax.enable_multiple_locations)
        {
            selectedLocationUID = getSelectedLocationUID();
            selectedLocationName = getSelectedLocationName();
        }
        
        bizcalReqRegisterAppointment = jQuery.post({
            url: setrio_bizcal_ajax.ajax_url,
            data: {
                _ajax_nonce: setrio_bizcal_ajax.nonce,
                action: "register_appointment",
                physician_uid: bizcalRAPhysicianUID.val(),
                service_uid: bizcalRAServiceUID.val(),
                payment_type_id: bizcalRAPaymentTypeID.val(),
                start_date: bizcalRAStartDate.val(),
                end_date: bizcalRAEndDate.val(),
                last_name: bizcalRALastName.val(),
                first_name: bizcalRAFirstName.val(),
                phone: bizcalRAPhone.val(),
                email: bizcalRAEmail.val(),
                observations: bizcalRAObservations.val(),
                newsletter: bizcalRANewsletter.is(':checked') ? 1 : 0,
                data_policy: bizcalRADataPolicy.is(':checked') ? 1 : 0,
                terms: bizcalRATerms.is(':checked') ? 1 : 0,
                recaptcha: bizcalRAReCaptcha.val(),
                speciality_code: specialityCode,
                speciality_name: speciality,
                service_name: service,
                physician_name: physician,
                location_uid: selectedLocationUID,
                location_name: selectedLocationName,
				price: price
            },
            dataType: "json"
        })
        .done(function(data){
            var requestedDateAvailabilities = [];
            var recommendedDateAvailabilities = [];
            data = JSON.parse(data);

            if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
            {
                var formattedDate = bizcalRAStartDate.val();
                formattedDate = formattedDate.substring(6,8) + "." + formattedDate.substring(4,6) + "." + formattedDate.substring(0,4)
                    + ", ora " + formattedDate.substring(9,14).replace("-",":");
                var physicianUID = getSelectedPhysicianUID();
                if (physicianUID.length == 0)
                {
                    //jQuery(".bizcal-sel-med").val(bizcalRAPhysicianUID.val()).trigger('change');
                }
                var physician = getSelectedPhysicianName();// jQuery(".bizcal-sel-med").select2("data");
                //physician = physician[0].text;
                var speciality = jQuery(".bizcal-sel-spec").select2("data");
                speciality = speciality[0].text;
                
                jQuery("#bizcal-register-appointment-form").find("input[type=text], textarea").val("");
                jQuery("#bizcal-register-appointment-form").find("input[type=checkbox]").prop("checked", false);
                grecaptcha.reset();
				
				if(typeof data.redirect !== 'undefined'){
					
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
				
				showInfoMessage(data.message);

                /* if (!setrio_bizcal_ajax.enable_multiple_locations)
                {
                    showInfoMessage(setrio_bizcal_ajax.msg_confirm_appointment.formatBizStr({
                        data: formattedDate,
                        medic: physician,
                        specialitatea: speciality,
                        email: setrio_bizcal_ajax.clinic_email,
                        telefon: setrio_bizcal_ajax.clinic_phone
                        }), setrio_bizcal_ajax.msg_info);
                }
                else
                {
                    var location = getSelectedLocationName();
                    showInfoMessage(setrio_bizcal_ajax.msg_confirm_appointment_with_location.formatBizStr({
                        data: formattedDate,
                        medic: physician,
                        specialitatea: speciality,
                        email: setrio_bizcal_ajax.clinic_email,
                        telefon: setrio_bizcal_ajax.clinic_phone,
                        locatia: location
                        }), setrio_bizcal_ajax.msg_info);
                } */
                
                if (isFormWithPopups())
                    dialog.dialog("close");
                
                if (jQuery("#setrio-bizcal-popup-btn-next").length > 0)
                    jQuery("#setrio-bizcal-main-box-content").dialog("close");
            }
            else
            {
                showErrorMessage(data.ErrorMessage, setrio_bizcal_ajax.msg_error);
                grecaptcha.reset();
            }
            
            bizcalReqRegisterAppointment = null;
            jQuery("#bizcal-register-appointment-button").prop("disabled", false);
        })
        .fail(function(req){
            if ((req.status !== 0) || (req.statusText !== "abort"))
            {
                showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
                grecaptcha.reset();
            }
            bizcalReqRegisterAppointment = null;
            jQuery("#bizcal-register-appointment-button").prop("disabled", false);
        });
    }
    
    return valid;
}

function wsGetPriceForService(physician_uid, service_uid)
{
    if (bizcalReqGetPriceForService != null)
    {
        bizcalReqGetPriceForService.abort();
        bizcalReqGetPriceForService = null;
    }
    
    bizcalReqGetPriceForService = jQuery.post({
            url: setrio_bizcal_ajax.ajax_url,
            data: {
                _ajax_nonce: setrio_bizcal_ajax.nonce,
                action: "get_price_for_service",
                physician_uid: physician_uid,
                service_uid: service_uid
            },
            dataType: "json"
        })
        .done(function(data){
            var selectData = [];
            data = JSON.parse(data);

            if ((data.ErrorCode == 0) && (data.ErrorMessage == ""))
            {
                jQuery("#bizcal-sel-time-service").html(setrio_bizcal_ajax.msg_price + " " + data.Price);
                jQuery("#bizcal-sel-time-service").css("display", "");
                jQuery("#bizcal-sel-time-service").css("color", "");
            }
            else
            {
                jQuery("#bizcal-sel-time-service").html(data.ErrorMessage);
                jQuery("#bizcal-sel-time-service").css("display", "");
                jQuery("#bizcal-sel-time-service").css("color", "red");
            }
                       
            bizcalReqGetPriceForService = null;
        })
        .fail(function(req){
            if ((req.status !== 0) || (req.statusText !== "abort"))
            {
                showErrorMessage(setrio_bizcal_ajax.msg_service_unknown_error, setrio_bizcal_ajax.msg_error);
            }
        });
}

// Metode interfata

function getSelectedMedicalSpecialityCode()
{
    var medicalSpecialityControls = jQuery("#bizcal-sel-spec");
    if (medicalSpecialityControls.length > 0)
        return medicalSpecialityControls[0].value;
    else
        return "";
}

function getSelectedLocationUID()
{
    var locationControls = jQuery("#bizcal-sel-location");
    if (locationControls.length > 0)
        return locationControls[0].value;
    else
        return "";
}

function getSelectedLocationName()
{
    var locationName = "-";
    if (getSelectedLocationUID() != "")
    {
        locationName = jQuery(".bizcal-sel-location").select2("data");
        locationName = locationName[0].text;
    }
    
    return locationName;
}

function setSelectedMedicalSpecialityCode(code, disable_selection)
{
    var medicalSpecialityControls = jQuery("#bizcal-sel-spec");
    if (medicalSpecialityControls.length > 0)
    {
        if (jQuery('#bizcal-sel-spec').find("option[value='" + code + "']").length)
        {
            jQuery('#bizcal-sel-spec').val(code).trigger('change');
            if (disable_selection)
                jQuery('#bizcal-sel-spec').prop("disabled", true);
            doOnSelectMedicalSpeciality();
        }
        else
        {
            jQuery('#bizcal-sel-spec').val(null).trigger('change');
            jQuery('#bizcal-sel-spec').prop("disabled", false);;
        }
    }
}

function setSelectedLocationName(code, disable_selection)
{
    var locationControls = jQuery("#bizcal-sel-location");
	let safe_code = setrioBizcalSafeText(code);
    if (locationControls.length > 0)
    {
		var locationControlOptions = locationControls.find("option").filter(function(index){return safe_code == this.value});
        if (locationControlOptions.length)
        {
			// TODO
            locationControls.val(locationControlOptions[0].value).trigger('change');
            if (disable_selection)
                locationControls.prop("disabled", true);
        }
        else
        {
            locationControls.val(null).trigger('change');
            locationControls.prop("disabled", false);;
        }
    }
}

function getSelectedPaymentTypeID()
{
    var paymentTypeControls = jQuery("#bizcal-sel-payment");
    if (paymentTypeControls.length > 0)
        return paymentTypeControls[0].value;
    else
        return 0;
}

function getSelectedPhysicianUID()
{
    var physicianControls = jQuery(".bizcal-sel-time");
    if (physicianControls.length > 0)
    {
        var selectedPhysicianUID = jQuery("#bizcal-sel-time li.ui-selected").data("physicianUid");
        //var selectedPhysicianUID = physicianControls[0].value;
        if ((selectedPhysicianUID == "-") || (selectedPhysicianUID == "0") || (selectedPhysicianUID == " "))
            selectedPhysicianUID = "";
        if (typeof(selectedPhysicianUID) == "undefined")
            selectedPhysicianUID = "";
        return selectedPhysicianUID;
    }
    else
        return "";
}

function getSelectedPhysicianName()
{
    var physicianControls = jQuery(".bizcal-sel-time");
    if (physicianControls.length > 0)
    {
        var selectedPhysicianName = jQuery("#bizcal-sel-time li.ui-selected").data("physicianName");
        //var selectedPhysicianUID = physicianControls[0].value;
        if ((selectedPhysicianName == "-") || (selectedPhysicianName == " "))
            selectedPhysicianName = "";
        return selectedPhysicianName;
    }
    else
        return "";
}

function getSelectedServicePrice()
{
    var physicianControls = jQuery(".bizcal-sel-time");
    if (physicianControls.length > 0)
    {
        var selectedPhysicianPrice = jQuery("#bizcal-sel-time li.ui-selected").data("physicianPrice");
        if ((selectedPhysicianPrice == "-") || (selectedPhysicianPrice == " "))
            selectedPhysicianPrice = "";
        return selectedPhysicianPrice;
    }
    else
        return "";
}

function getPreferredPhysicianUID()
{
    var physicianControls = jQuery(".bizcal-sel-preferred-physician");
    var physicialManualSelectEnabled = setrio_bizcal_ajax.allow_search_physician;
    if ((physicianControls.length > 0) && (physicialManualSelectEnabled))
    {
        var selectedPhysicianUID = physicianControls[0].value;
        if ((selectedPhysicianUID == "-") || (selectedPhysicianUID == "0") || (selectedPhysicianUID == " "))
            selectedPhysicianUID = "";
        return selectedPhysicianUID;
    }
    else
        return "";
}

function getPreferredPhysicianName()
{
    var physicianControls = jQuery(".bizcal-sel-preferred-physician");
    var physicialManualSelectEnabled = setrio_bizcal_ajax.allow_search_physician;

    if ((physicianControls.length > 0) && (physicialManualSelectEnabled))
    {
        if (jQuery('.bizcal-sel-preferred-physician').find("option").length)
        {
            var selectedPhysicianName = jQuery(".bizcal-sel-preferred-physician").select2('data')[0].text;
            if ((selectedPhysicianName == "-") || (selectedPhysicianName == " "))
                selectedPhysicianName = "";
            return selectedPhysicianName;
        }
        else
            return "";
    }
    else
        return "";
}

function setPreferredPhysicianName(name, disable_selection)
{
    var physicianControls = jQuery(".bizcal-sel-preferred-physician");
    var physicialManualSelectEnabled = setrio_bizcal_ajax.allow_search_physician;
    if ((physicianControls.length > 0) && (physicialManualSelectEnabled))
    {
        console.log("SETTING PREFERRED PHYSICIAN", name);
        //console.log(jQuery('.bizcal-sel-preferred-physician').find("option[text='" + name + "']"));
        if (jQuery('.bizcal-sel-preferred-physician').find("option[text='" + name + "']").length)
        {
            console.log("TEST222");
            var desiredUID = jQuery('.bizcal-sel-preferred-physician').find("option[text='" + name + "']")[0].val();
            jQuery('.bizcal-sel-preferred-physician').val(desiredUID).trigger('change');
            if (disable_selection)
                jQuery('.bizcal-sel-preferred-physician').prop("disabled", true);
            doOnSelectPreferredPhysician();
        }
        else
        {
            console.log("TEST333");
            jQuery('.bizcal-sel-preferred-physician').val(null).trigger('change');
            jQuery('.bizcal-sel-preferred-physician').prop("disabled", false);
        }

        var selectedPhysicianUID = physicianControls[0].value;
        if ((selectedPhysicianUID == "-") || (selectedPhysicianUID == "0") || (selectedPhysicianUID == " "))
            selectedPhysicianUID = "";
        return selectedPhysicianUID;
    }
}

function getSelectedPhysicianServicePrice()
{
    var physicianControls = jQuery(".bizcal-sel-time");
    if (physicianControls.length > 0)
    {
        var selectedPhysicianPrice = jQuery("#bizcal-sel-time li.ui-selected").data("physicianPrice");
        //var selectedPhysicianUID = physicianControls[0].value;
        if ((selectedPhysicianPrice == "-") || (selectedPhysicianPrice == " "))
            selectedPhysicianPrice = "";
        return selectedPhysicianPrice;
    }
    else
        return "";
    
    /*var physicianControls = jQuery("#bizcal-sel-time-container").find(".bizcal-sel-med-selected-price");
    if (physicianControls.length > 0)
    {
        var selectedPhysicianServicePrice = physicianControls[0].innerText;
        return selectedPhysicianServicePrice;
    }
    else
        return "";*/
}

function getSelectedOrAutoAllocatedPhysicianUID()
{
    physicianUID = getSelectedPhysicianUID();
    if (physicianUID == "")
    {
        var physicianAutoAllocatedControls = jQuery("#bizcal-ra-physician-uid-auto");
        if (physicianAutoAllocatedControls.length > 0)
        {
            physicianUID = physicianAutoAllocatedControls[0].value;
            if ((physicianUID == "-") || (physicianUID == "0"))
                physicianUID = "";
        }
    }
    return physicianUID;
}

function getSelectedMedicalServiceUID()
{
    var medicalServiceControlsBox = jQuery(".bizcal-sel-serv-box");
    var medicalServiceControls = jQuery(".bizcal-sel-serv");
    if (medicalServiceControlsBox.length > 0)
    {
        var medicalServiceControlsStatus = medicalServiceControlsBox.css("display");
        if (medicalServiceControlsStatus == "none")
            return "";
        else
        {
            var selectedMedicalServiceUID = medicalServiceControls[0].value;
            if ((selectedMedicalServiceUID == "-") || (selectedMedicalServiceUID == "0"))
                selectedMedicalServiceUID = "";
            return selectedMedicalServiceUID;
        }
    }
    else
        return "";
}

function getSelectedMedicalServiceName()
{
    var medicalServiceControls = jQuery("#bizcal-sel-serv");
    if (medicalServiceControls.length > 0)
    {
        if (jQuery('#bizcal-sel-serv').find("option").length)
        {
            var selectedServiceName = jQuery("#bizcal-sel-serv").select2('data')[0].text;
            if ((selectedServiceName == "-") || (selectedServiceName == " "))
                selectedServiceName = "";
            return selectedServiceName;
        }
        else
            return "";
    }
    else
        return "";
}

function setSelectedMedicalServiceName(name, disable_selection)
{
    var serviceControls = jQuery("#bizcal-sel-serv");
    if (serviceControls.length > 0)
    {
        if (jQuery('#bizcal-sel-serv').find("option[text='" + name + "']").length)
        {
            var desiredUID = jQuery('#bizcal-sel-serv').find("option[text='" + name + "']")[0].val();
            jQuery('#bizcal-sel-serv').val(desiredUID).trigger('change');
            if (disable_selection)
                jQuery('#bizcal-sel-serv').prop("disabled", true);
            doOnSelectMedicalService();
        }
        else
        {
            jQuery('#bizcal-sel-serv').val(null).trigger('change');
            jQuery('#bizcal-sel-serv').prop("disabled", false);
        }

        var selectedMedicalServiceName = serviceControls[0].value;
        if ((selectedMedicalServiceName == "-") || (selectedMedicalServiceName == "0") || (selectedMedicalServiceName == " "))
            selectedMedicalServiceName = "";
        return selectedMedicalServiceName;
    }
}

function getSelectedDate()
{
    var desiredDate = jQuery("#bizcal-sel-date").datepicker("getDate");

    if (desiredDate)
    {
        var desiredYear = desiredDate.getFullYear();
        var desiredMonth = desiredDate.getMonth() + 1;
        var desiredDay = desiredDate.getDate(); // nu e greseala
        var formattedDate = "" + desiredYear + (desiredMonth < 10 ? "0" : "") + desiredMonth + (desiredDay < 10 ? "0" : "") + desiredDay;
        return formattedDate;
    }
    else
        return null;
}

function hideLocationSelectionBox()
{
    jQuery(".bizcal-sel-location-box").css("display", "none");
    jQuery(".bizcal-sel-location").html("");
}

function showLocationSelectionBox()
{
    jQuery(".bizcal-sel-location-box").css("display", "");
    
    var specialityCode = getSelectedMedicalSpecialityCode();
    if (specialityCode != "")
        wsGetLocations(specialityCode);
}

function hideMedicalServicesSelectionBox()
{
    jQuery(".bizcal-sel-serv-box").css("display", "none");
    jQuery(".bizcal-sel-serv").html("");
}

function showMedicalServicesSelectionBox()
{
    jQuery(".bizcal-sel-serv-box").css("display", "");
    
    var specialityCode = getSelectedMedicalSpecialityCode();
    
    var physicianUID = "";
    //if ((bizcalDefaultPhysician != "") || (setrio_bizcal_ajax.appointment_param_order == 1))
    physicianUID = getPreferredPhysicianUID();

    console.log("SHOW SERVICES - PhysicianUID", physicianUID);
        
    if (physicianUID == "")
        wsGetMedicalServices(specialityCode)
    else
        wsGetMedicalServicesPriceList(specialityCode, physicianUID);
}

function hidePreferredPhysicianSelectionBox()
{
    jQuery(".bizcal-sel-preferred-physician-box").css("display", "none");
    jQuery(".bizcal-sel-preferred-physician").html("");
}

function showPreferredPhysicianSelectionBox()
{
    var initialPreferredPhysicianUID = getPreferredPhysicianUID();

    jQuery(".bizcal-sel-preferred-physician-box").css("display", "");
	
	setrioBizcalSelect2Destroy(".bizcal-sel-preferred-physician");
    
    jQuery(".bizcal-sel-preferred-physician").val(null).trigger("change");
    jQuery(".bizcal-sel-preferred-physician").attr("data-placeholder", "Se încarcă...");
    jQuery(".bizcal-sel-preferred-physician").select2({theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',});
	setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-preferred-physician"));
        
    var specialityCode = getSelectedMedicalSpecialityCode();
    var paymentType = getSelectedPaymentTypeID();
    wsGetPhysicians(specialityCode, paymentType, initialPreferredPhysicianUID);
}

function hideAvailabilityHoursSelectionBox()
{
    jQuery("#bizcal-sel-time-physician").css("display", "none");
    jQuery("#bizcal-sel-time-service").css("display", "none");
    jQuery("#bizcal-sel-time-label").css("display", "none");
    jQuery("#bizcal-sel-time").css("display", "none");
}

function showAvailabilityHoursSelectionBox()
{
    jQuery("#bizcal-sel-time-physician").css("display", "");
    jQuery("#bizcal-sel-time-service").css("display", "");
    jQuery("#bizcal-sel-time-label").css("display", "");
    jQuery("#bizcal-sel-time").css("display", "");
}

function showHideAvailabilityHoursSelectionBox()
{
    //var physicianUID = getSelectedPhysicianUID();
    //if (physicianUID == "")
    //    hideAvailabilityHoursSelectionBox();
    //else
        showAvailabilityHoursSelectionBox();
}

function resetAvailability(resetAutoSelectedPhysician)
{
    if (resetAutoSelectedPhysician === true)
    {
        jQuery("#bizcal-ra-physician-uid-auto").val("");
    }
    jQuery("#bizcal-sel-time").html("");
    jQuery("#bizcal-sel-time-container").css("display", "none");
    jQuery(".bizcal-sel-time-loading").css("display", "none");
    
    jQuery("#setrio-bizcal-popup-btn-next").prop('disabled', true);
    jQuery("#setrio-bizcal-popup-btn-next").addClass('ui-state-disabled');
}

function startLoadingAvailability()
{
    jQuery("#bizcal-sel-time-container").css("display", "none");
    jQuery(".bizcal-sel-time-loading").css("display", "block");
    
    jQuery("#setrio-bizcal-popup-btn-next").prop('disabled', true);
    jQuery("#setrio-bizcal-popup-btn-next").addClass('ui-state-disabled');
}

function stopLoadingAvailability()
{
    jQuery("#bizcal-sel-time-container").css("display", "block");
    jQuery(".bizcal-sel-time-loading").css("display", "none");
    showHideAvailabilityHoursSelectionBox();
    
    jQuery("#setrio-bizcal-popup-btn-next").prop('disabled', false);
    jQuery("#setrio-bizcal-popup-btn-next").removeClass('ui-state-disabled');
}

function doOnSelectMedicalSpeciality(e)
{
    hidePreferredPhysicianSelectionBox();
    
    if ((setrio_bizcal_ajax.enable_multiple_locations) && (!bizcalLocationsInitialized))
    {
		setrioBizcalSelect2Destroy(".bizcal-sel-location");
        jQuery(".bizcal-sel-location").val(null).trigger("change");
        jQuery(".bizcal-sel-location").attr("data-placeholder", "Se încarcă...");
        jQuery(".bizcal-sel-location").select2({theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',});
		setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-location"));
    }
    else
    {
		setrioBizcalSelect2Destroy(".bizcal-sel-serv");
        jQuery(".bizcal-sel-serv").val(null).trigger("change");
        jQuery(".bizcal-sel-serv").attr("data-placeholder", "Se încarcă...");
        jQuery(".bizcal-sel-serv").select2({theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',});
		setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-serv"));
    }
    
    jQuery(".bizcal-sel-payment").val(null).trigger("change");
    var specialityCode = getSelectedMedicalSpecialityCode();
    resetAvailability(true);

    if (specialityCode.length > 0)
    {
        if (setrio_bizcal_ajax.enable_multiple_locations)
        {
            showLocationSelectionBox();
            wsGetLocations(specialityCode);
        }
        else
        {   
            hideLocationSelectionBox();
            wsGetAllowedPaymentTypes(specialityCode);
        }
    }
    else
    {
        hideLocationSelectionBox();
    }
}

function doOnSelectLocation(e)
{
    hidePreferredPhysicianSelectionBox();
    setrioBizcalSelect2Destroy(".bizcal-sel-serv");
    jQuery(".bizcal-sel-serv").val(null).trigger("change");
    jQuery(".bizcal-sel-serv").attr("data-placeholder", "Se încarcă...");
    jQuery(".bizcal-sel-serv").select2({theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',});
	setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-serv"));
    
    jQuery(".bizcal-sel-payment").val(null).trigger("change");
    var specialityCode = getSelectedMedicalSpecialityCode();
    resetAvailability(true);
    wsGetAllowedPaymentTypes(specialityCode);
}

function doOnSelectPaymentType(e)
{
    var speciality_code = getSelectedMedicalSpecialityCode();
    var paymentTypeId = getSelectedPaymentTypeID();
    
    resetAvailability(true);
	setrioBizcalSelect2Destroy(".bizcal-sel-serv");
    jQuery(".bizcal-sel-serv").val(null).trigger("change");
    jQuery(".bizcal-sel-serv").attr("data-placeholder", "Se încarcă...");
    jQuery(".bizcal-sel-serv").select2({theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',});
	setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-serv"));
    
    if (paymentTypeId == 0)
    {
        hideMedicalServicesSelectionBox();
        hidePreferredPhysicianSelectionBox();        
    }
    else if (paymentTypeId == 2)
    {
        bizcalMedicalServicesInitialized = true;
        hideMedicalServicesSelectionBox();
        if (setrio_bizcal_ajax.allow_search_physician == true)
            showPreferredPhysicianSelectionBox();
        else if ( (!isFormWithPopups()) && (!bizcalDisableAutoRefreshAppointmentAvailability) )
            checkAvailability(true);
    }
    else
    {
        if ((setrio_bizcal_ajax.appointment_param_order == 0) && (bizcalDefaultPhysician == ""))
        {
            showMedicalServicesSelectionBox();
            if (setrio_bizcal_ajax.allow_search_physician == true)
                showPreferredPhysicianSelectionBox();
        }
        else
        {
            if (setrio_bizcal_ajax.allow_search_physician == true)
                showPreferredPhysicianSelectionBox();
            
            showMedicalServicesSelectionBox();
        }
    }
}

function doOnSelectPhysician(e)
{
    /*var physicianUID = getSelectedPhysicianUID();

    jQuery("#bizcal-sel-time li").removeClass("ui-selected");
    jQuery("#bizcal-sel-time li").css("display", "none");
    jQuery("#bizcal-sel-time li.bizcal-sel-time-ph-" + physicianUID).css("display", "");
    jQuery("#bizcal-sel-time li.bizcal-sel-time-ph-" + physicianUID + ":first").addClass("ui-selected");
    if ((getSelectedMedicalServiceUID() != "") && (physicianUID != ""))
    {
        jQuery("#bizcal-sel-time-physician").html(setrio_bizcal_ajax.msg_physician_price + " <b>" + getSelectedPhysicianServicePrice() + "</b>");
        jQuery("#bizcal-sel-time-physician").css("display", "");
    }
    else
        jQuery("#bizcal-sel-time-physician").css("display", "none");
    showHideAvailabilityHoursSelectionBox();*/

    /*
    resetAvailability(true);
    
    var specialityCode = getSelectedMedicalSpecialityCode();
    var physicianUID = getSelectedPhysicianUID();
    var paymentTypeId = getSelectedPaymentTypeID();
    
    if (paymentTypeId != 2)
    {
        if (physicianUID == "")
            wsGetMedicalServices(specialityCode)
        else
            wsGetMedicalServicesPriceList(specialityCode, physicianUID);
    }
    else
    {
        if ( (!isFormWithPopups()) && (!bizcalDisableAutoRefreshAppointmentAvailability) )
            checkAvailability(true);
    }
    */
}

function doOnSelectMedicalService(e)
{
    resetAvailability(false);
    
    var physicialManualSelectEnabled = setrio_bizcal_ajax.allow_search_physician;
    if ((physicialManualSelectEnabled) && (setrio_bizcal_ajax.appointment_param_order == 0) && (bizcalDefaultPhysician == ""))
    {
        var initialPreferredPhysicianUID = getPreferredPhysicianUID();
		setrioBizcalSelect2Destroy(".bizcal-sel-preferred-physician");
        jQuery(".bizcal-sel-preferred-physician").val(null).trigger("change");
        jQuery(".bizcal-sel-preferred-physician").attr("data-placeholder", "Se încarcă...");
        jQuery(".bizcal-sel-preferred-physician").select2({theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',});
		setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-preferred-physician"));
        
        var specialityCode = getSelectedMedicalSpecialityCode();
        var paymentType = getSelectedPaymentTypeID();
        showPreferredPhysicianSelectionBox();
        wsGetPhysicians(specialityCode, paymentType, initialPreferredPhysicianUID);
    }
    else if ( (!isFormWithPopups()) && (!bizcalDisableAutoRefreshAppointmentAvailability) )
        checkAvailability(true);
}

function doOnSelectPreferredPhysician(e)
{
    resetAvailability(false);

    paymentTypeID = getSelectedPaymentTypeID();

    if ((setrio_bizcal_ajax.appointment_param_order == 0) && (bizcalDefaultPhysician == ""))
    {
        if ((paymentTypeID != 0) && (paymentTypeID != 2))
            showMedicalServicesSelectionBox();
        if ( (!isFormWithPopups()) && (!bizcalDisableAutoRefreshAppointmentAvailability) )
            checkAvailability(true);
    }
    else
    {
        if ((paymentTypeID != 0) && (paymentTypeID != 2))
            showMedicalServicesSelectionBox();
    }
}

function checkAvailability(auto)
{
    if ((!bizcalMedicalSpecialitiesInitialized)
        || ((!bizcalLocationsInitialized) && (setrio_bizcal_ajax.enable_multiple_locations))
        || (!bizcalMedicalServicesInitialized)
        || (!bizcalPaymentTypesInitialized))// || (!bizcalPhysiciansInitialized))
        return;
    
    var specialityCode = getSelectedMedicalSpecialityCode();
    var medicalServiceUID = getSelectedMedicalServiceUID();
    var physicianUID = getPreferredPhysicianUID();// null;//getSelectedPhysicianUID();
    var paymentTypeID = getSelectedPaymentTypeID();

    if (specialityCode == "")
    {
        if (!auto)
            showErrorMessage("Nu ați selectat specialitatea medicală dorită!", "Eroare");
        return;
    }
    if (paymentTypeID == "")
    {
        if (!auto)
            showErrorMessage("Nu ați selectat modalitatea de plată dorită!", "Eroare");
        return;
    }
    if ((medicalServiceUID == "") && (paymentTypeID != 2))
    {
        if (!auto)
            showErrorMessage("Nu ați selectat serviciul medical dorit!", "Eroare");
        return;
    }

    var selectedDate = getSelectedDate();
    if (selectedDate != null)
    {
        wsGetAppointmentAvailabilities(specialityCode, medicalServiceUID, physicianUID, paymentTypeID, selectedDate);
    }
}

function registerAppointment()
{
    var specialityCode = getSelectedMedicalSpecialityCode();
    var medicalServiceUID = getSelectedMedicalServiceUID();
    var paymentTypeID = getSelectedPaymentTypeID();
    var physicianUID = getSelectedPhysicianUID();

    if (physicianUID != "")
    {
        var selectedDate = getSelectedDate();
        if ( (selectedDate != null) && (jQuery("#bizcal-sel-time li.ui-selected").length > 0) )
        {
            var desiredStartTime = jQuery("#bizcal-sel-time li.ui-selected")[0].innerHTML.substring(0,5).replace(":", "-");
            var desiredEndTime = jQuery("#bizcal-sel-time li.ui-selected")[0].innerHTML.substring(8,13).replace(":", "-");
            
            desiredStartTime = selectedDate + "T" + desiredStartTime;
            desiredEndTime = selectedDate + "T" + desiredEndTime;
            
            emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
            bizcalRAPhysicianUID = jQuery("#bizcal-ra-physician-uid");
            bizcalRAServiceUID = jQuery("#bizcal-ra-service-uid");
            bizcalRAPaymentTypeID = jQuery("#bizcal-ra-payment-type-id");
            bizcalRAStartDate = jQuery("#bizcal-ra-start-date");
            bizcalRAEndDate = jQuery("#bizcal-ra-end-date");
            bizcalRAFirstName = jQuery("#bizcal-ra-first-name");
            bizcalRALastName = jQuery("#bizcal-ra-last-name");
            bizcalRAPhone = jQuery("#bizcal-ra-phone");
            bizcalRANewsletter = jQuery("#bizcal-ra-newsletter");
            bizcalRATerms = jQuery("#bizcal-ra-terms");
            bizcalRADataPolicy = jQuery("#bizcal-ra-data-policy");
            bizcalRAEmail = jQuery("#bizcal-ra-email");
            bizcalRAObservations = jQuery("#bizcal-ra-observations");
            bizcalRAAllFields = jQuery([]).add(bizcalRAFirstName).add(bizcalRALastName).add(bizcalRAPhone).add(bizcalRAEmail).add(bizcalRAObservations).add(bizcalRATerms).add(bizcalRADataPolicy);
            bizcalRATips = jQuery("#bizcal-register-appointment-form .bizcal-register-appointment-form-validate-tips");
            
            bizcalRAPhysicianUID.val(physicianUID);
            bizcalRAServiceUID.val(medicalServiceUID);
            bizcalRAPaymentTypeID.val(paymentTypeID);
            bizcalRAStartDate.val(desiredStartTime);
            bizcalRAEndDate.val(desiredEndTime);
            
            if (isFormWithPopups())
            {
                dialog = jQuery("#bizcal-register-appointment-form").dialog({
                    autoOpen: false,
                    height: 650,
                    width: "90%",
                    modal: true,
                    buttons: [
                        {
                            text: setrio_bizcal_ajax.msg_request_appointment,
                            click: wsRegisterAppointment,
                        },
                        {
                            text: setrio_bizcal_ajax.msg_cancel,
                            click: function() {
                                    dialog.dialog("close");
                                }
                        }
                    ],
                    close: function() {
                        form[0].reset();
                        bizcalRAAllFields.removeClass("ui-state-error");
                    }
                });
     
                form = dialog.find("form").on("submit", function(event) {
                    event.preventDefault();
                    wsRegisterAppointment();
                });
     
                dialog.dialog("open");
            }
            else
            {
                wsRegisterAppointment();
            }
        }
        else
        {
            showErrorMessage(setrio_bizcal_ajax.msg_err_appointment_time_missing, setrio_bizcal_ajax.msg_error);
        }
    }
    else
    {
        showErrorMessage(setrio_bizcal_ajax.msg_err_physician_missing, setrio_bizcal_ajax.msg_error);
    }
}

function resetTips()
{
    bizcalRATips.html("").removeClass("ui-state-highlight").css("display", "hidden");
}

function updateTips(t)
{
    bizcalRATips
        .html((bizcalRATips.html().length > 0 ? bizcalRATips.html() + "<br/>" : "") + t)
        .addClass( "ui-state-highlight" )
        .css("display", "block");
}

function doOnDateSelect(dateText, obj)
{
    bizcalAppointmentDateSelected = true;
    
    if ( (!isFormWithPopups()) && (!bizcalDisableAutoRefreshAppointmentAvailability) )
        checkAvailability(false);
}

function setrioBizcalPopupGoBack()
{
    if (jQuery("#setrio-bizcal-page-1").is(":visible"))
    {
        //alert("register");
    }
    else if (jQuery("#setrio-bizcal-page-2").is(":visible"))
    {
        jQuery("#setrio-bizcal-page-1").show();
        jQuery("#setrio-bizcal-page-2").hide();
        jQuery("#setrio-bizcal-page-3").hide();
        setTimeout(function(){jQuery("#setrio-bizcal-main-box-content").scrollTop(0)}, 100);
        
        jQuery("#setrio-bizcal-popup-btn-back").prop('disabled', true);
        jQuery("#setrio-bizcal-popup-btn-back").addClass('ui-state-disabled');
    }
    else if (jQuery("#setrio-bizcal-page-3").is(":visible"))
    {
        jQuery("#setrio-bizcal-page-1").hide();
        jQuery("#setrio-bizcal-page-2").show();
        jQuery("#setrio-bizcal-page-3").hide();
        setTimeout(function(){jQuery("#setrio-bizcal-main-box-content").scrollTop(0)}, 100);
        
        jQuery("#setrio-bizcal-popup-btn-back").prop('disabled', false);
        jQuery("#setrio-bizcal-popup-btn-back").removeClass('ui-state-disabled');
    }
}

function setrioBizcalPopupContinue()
{
    if (jQuery("#setrio-bizcal-page-1").is(":visible"))
    {
        jQuery("#setrio-bizcal-page-1").hide();
        jQuery("#setrio-bizcal-page-2").show();
        jQuery("#setrio-bizcal-page-3").hide();
        setTimeout(function(){jQuery("#setrio-bizcal-main-box-content").scrollTop(0)}, 100);
        jQuery("#setrio-bizcal-popup-btn-back").prop('disabled', false);
        jQuery("#setrio-bizcal-popup-btn-back").removeClass('ui-state-disabled');
    }
    else if (jQuery("#setrio-bizcal-page-2").is(":visible"))
    {
        var physicianUID = getSelectedPhysicianUID();
        if (physicianUID != "")
        {
            var selectedDate = getSelectedDate();
            if ( (selectedDate != null) && (jQuery("#bizcal-sel-time li.ui-selected").length > 0) )
            {
                jQuery("#setrio-bizcal-page-1").hide();
                jQuery("#setrio-bizcal-page-2").hide();
                jQuery("#setrio-bizcal-page-3").show();
                setTimeout(function(){jQuery("#setrio-bizcal-main-box-content").scrollTop(0)}, 100);
                jQuery("#setrio-bizcal-popup-btn-back").prop('disabled', false);
                jQuery("#setrio-bizcal-popup-btn-back").removeClass('ui-state-disabled');
            }
            else
            {
                showErrorMessage(setrio_bizcal_ajax.msg_err_appointment_time_missing, setrio_bizcal_ajax.msg_error);
            }
        }
        else
        {
            showErrorMessage(setrio_bizcal_ajax.msg_err_appointment_time_missing, setrio_bizcal_ajax.msg_error);
        }
    }
    else if (jQuery("#setrio-bizcal-page-3").is(":visible"))
    {
        registerAppointment();
    }
}

function setrioBizcalStripDiacritics (text) {
    // Used 'uni range + named function' from http://jsperf.com/diacritics/18
    function match(a) {
			var DIACRITICS={"Ⓐ":"A","Ａ":"A","À":"A","Á":"A","Â":"A","Ầ":"A","Ấ":"A","Ẫ":"A","Ẩ":"A","Ã":"A","Ā":"A","Ă":"A","Ằ":"A","Ắ":"A","Ẵ":"A","Ẳ":"A","Ȧ":"A","Ǡ":"A","Ä":"A","Ǟ":"A","Ả":"A","Å":"A","Ǻ":"A","Ǎ":"A","Ȁ":"A","Ȃ":"A","Ạ":"A","Ậ":"A","Ặ":"A","Ḁ":"A","Ą":"A","Ⱥ":"A","Ɐ":"A","Ꜳ":"AA","Æ":"AE","Ǽ":"AE","Ǣ":"AE","Ꜵ":"AO","Ꜷ":"AU","Ꜹ":"AV","Ꜻ":"AV","Ꜽ":"AY","Ⓑ":"B","Ｂ":"B","Ḃ":"B","Ḅ":"B","Ḇ":"B","Ƀ":"B","Ƃ":"B","Ɓ":"B","Ⓒ":"C","Ｃ":"C","Ć":"C","Ĉ":"C","Ċ":"C","Č":"C","Ç":"C","Ḉ":"C","Ƈ":"C","Ȼ":"C","Ꜿ":"C","Ⓓ":"D","Ｄ":"D","Ḋ":"D","Ď":"D","Ḍ":"D","Ḑ":"D","Ḓ":"D","Ḏ":"D","Đ":"D","Ƌ":"D","Ɗ":"D","Ɖ":"D","Ꝺ":"D","Ǳ":"DZ","Ǆ":"DZ","ǲ":"Dz","ǅ":"Dz","Ⓔ":"E","Ｅ":"E","È":"E","É":"E","Ê":"E","Ề":"E","Ế":"E","Ễ":"E","Ể":"E","Ẽ":"E","Ē":"E","Ḕ":"E","Ḗ":"E","Ĕ":"E","Ė":"E","Ë":"E","Ẻ":"E","Ě":"E","Ȅ":"E","Ȇ":"E","Ẹ":"E","Ệ":"E","Ȩ":"E","Ḝ":"E","Ę":"E","Ḙ":"E","Ḛ":"E","Ɛ":"E","Ǝ":"E","Ⓕ":"F","Ｆ":"F","Ḟ":"F","Ƒ":"F","Ꝼ":"F","Ⓖ":"G","Ｇ":"G","Ǵ":"G","Ĝ":"G","Ḡ":"G","Ğ":"G","Ġ":"G","Ǧ":"G","Ģ":"G","Ǥ":"G","Ɠ":"G","Ꞡ":"G","Ᵹ":"G","Ꝿ":"G","Ⓗ":"H","Ｈ":"H","Ĥ":"H","Ḣ":"H","Ḧ":"H","Ȟ":"H","Ḥ":"H","Ḩ":"H","Ḫ":"H","Ħ":"H","Ⱨ":"H","Ⱶ":"H","Ɥ":"H","Ⓘ":"I","Ｉ":"I","Ì":"I","Í":"I","Î":"I","Ĩ":"I","Ī":"I","Ĭ":"I","İ":"I","Ï":"I","Ḯ":"I","Ỉ":"I","Ǐ":"I","Ȉ":"I","Ȋ":"I","Ị":"I","Į":"I","Ḭ":"I","Ɨ":"I","Ⓙ":"J","Ｊ":"J","Ĵ":"J","Ɉ":"J","Ⓚ":"K","Ｋ":"K","Ḱ":"K","Ǩ":"K","Ḳ":"K","Ķ":"K","Ḵ":"K","Ƙ":"K","Ⱪ":"K","Ꝁ":"K","Ꝃ":"K","Ꝅ":"K","Ꞣ":"K","Ⓛ":"L","Ｌ":"L","Ŀ":"L","Ĺ":"L","Ľ":"L","Ḷ":"L","Ḹ":"L","Ļ":"L","Ḽ":"L","Ḻ":"L","Ł":"L","Ƚ":"L","Ɫ":"L","Ⱡ":"L","Ꝉ":"L","Ꝇ":"L","Ꞁ":"L","Ǉ":"LJ","ǈ":"Lj","Ⓜ":"M","Ｍ":"M","Ḿ":"M","Ṁ":"M","Ṃ":"M","Ɱ":"M","Ɯ":"M","Ⓝ":"N","Ｎ":"N","Ǹ":"N","Ń":"N","Ñ":"N","Ṅ":"N","Ň":"N","Ṇ":"N","Ņ":"N","Ṋ":"N","Ṉ":"N","Ƞ":"N","Ɲ":"N","Ꞑ":"N","Ꞥ":"N","Ǌ":"NJ","ǋ":"Nj","Ⓞ":"O","Ｏ":"O","Ò":"O","Ó":"O","Ô":"O","Ồ":"O","Ố":"O","Ỗ":"O","Ổ":"O","Õ":"O","Ṍ":"O","Ȭ":"O","Ṏ":"O","Ō":"O","Ṑ":"O","Ṓ":"O","Ŏ":"O","Ȯ":"O","Ȱ":"O","Ö":"O","Ȫ":"O","Ỏ":"O","Ő":"O","Ǒ":"O","Ȍ":"O","Ȏ":"O","Ơ":"O","Ờ":"O","Ớ":"O","Ỡ":"O","Ở":"O","Ợ":"O","Ọ":"O","Ộ":"O","Ǫ":"O","Ǭ":"O","Ø":"O","Ǿ":"O","Ɔ":"O","Ɵ":"O","Ꝋ":"O","Ꝍ":"O","Ƣ":"OI","Ꝏ":"OO","Ȣ":"OU","Ⓟ":"P","Ｐ":"P","Ṕ":"P","Ṗ":"P","Ƥ":"P","Ᵽ":"P","Ꝑ":"P","Ꝓ":"P","Ꝕ":"P","Ⓠ":"Q","Ｑ":"Q","Ꝗ":"Q","Ꝙ":"Q","Ɋ":"Q","Ⓡ":"R","Ｒ":"R","Ŕ":"R","Ṙ":"R","Ř":"R","Ȑ":"R","Ȓ":"R","Ṛ":"R","Ṝ":"R","Ŗ":"R","Ṟ":"R","Ɍ":"R","Ɽ":"R","Ꝛ":"R","Ꞧ":"R","Ꞃ":"R","Ⓢ":"S","Ｓ":"S","ẞ":"S","Ś":"S","Ṥ":"S","Ŝ":"S","Ṡ":"S","Š":"S","Ṧ":"S","Ṣ":"S","Ṩ":"S","Ș":"S","Ş":"S","Ȿ":"S","Ꞩ":"S","Ꞅ":"S","Ⓣ":"T","Ｔ":"T","Ṫ":"T","Ť":"T","Ṭ":"T","Ț":"T","Ţ":"T","Ṱ":"T","Ṯ":"T","Ŧ":"T","Ƭ":"T","Ʈ":"T","Ⱦ":"T","Ꞇ":"T","Ꜩ":"TZ","Ⓤ":"U","Ｕ":"U","Ù":"U","Ú":"U","Û":"U","Ũ":"U","Ṹ":"U","Ū":"U","Ṻ":"U","Ŭ":"U","Ü":"U","Ǜ":"U","Ǘ":"U","Ǖ":"U","Ǚ":"U","Ủ":"U","Ů":"U","Ű":"U","Ǔ":"U","Ȕ":"U","Ȗ":"U","Ư":"U","Ừ":"U","Ứ":"U","Ữ":"U","Ử":"U","Ự":"U","Ụ":"U","Ṳ":"U","Ų":"U","Ṷ":"U","Ṵ":"U","Ʉ":"U","Ⓥ":"V","Ｖ":"V","Ṽ":"V","Ṿ":"V","Ʋ":"V","Ꝟ":"V","Ʌ":"V","Ꝡ":"VY","Ⓦ":"W","Ｗ":"W","Ẁ":"W","Ẃ":"W","Ŵ":"W","Ẇ":"W","Ẅ":"W","Ẉ":"W","Ⱳ":"W","Ⓧ":"X","Ｘ":"X","Ẋ":"X","Ẍ":"X","Ⓨ":"Y","Ｙ":"Y","Ỳ":"Y","Ý":"Y","Ŷ":"Y","Ỹ":"Y","Ȳ":"Y","Ẏ":"Y","Ÿ":"Y","Ỷ":"Y","Ỵ":"Y","Ƴ":"Y","Ɏ":"Y","Ỿ":"Y","Ⓩ":"Z","Ｚ":"Z","Ź":"Z","Ẑ":"Z","Ż":"Z","Ž":"Z","Ẓ":"Z","Ẕ":"Z","Ƶ":"Z","Ȥ":"Z","Ɀ":"Z","Ⱬ":"Z","Ꝣ":"Z","ⓐ":"a","ａ":"a","ẚ":"a","à":"a","á":"a","â":"a","ầ":"a","ấ":"a","ẫ":"a","ẩ":"a","ã":"a","ā":"a","ă":"a","ằ":"a","ắ":"a","ẵ":"a","ẳ":"a","ȧ":"a","ǡ":"a","ä":"a","ǟ":"a","ả":"a","å":"a","ǻ":"a","ǎ":"a","ȁ":"a","ȃ":"a","ạ":"a","ậ":"a","ặ":"a","ḁ":"a","ą":"a","ⱥ":"a","ɐ":"a","ꜳ":"aa","æ":"ae","ǽ":"ae","ǣ":"ae","ꜵ":"ao","ꜷ":"au","ꜹ":"av","ꜻ":"av","ꜽ":"ay","ⓑ":"b","ｂ":"b","ḃ":"b","ḅ":"b","ḇ":"b","ƀ":"b","ƃ":"b","ɓ":"b","ⓒ":"c","ｃ":"c","ć":"c","ĉ":"c","ċ":"c","č":"c","ç":"c","ḉ":"c","ƈ":"c","ȼ":"c","ꜿ":"c","ↄ":"c","ⓓ":"d","ｄ":"d","ḋ":"d","ď":"d","ḍ":"d","ḑ":"d","ḓ":"d","ḏ":"d","đ":"d","ƌ":"d","ɖ":"d","ɗ":"d","ꝺ":"d","ǳ":"dz","ǆ":"dz","ⓔ":"e","ｅ":"e","è":"e","é":"e","ê":"e","ề":"e","ế":"e","ễ":"e","ể":"e","ẽ":"e","ē":"e","ḕ":"e","ḗ":"e","ĕ":"e","ė":"e","ë":"e","ẻ":"e","ě":"e","ȅ":"e","ȇ":"e","ẹ":"e","ệ":"e","ȩ":"e","ḝ":"e","ę":"e","ḙ":"e","ḛ":"e","ɇ":"e","ɛ":"e","ǝ":"e","ⓕ":"f","ｆ":"f","ḟ":"f","ƒ":"f","ꝼ":"f","ⓖ":"g","ｇ":"g","ǵ":"g","ĝ":"g","ḡ":"g","ğ":"g","ġ":"g","ǧ":"g","ģ":"g","ǥ":"g","ɠ":"g","ꞡ":"g","ᵹ":"g","ꝿ":"g","ⓗ":"h","ｈ":"h","ĥ":"h","ḣ":"h","ḧ":"h","ȟ":"h","ḥ":"h","ḩ":"h","ḫ":"h","ẖ":"h","ħ":"h","ⱨ":"h","ⱶ":"h","ɥ":"h","ƕ":"hv","ⓘ":"i","ｉ":"i","ì":"i","í":"i","î":"i","ĩ":"i","ī":"i","ĭ":"i","ï":"i","ḯ":"i","ỉ":"i","ǐ":"i","ȉ":"i","ȋ":"i","ị":"i","į":"i","ḭ":"i","ɨ":"i","ı":"i","ⓙ":"j","ｊ":"j","ĵ":"j","ǰ":"j","ɉ":"j","ⓚ":"k","ｋ":"k","ḱ":"k","ǩ":"k","ḳ":"k","ķ":"k","ḵ":"k","ƙ":"k","ⱪ":"k","ꝁ":"k","ꝃ":"k","ꝅ":"k","ꞣ":"k","ⓛ":"l","ｌ":"l","ŀ":"l","ĺ":"l","ľ":"l","ḷ":"l","ḹ":"l","ļ":"l","ḽ":"l","ḻ":"l","ſ":"l","ł":"l","ƚ":"l","ɫ":"l","ⱡ":"l","ꝉ":"l","ꞁ":"l","ꝇ":"l","ǉ":"lj","ⓜ":"m","ｍ":"m","ḿ":"m","ṁ":"m","ṃ":"m","ɱ":"m","ɯ":"m","ⓝ":"n","ｎ":"n","ǹ":"n","ń":"n","ñ":"n","ṅ":"n","ň":"n","ṇ":"n","ņ":"n","ṋ":"n","ṉ":"n","ƞ":"n","ɲ":"n","ŉ":"n","ꞑ":"n","ꞥ":"n","ǌ":"nj","ⓞ":"o","ｏ":"o","ò":"o","ó":"o","ô":"o","ồ":"o","ố":"o","ỗ":"o","ổ":"o","õ":"o","ṍ":"o","ȭ":"o","ṏ":"o","ō":"o","ṑ":"o","ṓ":"o","ŏ":"o","ȯ":"o","ȱ":"o","ö":"o","ȫ":"o","ỏ":"o","ő":"o","ǒ":"o","ȍ":"o","ȏ":"o","ơ":"o","ờ":"o","ớ":"o","ỡ":"o","ở":"o","ợ":"o","ọ":"o","ộ":"o","ǫ":"o","ǭ":"o","ø":"o","ǿ":"o","ɔ":"o","ꝋ":"o","ꝍ":"o","ɵ":"o","ƣ":"oi","ȣ":"ou","ꝏ":"oo","ⓟ":"p","ｐ":"p","ṕ":"p","ṗ":"p","ƥ":"p","ᵽ":"p","ꝑ":"p","ꝓ":"p","ꝕ":"p","ⓠ":"q","ｑ":"q","ɋ":"q","ꝗ":"q","ꝙ":"q","ⓡ":"r","ｒ":"r","ŕ":"r","ṙ":"r","ř":"r","ȑ":"r","ȓ":"r","ṛ":"r","ṝ":"r","ŗ":"r","ṟ":"r","ɍ":"r","ɽ":"r","ꝛ":"r","ꞧ":"r","ꞃ":"r","ⓢ":"s","ｓ":"s","ß":"s","ś":"s","ṥ":"s","ŝ":"s","ṡ":"s","š":"s","ṧ":"s","ṣ":"s","ṩ":"s","ș":"s","ş":"s","ȿ":"s","ꞩ":"s","ꞅ":"s","ẛ":"s","ⓣ":"t","ｔ":"t","ṫ":"t","ẗ":"t","ť":"t","ṭ":"t","ț":"t","ţ":"t","ṱ":"t","ṯ":"t","ŧ":"t","ƭ":"t","ʈ":"t","ⱦ":"t","ꞇ":"t","ꜩ":"tz","ⓤ":"u","ｕ":"u","ù":"u","ú":"u","û":"u","ũ":"u","ṹ":"u","ū":"u","ṻ":"u","ŭ":"u","ü":"u","ǜ":"u","ǘ":"u","ǖ":"u","ǚ":"u","ủ":"u","ů":"u","ű":"u","ǔ":"u","ȕ":"u","ȗ":"u","ư":"u","ừ":"u","ứ":"u","ữ":"u","ử":"u","ự":"u","ụ":"u","ṳ":"u","ų":"u","ṷ":"u","ṵ":"u","ʉ":"u","ⓥ":"v","ｖ":"v","ṽ":"v","ṿ":"v","ʋ":"v","ꝟ":"v","ʌ":"v","ꝡ":"vy","ⓦ":"w","ｗ":"w","ẁ":"w","ẃ":"w","ŵ":"w","ẇ":"w","ẅ":"w","ẘ":"w","ẉ":"w","ⱳ":"w","ⓧ":"x","ｘ":"x","ẋ":"x","ẍ":"x","ⓨ":"y","ｙ":"y","ỳ":"y","ý":"y","ŷ":"y","ỹ":"y","ȳ":"y","ẏ":"y","ÿ":"y","ỷ":"y","ẙ":"y","ỵ":"y","ƴ":"y","ɏ":"y","ỿ":"y","ⓩ":"z","ｚ":"z","ź":"z","ẑ":"z","ż":"z","ž":"z","ẓ":"z","ẕ":"z","ƶ":"z","ȥ":"z","ɀ":"z","ⱬ":"z","ꝣ":"z","Ά":"Α","Έ":"Ε","Ή":"Η","Ί":"Ι","Ϊ":"Ι","Ό":"Ο","Ύ":"Υ","Ϋ":"Υ","Ώ":"Ω","ά":"α","έ":"ε","ή":"η","ί":"ι","ϊ":"ι","ΐ":"ι","ό":"ο","ύ":"υ","ϋ":"υ","ΰ":"υ","ω":"ω","ς":"σ"}
      return DIACRITICS[a] || a;
    }

    return text.replace(/[^\u0000-\u007E]/g, match);
}

function setrioBizcalSafeText(text){
	text = typeof text === 'string' ? text : '';
	text = setrioBizcalStripDiacritics(text);
	text = text.replace(/[^a-zA-Z0-9]/gs,' ');
	text = text.replace(/^\s+/,'');
	text = text.replace(/\s+$/,'');
	text = text.toLowerCase();
	return text;
}

function setrioBizcalPopupShow(button)
{
    jQuery("#setrio-bizcal-page-1").show();
    jQuery("#setrio-bizcal-page-2").hide();
    jQuery("#setrio-bizcal-page-3").hide();
    
    var wpAdminBarHeight = 0;
    if (jQuery("div#wpadminbar").length > 0)
        wpAdminBarHeight = jQuery("div#wpadminbar").height();
        
    var defaultSpeciality = jQuery(button).attr("data-speciality");
    var defaultService = jQuery(button).attr("data-service");
    var defaultPhysician = jQuery(button).attr("data-physician");
    var defaultLocation = jQuery(button).attr("data-location");

    if (typeof(defaultSpeciality) == "undefined")
        defaultSpeciality = "";
    if (typeof(defaultService) == "undefined")
        defaultService = "";
    if (typeof(defaultPhysician) == "undefined")
        defaultPhysician = "";
    if (typeof(defaultLocation) == "undefined")
        defaultLocation = "";
    
    bizcalDefaultSpeciality = defaultSpeciality;
    bizcalDefaultService = defaultService;
    bizcalDefaultPhysician = defaultPhysician;
    bizcalDefaultLocation = setrioBizcalSafeText(defaultLocation);
console.log("DEFAULT PHYSICIAN", bizcalDefaultPhysician);    
    console.log("DEFAULT SERVICE INIT", bizcalDefaultService);
    console.log("DEFAULT LOCATION INIT", bizcalDefaultLocation);
    
    if (defaultSpeciality != "")
    {
        if (defaultSpeciality != getSelectedMedicalSpecialityCode())
        {
            setSelectedMedicalSpecialityCode(defaultSpeciality, true);
        }
    }
    else
        jQuery(".bizcal-sel-spec").prop("disabled", false);
    
    /* if (defaultLocation != "")
    {
        if (setrioBizcalSafeText(defaultLocation) != setrioBizcalSafeText(getSelectedLocationName()))
        {
            setSelectedLocationName(defaultLocation, true);
        }
    }
    else
        jQuery(".bizcal-sel-location").prop("disabled", false); */
    
    if (defaultService != "")
    {
        if (defaultService != getSelectedMedicalServiceName())
        {
            setSelectedMedicalServiceName(defaultService, true);
        }
    }
    else
        jQuery('#bizcal-sel-serv').prop("disabled", false);
    
    if (defaultPhysician != "")
    {
        if (defaultPhysician != getSelectedPhysicianName())
        {
            doOnSelectMedicalSpeciality();
            setPreferredPhysicianName(defaultPhysician, true);
        }
    }
    else
    {
        jQuery('.bizcal-sel-preferred-physician').prop("disabled", false);
        if (defaultPhysician != getSelectedPhysicianName())
        {
            doOnSelectMedicalSpeciality();
        }
    }
    
    jQuery("#setrio-bizcal-main-box-content").dialog({
        modal: true,
        title: 'Solicită o programare',
        appendTo: '.bizcal-main-box',
        height: bizcalIsMobileVersion ? Math.max(document.documentElement.clientHeight, window.innerHeight || 0) - wpAdminBarHeight : 600,
        maxHeight: bizcalIsMobileVersion ? Math.max(document.documentElement.clientHeight, window.innerHeight || 0) - wpAdminBarHeight : 600,
        width: bizcalIsMobileVersion ? document.body.clientWidth : 900,
        maxWidth: 900,
        position: {
            my: ((bizcalIsMobileVersion == true) && (wpAdminBarHeight > 0)) ? "bottom" : "center",
            at: ((bizcalIsMobileVersion == true) && (wpAdminBarHeight > 0)) ? "bottom" : "center",
            of: window
        },
        buttons: [
            {
                id: "setrio-bizcal-popup-btn-back",
                text: "Înapoi",
                icon: "ui-icon-back",
                disabled: true,
                click: setrioBizcalPopupGoBack   
            },
            {
                id: "setrio-bizcal-popup-btn-next",
                text: "Continuă",
                icon: "ui-icon-next",
                disabled: true,
                click: setrioBizcalPopupContinue   
            }
        ]
    });
}

jQuery(document).ready(function($)
{   
    // Parse BizCalendar components and attach a Vue application to each of them (we use different applications because each form must behave differently)
    const bizcalComponents = document.getElementsByClassName('bizcal-main-box-vue');

	if (bizcalComponents.length > 0)
	{
      console.log("VUE INIT...");
    
      // Vue components registration
      //Vue.component('v-select', window.VueMultiselect.default);
      
      // Init Vuex
      Vue.use(Vuex);
	}

    for (var i = 0; i < bizcalComponents.length; i++)
    {
        new Vue({
            el: "div#" + bizcalComponents[i].id,
            store: new Vuex.Store({
                strict: true,
                state: {
                    selectedSpeciality: null,
                    selectedLocation: null,
                    selectedPaymentType: null,
                    selectedService: null,
                    selectedPreferredPhysician: null,
                    selectedAppointmentDate: null,                  

                    specialities: [],
                    locations: [],
                    paymentTypes: [],
                    services: [],
                    preferredPhysicians: [],

                    availablePhysicians: []
                },
                mutations: {
                    setSpecialities(state, value) {
                        state.specialities = value;
                    },
                    selectSpeciality(state, value) {
                        state.selectedSpeciality = value;
                    },

                    setLocations(state, payload) {
                        console.log('received location', payload.selectedLocation);
                        state.locations = payload.locations;
                        state.selectedLocation = payload.selectedLocation;
                    },
                    selectLocation(state, value) {
                        console.log("mutation start", value);
                        state.selectedLocation = value;
                    },

                    setPaymentTypes(state, payload) {
                        console.log('received payment type ', payload.selectedPaymentType);
                        state.paymentTypes = payload.paymentTypes;
                        console.log('setting payment type to ', payload.selectedPaymentType);
                        state.selectedPaymentType = payload.selectedPaymentType;
                    },
                    selectPaymentType(state, value) {
                        state.selectedPaymentType = value;
                    },

                    setServices(state, payload) {
                        state.services = payload.services;
                        state.selectedService = payload.selectedService;
                    },
                    selectService(state, value) {
                        state.selectedService = value;
                    },

                    setPreferredPhysicians(state, payload) {
                        state.preferredPhysicians = payload.preferredPhysicians;
                        state.selectedPreferredPhysician = payload.selectedPreferredPhysician;
                    },
                    selectPreferredPhysician(state, value) {
                        state.selectedPreferredPhysician = value;
                    },
                    selectAppointmentDate(state, value) {
                        state.selectedAppointmentDate = value;
                    }
                },
                actions: {
                    loadSpecialities(context) {
                        getMedicalSpecialitiesFromService(context);
                    },
                    loadLocations(context) {
                        if (setrio_bizcal_ajax.enable_multiple_locations)
                            getLocationsFromService(context);
                        else
                            context.commit('selectLocation', null);
                    },
                    loadPaymentTypes(context) {
                        getAllowedPaymentTypesFromService(context);
                    },
                    loadServices(context) {
                        getMedicalServicesFromService(context);
                    },
                    loadPreferredPhysicians(context) {
                        getPhysiciansFromService(context);
                    },
                    checkAvailabilities(context) {
                        getAppointmentAvailabilities(context);
                    },
                    
                    selectSpeciality(context, value) {
                        context.commit('selectSpeciality', value);
                    },
                    selectLocation(context, value) {
                        context.commit('selectLocation', value);
                    },
                    selectPaymentType(context, value) {
                        context.commit('selectPaymentType', value);
                    },
                    selectService(context, value) {
                        context.commit('selectService', value);
                    },
                    selectPreferredPhysician(context, value) {
                        context.commit('selectPreferredPhysician', value);
                    },
                    selectAppointmentDate(context, value) {
                        context.commit("selectAppointmentDate", value);
                    },
                    checkAppointmentAvailability(context) {
                        getAppointmentAvailabilities(context);
                    },
                    selectAppointmentInterval(context) {},
                    registerAppointment(context) {},
                }
            }),
            data: function() {
                return {
                    hasDefaultSpeciality: false,                   
                    
                    specialityCombo: null,
                    locationCombo: null,
                    //locationComboClass: "hidden",
                    paymentTypeCombo: null,
                    //paymentTypeComboClass: "hidden",
                    servicesCombo: null,
                    servicesComboClass: "hidden",
                    preferredPhysiciansCombo: null,
                    preferredPhysiciansComboClass: "hidden",                                      
                }
            },
            methods: {
                incrementDebugValue() {
                    this.$store.dispatch('incrementDebugValue');
                }
            },
            computed: {
                selectedSpeciality: {
                    get() { return this.$store.state.selectedSpeciality; },
                    set(value) { this.$store.dispatch('selectSpeciality', value); }
                },
                selectedLocation: {
                    get() { return this.$store.state.selectedLocation; },
                    set(value) { console.log("setter", value); this.$store.dispatch('selectLocation', value); }
                },
                locationComboClass: {
                    get() {
                        if ((setrio_bizcal_ajax.enable_multiple_locations) && (this.$store.state.selectedSpeciality != null))
                        // (this.$store.state.locations != null) && (this.$store.state.locations.length > 0))
                            return ""
                        else
                            return "hidden";
                    }
                },
                selectedPaymentType: {
                    get() { return this.$store.state.selectedPaymentType; },
                    set(value) {this.$store.dispatch('selectPaymentType', value); }
                },
                paymentTypeComboClass: {
                    get() {
                        if ((this.$store.state.paymentTypes != null) && (this.$store.state.paymentTypes.length > 1))
                            return "";
                        else
                            return "hidden";
                    }
                },

                selectedService: {
                    get() { return this.$store.state.selectedService; },
                    set(value) {this.$store.dispatch('selectService', value); }
                },
                serviceComboClass: {
                    get() {
                        if ((this.$store.state.services != null) && (this.$store.state.services.length > 0))
                            return "";
                        else
                            return "hidden";
                    }
                },

                selectedPreferredPhysician: {
                    get() { return this.$store.state.selectedPreferredPhysician; },
                    set(value) {this.$store.dispatch('selectPreferredPhysician', value); }
                },
                preferredPhysicianComboClass: {
                    get() {
                        if ((this.$store.state.preferredPhysicians != null) && (this.$store.state.preferredPhysicians.length > 1))
                            return "";
                        else
                            return "hidden";
                    }
                },

                selectedAppointmentDate: {
                    get() { return this.$store.state.selectedAppointmentDate; },
                    set(value) {this.$store.dispatch('selectAppointmentDate', value); }
                },

                ...Vuex.mapState({
                debugValue: state => state.debugValue,

                specialities:  state => state.specialities,
                locations:  state => state.locations,
                paymentTypes:  state => state.paymentTypes,
                services:  state => state.services,
                preferredPhysicians:  state => state.preferredPhysicians,

                availablePhysicians: state => state.availablePhysicians
                })
            },
            mounted: function() {
                console.log('VUE - mounted - start');
                this.$store.dispatch('loadSpecialities');
                //getMedicalSpecialitiesFromService(this);
                console.log('VUE - mounted - end');
            },
            watch: {
                selectedSpeciality: function(val, oldVal) {
                    console.log("NEW SPEC", this.$store.state.selectedSpeciality);
                    console.log("OLD SPEC", oldVal);

                    this.$store.dispatch('loadLocations');

                    /*this.selectedLocation = null;
                    this.selectedPaymentType = null;
                    this.selectedService = null;
                    this.selectedPreferredPhysician = null;
                    
                    this.servicesComboClass = "hidden";
                    this.preferredPhysiciansComboClass = "hidden";
                    
                    if (setrio_bizcal_ajax.enable_multiple_locations) {
                        if (val != null) {
                            this.locationComboClass = "";
                            getLocationsFromService(this, true);
                        }
                        else {
                            this.locationComboClass = "hidden";
                        }
                    }
                    else {
                        if (val != null) {
                            this.paymentTypeComboClass = "";
                            getAllowedPaymentTypesFromService(this, true);
                        }
                        else {
                            this.paymentTypeComboClass = "hidden";
                        }
                    }*/
                },
                locations: function(val, oldVal) {
                    console.log("watch -> locations -> ENTER");
                    if (this.$refs.locationCombo)
                    {
                        this.$refs.locationCombo.focus();
                        console.log('selectedLocation', this.$store.state.selectedLocation);
                        if ((this.$store.state.selectedLocation == null) || (typeof(this.$store.state.selectedLocation) == "undefined"))
                        {
                            this.$refs.locationCombo.showPopup();
                        }
                    }
                    console.log("watch -> locations -> END");
                },
                selectedLocation: function(val, oldVal) {
                    console.log("NEW LOC", this.$store.state.selectedLocation);
                    console.log("OLD LOC", oldVal);

                    this.$store.dispatch("loadPaymentTypes");

                    /*this.selectedPaymentType = null;
                    this.selectedService = null;
                    this.selectedPreferredPhysician = null;
                    
                    this.servicesComboClass = "hidden";
                    this.preferredPhysiciansComboClass = "hidden";
                    
                    if ((this.selectedSpeciality != null) && ((val != null) || (!setrio_bizcal_ajax.enable_multiple_locations))) {
                        this.paymentTypeComboClass = "";
                        getAllowedPaymentTypesFromService(this, true);
                    }
                    else {
                        this.paymentTypeComboClass = "hidden";
                    }*/
                },
                paymentTypes: function(val) {
                    
                    //if ((val == null) || (val.length <= 1))
                    //    this.paymentTypeComboClass = "hidden";
                    //this.paymentTypeComboClass = "";
                },
                selectedPaymentType: function(val) {
                    console.log("OnSelectPaymentType - START", val);
                    if ((setrio_bizcal_ajax.appointment_param_order == 0) && (bizcalDefaultPhysician == ""))
                    {
                        this.$store.dispatch("loadServices");
                    }
                    else
                    {
                        this.$store.dispatch("loadPreferredPhysicians");
                    }
                    /*this.selectedService = null;
                    this.selectedPreferredPhysician = null;
                    this.servicesComboClass = "hidden";
                    this.preferredPhysiciansComboClass = "hidden";
                    
                    if (val == 2)
                    {
                        
                        bizcalMedicalServicesInitialized = true;
                        hideMedicalServicesSelectionBox();
                        if (setrio_bizcal_ajax.allow_search_physician == true)
                        {
                            this.preferredPhysiciansComboClass = "";
                            getPhysiciansFromService(this, true);
                        }
                    }
                    else
                    {
                        if ((setrio_bizcal_ajax.appointment_param_order == 0) && (bizcalDefaultPhysician == ""))
                        {
                            this.servicesComboClass = "";
                            getMedicalServicesFromService(this, true);
                        }
                        else
                        {
                            this.preferredPhysiciansComboClass = "";
                            getPhysiciansFromService(this, true);
                        }
                    }*/
                },
                services: function(val) {
                    if ((setrio_bizcal_ajax.appointment_param_order == 0) && (bizcalDefaultPhysician == ""))
                        this.$store.dispatch("loadPreferredPhysicians");
                },
                selectedService: function(val) {
                    console.log('selected service changed!');
                    if ((setrio_bizcal_ajax.appointment_param_order == 0) && (bizcalDefaultPhysician == ""))
                        this.$store.dispatch("loadPreferredPhysicians");
                },
                selectedPreferredPhysician: function(val) {
                    if (!((setrio_bizcal_ajax.appointment_param_order == 0) && (bizcalDefaultPhysician == "")))
                        this.$store.dispatch("loadServices");
                },
                selectedAppointmentDate: function(val) {
                    console.log("GETA");
                    this.$store.dispatch("checkAvailabilities");
                }
            }
        })
    }  

	if (bizcalComponents.length > 0)
	{
	    console.log("VUE END...");
	}

    bizcalIsMobileVersion = window.top.window.document.body.clientWidth <= 991;
    
    if ((typeof $.fn.datepicker != "undefined") && (typeof $.fn.datepicker.noConflict != "undefined"))
    {
        var datepicker = $.fn.datepicker.noConflict(); // return $.fn.datepicker to previously assigned value
        $.fn.bootstrapDP = datepicker;                 // give $().bootstrapDP the bootstrap-datepicker functionality
    }
	setrioBizcalSelect2Destroy(".bizcal-sel-spec");
    $(".bizcal-sel-spec").select2({
		theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
        placeholder: setrio_bizcal_ajax.msg_medical_speciality_placeholder,
        minimumResultsForSearch: bizcalIsMobileVersion ? -1 : 0,
    });
	
	setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-spec"));
    
    $(".bizcal-sel-spec").on("select2:select", doOnSelectMedicalSpeciality);
    
    if (setrio_bizcal_ajax.enable_multiple_locations)
    {
		setrioBizcalSelect2Destroy(".bizcal-sel-location");
        $(".bizcal-sel-location").select2({
			theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
            placeholder: setrio_bizcal_ajax.msg_location_placeholder,
            minimumResultsForSearch: bizcalIsMobileVersion ? -1 : 0,
        });
		
		setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-location"));
        
        $(".bizcal-sel-location").on("select2:select", doOnSelectLocation);
    }
	setrioBizcalSelect2Destroy(".bizcal-sel-payment");
    $(".bizcal-sel-payment").select2({
		theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
        placeholder: setrio_bizcal_ajax.msg_payment_type_placeholder,
        minimumResultsForSearch: bizcalIsMobileVersion ? -1 : 0
    });
	
	setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-payment"));
    
	setrioBizcalSelect2Destroy(".bizcal-sel-preferred-physician");
    $(".bizcal-sel-preferred-physician").select2({
		theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
        placeholder: setrio_bizcal_ajax.msg_physician_placeholder,
        minimumResultsForSearch: bizcalIsMobileVersion ? -1 : 0
    });
	
	setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-preferred-physician"));
    
    /*$(".bizcal-sel-med").select2({
		theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
        placeholder: setrio_bizcal_ajax.msg_physician_placeholder,
        minimumResultsForSearch: -1
    });

    $(".bizcal-sel-med").on("select2:select", doOnSelectPhysician);*/
	
	setrioBizcalSelect2Destroy(".bizcal-sel-serv");
    $(".bizcal-sel-serv").select2({
		theme: !setrio_bizcal_ajax['enableCustomJQueryUI'] ? 'default': 'jquery-ui',
        placeholder: setrio_bizcal_ajax.msg_medical_service_placeholder,
        minimumResultsForSearch: bizcalIsMobileVersion ? -1 : 0
    });
	
	setrioBizcalSelect2JqueryUI(jQuery(".bizcal-sel-serv"));
    
    $(".bizcal-sel-serv").on("select2:select", doOnSelectMedicalService);

    wsGetMedicalSpecialities();      
    //wsGetPaymentTypes();
    
    $(".bizcal-sel-preferred-physician").on("select2:select", doOnSelectPreferredPhysician);

    //$("#bizcal-sel-search-mode-container").checkboxradio();
    //$("#bizcal-sel-search-mode").controlgroup();
        
    $.datepicker.setDefaults($.extend({
        dateFormat: 'yyyymmdd',
        minDate: 0,
        firstDay: 1,
        dayNamesMin: ['D', 'L', 'Ma', 'Mi', 'J', 'V', 'S'],
        monthNames: ["ianuarie", "februarie", "martie", "aprilie", "mai", "iunie", "iulie", "august", "septembrie", "octombrie", "noiembrie", "decembrie"],
        gotoCurrent: true
        }, $.datepicker.regional['ro']));

    $("#bizcal-sel-date").datepicker({
        onSelect: doOnDateSelect,
        dateFormat: "yyyymmdd",
        defaultDate: +setrio_bizcal_ajax.min_days_to_appointment,
        minDate: setrio_bizcal_ajax.min_days_to_appointment,
        }).ready(function(){
            bizcalAppointmentDateSelected = true;
        });
        
    bizcalAppointmentDateSelected = true; // La Sante Vera nu se apeleaza evenimentul ready de mai sus

    $("#bizcal-sel-time").selectable({
		selected: function(event, ui){jQuery(ui.selected).addClass('ui-state-active');},unselected: function(event, ui){jQuery(ui.unselected).removeClass('ui-state-active');},
	});
    
    if (!isFormWithPopups())
    {
        form = $("#bizcal-register-appointment-form").find("form").on("submit", function(event) {
            event.preventDefault();
            registerAppointment();
        });
    }
    
    jQuery(".setrio-bizcal-appointment-button").on("click", function (e) {
        console.log("Showing popup...");
        console.log(this);
        setrioBizcalPopupShow(this);
        e.preventDefault();
        console.log("Popup shown!");
    });
    
    jQuery("a.bt_2").attr("onClick", "");
    jQuery("a.bt_2").unbind("click");
    jQuery("a.bt_2").click(function (e) {
        console.log("click start");
        setrioBizcalPopupShow();
        //e.stopPropagation();
        //e.preventDefault();
        jQuery("#buttonizer-sys").removeClass("opened");
        jQuery("#buttonizer-sys").addClass("closed");
        console.log("click end");
        //return false;
    });
});