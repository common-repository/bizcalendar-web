<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once("common.php");
require_once("communication.php");
require_once("localdata.php");
require_once("recaptcha/recaptchalib.php");
require_once("render.php");

$setrio_bizcal_debug = false;
$setrio_bizcal_securemode = false;

// INREGISTRARE PLUGIN IN WORDPRESS

function setrio_bizcal_add_async_forrecaptcha($url)
{
	if (strpos($url, '#bizcalgrecaptchaload') === false)
		return $url;
	else if (is_admin())
		return str_replace('#bizcalgrecaptchaload', '', $url);
	else
		return str_replace('#bizcalgrecaptchaload', '&hl=ro&onload=BizcalRenderCaptcha&render=explicit', $url) . "' async='async' defer='defer";
}

function setrio_bizcal_sortArrayByArray(array $array, array $orderArray)
{
	$ordered = array();
	foreach ($orderArray as $key) {
		if (array_key_exists($key, $array)) {
			$ordered[$key] = $array[$key];
			unset($array[$key]);
		}
	}
	return $ordered + $array;
}

function setrio_bizcal_enqueue_scripts($hook=null)
{
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-selectable');
	wp_enqueue_script('jquery-ui-widget');
	wp_enqueue_script('jquery-ui-button');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-ui-datepicker-ro');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('setrio-bizcal-recaptcha', 'https://www.google.com/recaptcha/api.js?#bizcalgrecaptchaload', [], "1.0.0.0", true);
	wp_enqueue_script('setrio-bizcal-select2-script', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js', array('jquery'),"1.0.0.0");
	wp_enqueue_script('setrio-bizcal-select2-script-jui', plugins_url('/select2/js/select2-jquery-ui.js', __FILE__), array('jquery'),"1.0.0.0");
	wp_enqueue_style('setrio-bizcal-select2-style', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css',[],"1.0.0.0");

	$enableCustomJQueryUI = get_option('setrio_bizcal_enable_custom_jquery_ui');
	$jQueryUIUploadsPath = get_option('setrio_bizcal_jquery_ui_uploads_path');
	$wp_upload_dir = wp_upload_dir($jQueryUIUploadsPath, false);
	$custom_css_file_exists = is_file($wp_upload_dir['path'] . '/setrio-bizcalendar/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css');
	if ($enableCustomJQueryUI && !$custom_css_file_exists) {
		$enableCustomJQueryUI = false;
	}
	if ($enableCustomJQueryUI) {
		wp_enqueue_style('jquery-ui', plugins_url('/css/jquery-ui-custom/jquery-ui.min.css', __FILE__),[],"1.0.0.0");
		wp_enqueue_style('jquery-ui', $wp_upload_dir['url'] . '/setrio-bizcalendar/jquery-ui-1.12.1.custom/jquery-ui.css',[],"1.0.0.0");
		wp_enqueue_style('jquery-ui-theme', $wp_upload_dir['url'] . '/setrio-bizcalendar/jquery-ui-1.12.1.custom/jquery-ui.theme.css',[],"1.0.0.0");
	} else {
		wp_enqueue_style('setrio-bizcal-select2-style-jui', plugins_url('/select2/css/select2-jquery-ui.css', __FILE__),[],"1.0.0.0");
		wp_enqueue_style('jquery-ui', plugins_url('/css/jquery-ui.css', __FILE__),[],"1.0.0.0");
		wp_enqueue_style('jquery-ui-structure', plugins_url('/css/jquery-ui.structure.css', __FILE__),[],"1.0.0.0");
		wp_enqueue_style('jquery-ui-theme', plugins_url('/css/jquery-ui.theme.css', __FILE__),[],"1.0.0.0");
	}

	wp_enqueue_style('setrio-bizcalendar-common', plugins_url('/css/common.css', __FILE__),[],"1.0.0.0");
	if ($enableCustomJQueryUI) {
		wp_enqueue_style('setrio-bizcalendar', plugins_url('/css/custom.css', __FILE__),[],"1.0.0.0");
	} else {
		wp_enqueue_style('setrio-bizcalendar', plugins_url('/css/bizcalendar.css', __FILE__),[],"1.0.0.0");
	}
	wp_enqueue_script('setrio-bizcalendar', plugins_url('/js/bizcalendar.js?rqv=' . gmdate("YmdHis"), __FILE__), array('jquery'), "1.0.0.0", false);

	$getMedicalSpecialitiesNonce = wp_create_nonce("getMedicalSpecialities");

	$enableMultipleLocations = (bool)get_option('setrio_bizcal_enable_multiple_locations', false);
	$clinicPhone = get_option('setrio_bizcal_phone', '');
	$clinicEmail = get_option('setrio_bizcal_email', '');
	$allCaps = (bool)get_option('setrio_bizcal_all_caps', false);
	$autoSelectMedicalSpeciality = (bool)get_option('setrio_bizcal_autosel_speciality', true);
	$autoSelectLocation = (bool)get_option('setrio_bizcal_autosel_location', true);
	$autoSelectPaymentType = (bool)get_option('setrio_bizcal_autosel_payment_type', true);
	$autoSelectMedicalService = (bool)get_option('setrio_bizcal_autosel_service', true);
	$autoSelectPhysician = (bool)get_option('setrio_bizcal_autosel_physician', true);
	$allowSearchForPhysician = (bool)get_option('setrio_bizcal_allow_search_physician', true);
	$customDropDownClass = ""; // ($allCaps ? "bizcal-select2-upper" : "");
	$showPhysicianDetails = (bool)get_option('setrio_bizcal_show_physician_details', false);
	$maxAvailabilities = (int)get_option('setrio_bizcal_max_availabilities', 0);
	$minDaysToAppointment = (int)get_option('setrio_bizcal_min_days_to_appointment', 0);
	$appointmentParamOrder = (int)get_option('setrio_bizcal_appointment_param_order', 0);
	$reCaptchaSiteKey = get_option('setrio_bizcal_g_site_key', '');
	wp_localize_script('setrio-bizcalendar', 'setrio_bizcal_ajax', array(
		'ajax_url' => admin_url('admin-ajax.php'),
		// 'ajax_url' => plugins_url('/call.php', __FILE__),
		'enableCustomJQueryUI' => $enableCustomJQueryUI ? 1 : '',
		'enable_multiple_locations' => $enableMultipleLocations,
		'clinic_phone' => $clinicPhone,
		'clinic_email' => $clinicEmail,
		'custom_dropdown_class' => $customDropDownClass,
		'nonce' => $getMedicalSpecialitiesNonce,
		'med_serv_all_caps' => $allCaps,
		'autosel_speciality' => $autoSelectMedicalSpeciality,
		'autosel_location' => $autoSelectLocation,
		'autosel_payment_type' => $autoSelectPaymentType,
		'autosel_service' => $autoSelectMedicalService,
		'autosel_physician' => $autoSelectPhysician,
		'allow_search_physician' => $allowSearchForPhysician,
		'max_availabilities' => $maxAvailabilities,
		'min_days_to_appointment' => $minDaysToAppointment,
		'appointment_param_order' => $appointmentParamOrder,
		'g_site_key' => $reCaptchaSiteKey,
		'msg_error' => setrio_bizcal_message('msgError'),
		'msg_warning' => setrio_bizcal_message('msgWarning'),
		'msg_info' => setrio_bizcal_message('msgInfo'),
		'msg_field_missing' => setrio_bizcal_message('lblFieldMissing'),
		'msg_medical_speciality_placeholder' => setrio_bizcal_message('lblMedicalSpecialityPlaceholder'),
		'msg_location_placeholder' => setrio_bizcal_message('lblLocationPlaceholder'),
		'msg_physician_placeholder' => setrio_bizcal_message('lblPhysicianPlaceholder'),
		'msg_physician_price' => setrio_bizcal_message('lblPhysicianPrice'),
		'msg_medical_service_placeholder' => setrio_bizcal_message('lblMedicalServicePlaceholder'),
		'msg_payment_type_placeholder' => setrio_bizcal_message('lblPaymentTypePlaceholder'),
		'msg_service_unknown_error' => setrio_bizcal_message('msgErrServiceUnknownError'),
		'msg_err_get_appointment_hours' => setrio_bizcal_message('msgErrGetAppointmentHours'),
		'msg_warn_no_available_appointments' => setrio_bizcal_message('msgWarnNoAvailableAppointments'),
		'msg_err_no_available_appointments' => setrio_bizcal_message('msgErrNoAvailableAppointments'),
		'msg_price' => setrio_bizcal_message('lblPrice'),
		'msg_availability_found' => setrio_bizcal_message('lblAvailabilityFound'),
		'msg_select_appointment_time' => setrio_bizcal_message('lblAppointmentTime'),
		'msg_err_request_in_progress' => setrio_bizcal_message('msgErrRequestInProgress'),
		'msg_fld_physician' => setrio_bizcal_message('lblPhysicianField'),
		'msg_fld_service' => setrio_bizcal_message('lblMedicalServiceField'),
		'msg_fld_payment_type' => setrio_bizcal_message('lblPaymentTypeField'),
		'msg_fld_start_time' => setrio_bizcal_message('lblAppointmentTimeStartField'),
		'msg_fld_end_time' => setrio_bizcal_message('lblAppointmentTimeEndField'),
		'msg_fld_first_name' => setrio_bizcal_message('lblFirstNameField'),
		'msg_fld_last_name' => setrio_bizcal_message('lblLastNameField'),
		'msg_fld_phone' => setrio_bizcal_message('lblPhoneField'),
		'msg_fld_phone_not_valid' => setrio_bizcal_message('lblPhoneFieldNotValid'),
		'msg_fld_email_not_valid' => setrio_bizcal_message('lblEmailFieldNotValid'),
		'msg_fld_terms_not_agreed' => setrio_bizcal_message('lblTermsNotAgreed'),
		'msg_fld_data_policy_not_agreed' => setrio_bizcal_message('lblDataPolicyNotAgreed'),
		'msg_fld_recaptcha_not_valid' => setrio_bizcal_message('lblReCaptchaFieldNotValid'),
		'msg_confirm_appointment' => setrio_bizcal_message('lblAppointmentConfirmation'),
		'msg_confirm_appointment_with_location' => setrio_bizcal_message('lblAppointmentConfirmationWithLocation'),
		'msg_request_appointment' => setrio_bizcal_message('btnRequestAppointment'),
		'msg_cancel' => setrio_bizcal_message('btnCancel'),
		'msg_err_appointment_time_missing' => setrio_bizcal_message('msgErrAppointmentTimeMissing'),
		'msg_err_physician_missing' => setrio_bizcal_message('msgErrPhysicianMissing'),
		'msg_any_available_physician' => setrio_bizcal_message('lblAnyAvailablePhysician'),
		'msg_any_available_location' => setrio_bizcal_message('lblAnyAvailableLocation'),
		'speciality_order' => (int)get_option('setrio_bizcal_speciality_order', 0),
		'show_physician_details' => $showPhysicianDetails,
		'plugins_url' => plugins_url("", __FILE__),
	));
}

function setrio_bizcal_init()
{
	global $setrio_bizcal_reCaptcha, $setrio_bizcal_reCaptchaResponse, $setrio_bizcal_reCaptchaSiteKey, $setrio_bizcal_reCaptchaSecretKey;

	$setrio_bizcal_reCaptchaSiteKey = get_option('setrio_bizcal_g_site_key', '');
	$setrio_bizcal_reCaptchaSecretKey = get_option('setrio_bizcal_g_secret_key', '');

	$setrio_bizcal_reCaptchaResponse = null;
	if (($setrio_bizcal_reCaptchaSiteKey) && ($setrio_bizcal_reCaptchaSecretKey))
		$setrio_bizcal_reCaptcha = new BizcalReCaptcha($setrio_bizcal_reCaptchaSecretKey);
	else
		$setrio_bizcal_reCaptcha = null;
}

function setrio_bizcal_ensure_form_is_added()
{
	global $setrio_bizcal_needForm, $setrio_bizcal_needVueForm;
	if($setrio_bizcal_needVueForm){
		echo '<div id="global_stabileste_programare-wrapper">';
		BizCalendar\wp_kses_post(setrio_bizcal_shortcode_vue([
			'type' => 'popup',
			'id' => 'global_stabileste_programare',
			'titlu' => 'stabileste o programare',
			'force' => 1,
			'control' => 0,
		]));
		echo '</div>';
	}
	if($setrio_bizcal_needForm){
		BizCalendar\wp_kses_post(setrio_bizcal_shortcode($atts = [], $content = null, $tag = 'bizcal_hidden'));
	}
}

// AFISARE MODUL IN SITE

function setrio_bizcal_shortcodes_init()
{
	if(is_admin()) return;
	$forceVue = (bool)get_option('setrio_bizcal_force_vue', false === get_option('setrio_bizcal_max_availabilities'));
	$normal_shortcode = $forceVue ? 'setrio_bizcal_shortcode_vue' : 'setrio_bizcal_shortcode';
	add_shortcode('bizcal_detalii_programare', 'setrio_bizcal_detalii_programare_shortcode');
	add_shortcode('bizcal', $normal_shortcode );
	add_shortcode('bizcal_popup', $normal_shortcode );
	add_shortcode('bizcal_hidden', $normal_shortcode );
	add_shortcode('bizcalv', 'setrio_bizcal_shortcode_vue');
	add_shortcode('bizcalv_popup', 'setrio_bizcal_shortcode_vue');
	add_shortcode('bizcalv_hidden', 'setrio_bizcal_shortcode_vue');

	$enableSuccessRedirect = get_option('setrio_bizcal_enable_success_redirect', 0);

	if ($enableSuccessRedirect) {
		$successRedirectPostId = get_option('setrio_bizcal_success_redirect_post_id');

		$url = !empty(get_the_permalink()) ? get_the_permalink() : (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$permalink = strtok($url, '?');

		// get post_id using url/permalink
		$page_id = url_to_postid($url);
		if (!$page_id && isset($_GET['page_id'])) {
			$page_id = (int)$_GET['page_id'];
		}
		$should_redirect = false;

		if (isset($_GET['sba_hash'])) {
			$uuid4 = (string)$_GET['sba_hash'];
			$info = get_transient('setrio_bizcal_appointment_' . $uuid4);
			if (!$info || !is_array($info)) {
				$should_redirect = true;
			}
		} elseif ($page_id && ($page_id == $successRedirectPostId)) {
			$should_redirect = true;
		}

		if ($should_redirect && (!is_home() || isset($_GET['sba_hash']))) {
			wp_redirect(home_url());
			exit;
		}
	}
}

function setrio_bizcal_ics($data = array(), $version = 2)
{
	if(!$data['data_st']) return;
	if(!$data['data_sf']) return;
	$data_cur = new \DateTime('now', new \DateTimeZone("UTC"));
	if(!$data_cur) return;
	$data_st = \DateTime::createFromFormat('d.m.Y H:i', $data['data_st'], new \DateTimeZone('Europe/Bucharest'));
	if(!$data_st) return;
	$data_st->setTimezone(new \DateTimeZone('UTC'));
	$data_sf = \DateTime::createFromFormat('d.m.Y H:i', $data['data_sf'], new \DateTimeZone('Europe/Bucharest'));
	if(!$data_sf) return;
	$data_sf->setTimezone(new \DateTimeZone('UTC'));

	$eol = "\r\n";
	$ical = 'BEGIN:VCALENDAR' . $eol;
	$ical .= 'VERSION:' . $version . '.0' . $eol;
	$ical .= 'PRODID:-//hacksw/handcal//NONSGML v1.0//EN' . $eol;
	$ical .= 'X-MS-OLK-FORCEINSPECTOROPEN:TRUE' . $eol;
	$ical .= 'METHOD:PUBLISH' . $eol;
	$ical .= 'CALSCALE:GREGORIAN' . $eol;
	$ical .= 'BEGIN:VEVENT' . $eol;
	$ical .= 'STATUS:CONFIRMED' . $eol;
	$ical .= 'SEQUENCE:0' . $eol;
	$ical .= 'PRIORITY:5' . $eol;
	$ical .= 'TRANSP:OPAQUE' . $eol;
	$ical .= 'BEGIN:VALARM' . $eol;
	$ical .= 'TRIGGER:-PT1H' . $eol;
	$ical .= 'ACTION:DISPLAY' . $eol;
	$ical .= 'END:VALARM' . $eol;
	$ical .= 'TZID:Europe/Bucharest' . $eol;
	$ical .= 'UID:' . md5(uniqid(wp_rand(), true)) . '@' . addslashes(gethostname()) . $eol;
	$ical .= 'DTSTART:' . $data_st->format('Ymd\THis\Z') . '' . $eol;
	$ical .= 'DTEND:' . $data_sf->format('Ymd\THis\Z') . '' . $eol;
	$ical .= 'DTSTAMP:' . $data_cur->format('Ymd\THis\Z') . '' . $eol;
	$ical .= 'LOCATION:' . trim(addslashes(isset($data['locatia']) ? $data['locatia'] : '')) . $eol;

	$description = setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('msgCalAppointmentDescription'), $data);
	$summary = setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('msgCalAppointmentSummary'), $data);

	$ical .= 'DESCRIPTION:' . htmlspecialchars(trim(preg_replace("/[\r\n]+/", "\\n", $description))) . $eol;
	// $ical .= 'URL;VALUE=URI: http://mydomain.com/events/' . $event['id'] . '' . $eol;
	$ical .= 'SUMMARY:' . htmlspecialchars(trim(preg_replace("/\s+/", " ", $summary))) . $eol;
	$ical .= 'END:VEVENT' . $eol;
	$ical .= 'END:VCALENDAR';

	return $ical;
}
function setrio_bizcal_shortcode($atts = [], $content = null, $tag = '')
{
	global $setrio_bizcal_reCaptchaSiteKey, $setrio_bizcal_reCaptchaSecretKey, $setrio_bizcal_needForm, $setrio_bizcal_formAdded, $setrio_bizcal_assetsAdded, $setrio_bizcal_formIndex;

	$form_default = ($tag == 'bizcal');
	$form_with_popups = ($tag == 'bizcal_popup');
	$form_hidden = ($tag == 'bizcal_hidden');
	
	if(!$setrio_bizcal_assetsAdded){
		$setrio_bizcal_assetsAdded = true;
		setrio_bizcal_enqueue_scripts();
	}

	if (!isset($setrio_bizcal_formAdded))
		$setrio_bizcal_formAdded = false;
	if (!isset($setrio_bizcal_formIndex))
		$setrio_bizcal_formIndex = 0;


	// normalize attribute keys, lowercase
	$atts = array_change_key_case((array)$atts, CASE_LOWER);

	$enableCustomCss = get_option('setrio_bizcal_enable_custom_css', 0);
	if ($enableCustomCss) {
		$customCss = get_option('setrio_bizcal_custom_css', '');
		if ('' !== trim($customCss)) {
			if (!wp_style_is('setrio-bizcal-custom-css')) {
				wp_register_style('setrio-bizcal-custom-css', false);
				wp_enqueue_style('setrio-bizcal-custom-css');
				wp_add_inline_style('setrio-bizcal-custom-css', $customCss);
			}
		}
	}


	// override default attributes with user attributes
	$wporg_atts = shortcode_atts([
		'titlu' => setrio_bizcal_message('txtPopupTitle'),
		'specialitate' => '',
		'locatie' => '',
		'serviciu' => '',
		'medic' => '',
		'calendar' => 'inline',
		'style' => get_option('setrio_bizcal_vue_button_style', ''),
		'class' => get_option('setrio_bizcal_vue_button_class', ''),
		'control' => 'button',
		'container' => '',
	], $atts, $tag);

	$setrio_bizcal_button_title = wp_kses_post($wporg_atts['titlu']);
	$setrio_bizcal_button_style = wp_kses_post($wporg_atts['style']);
	$setrio_bizcal_button_class = wp_kses_post($wporg_atts['class']);
	$setrio_bizcal_button_control = wp_kses_post($wporg_atts['control']);
	$setrio_bizcal_button_container = wp_kses_post($wporg_atts['container']);
	$setrio_bizcal_default_speciality = wp_kses_post($wporg_atts['specialitate']);
	$setrio_bizcal_default_location = wp_kses_post($wporg_atts['locatie']);
	$setrio_bizcal_default_service = wp_kses_post($wporg_atts['serviciu']);
	$setrio_bizcal_default_physician = wp_kses_post($wporg_atts['medic']);
	$setrio_bizcal_seldate_display_mode = wp_kses_post($wporg_atts['calendar']);

	$enableCustomJQueryUI = get_option('setrio_bizcal_enable_custom_jquery_ui');
	$jQueryUIUploadsPath = get_option('setrio_bizcal_jquery_ui_uploads_path');
	$wp_upload_dir = wp_upload_dir($jQueryUIUploadsPath, false);
	$custom_css_file_exists = is_file($wp_upload_dir['path'] . '/setrio-bizcalendar/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css');
	if ($enableCustomJQueryUI && !$custom_css_file_exists) {
		$enableCustomJQueryUI = false;
	}

	$select2ContainerCssClass = 'ui-selectmenu-button';
	if ($enableCustomJQueryUI) {
		$select2ContainerCssClass = 'ui-selectmenu-button ui-selectmenu-button ui-corner-all ui-button';
	}

	$allCaps = (bool)get_option('setrio_bizcal_all_caps', false);
	$enableMultipleLocations = get_option('setrio_bizcal_enable_multiple_locations', false);
	$appointmentParamOrder = (int)get_option('setrio_bizcal_appointment_param_order', 0);

	// start output
	$o = '';

	// Buton programare (varianta cu popup)
	if ($form_with_popups) {
		$setrio_bizcal_needForm = true;
		if ($setrio_bizcal_button_container != "")
			$o .= "\n\t<div class='$setrio_bizcal_button_container'>";
		if ($setrio_bizcal_button_control == "a")
			$o .= "\n\t<a href='#' class='setrio-bizcal-appointment-button"
				. (($setrio_bizcal_button_class) ? " $setrio_bizcal_button_class" : "") . "' "
				. (($setrio_bizcal_button_style) ? "style='$setrio_bizcal_button_style'" : "")
				. "data-speciality='$setrio_bizcal_default_speciality' "
				. "data-location='$setrio_bizcal_default_location' "
				. "data-service='$setrio_bizcal_default_service' "
				. "data-physician='$setrio_bizcal_default_physician'>$setrio_bizcal_button_title</a>";
		else
			$o .= "\n\t<input type='button' class='setrio-bizcal-appointment-button"
				. (($setrio_bizcal_button_class) ? " $setrio_bizcal_button_class" : "") . "' value='$setrio_bizcal_button_title' "
				. (($setrio_bizcal_button_style) ? "style='$setrio_bizcal_button_style'" : "")
				. "data-speciality='$setrio_bizcal_default_speciality' "
				. "data-location='$setrio_bizcal_default_location' "
				. "data-service='$setrio_bizcal_default_service' "
				. "data-physician='$setrio_bizcal_default_physician' />";
		if ($setrio_bizcal_button_container != "")
			$o .= "\n\t</div>";
	}

	if ((($form_default) || (($form_hidden) && (isset($setrio_bizcal_needForm)) && ($setrio_bizcal_needForm)))) {
		$setrio_bizcal_formAdded = true;

		// start box
		$o .= "\n\t<div class=\"bizcal-main-box setrio-bizcal-related\" id=\"setrio-bizcal-component-" . (++$setrio_bizcal_formIndex) . "\">";

		$o .= "\n\t\t<div id='setrio-bizcal-main-box-content'" . (($form_with_popups || $form_hidden) ? " style='display: none'" : "") . ">";

		// Specialitate
		$o .= "\n\t\t\t<div class=\"row\" id=\"setrio-bizcal-page-1\">";
		$o .= "\n\t\t\t\t<div class=\"col-lg-8 col-xl-8 col-md-12 col-sm-12 bizcal-sel-appointment-params-container\">";

		$o .= "\n\t\t\t\t\t<label for=\"bizcal-sel-spec\" class=\"col-12\">" . setrio_bizcal_message('lblMedicalSpeciality') . "<br/>";
		$o .= "\n\t\t\t\t\t\t<select id=\"bizcal-sel-spec\" class=\"bizcal-sel-spec ui-selectmenu-button ui-corner-all\" data-container-css-class=\"$select2ContainerCssClass\" style=\"width: 100%\"></select>";
		$o .= "\n\t\t\t\t\t</label>";

		if ($enableMultipleLocations) {
			$o .= "\n\t\t\t\t\t<label for=\"bizcal-sel-location\" style=\"display: none\" class=\"col-12 bizcal-sel-location-box\">" . setrio_bizcal_message('lblLocation') . "<br/>";
			$o .= "\n\t\t\t\t\t\t<select id=\"bizcal-sel-location\" class=\"bizcal-sel-location ui-selectmenu-button ui-corner-all\" data-container-css-class=\"$select2ContainerCssClass\" style=\"width: 100%\"></select>";
			$o .= "\n\t\t\t\t\t</label>";
		}

		$o .= "\n\t\t\t\t\t<label for=\"bizcal-sel-payment\" style=\"display: none\" class=\"col-12 bizcal-sel-payment-box\">" . setrio_bizcal_message('lblPaymentType') . "<br/>";
		$o .= "\n\t\t\t\t\t\t<select id=\"bizcal-sel-payment\" class=\"bizcal-sel-payment ui-selectmenu-button ui-corner-all\" data-container-css-class=\"$select2ContainerCssClass\" style=\"width: 100%\"></select>";
		$o .= "\n\t\t\t\t\t</label>";

		if ($appointmentParamOrder == 0) {
			$o .= "\n\t\t\t\t\t<label for=\"bizcal-sel-serv\" style=\"display: none\" class=\"col-12 bizcal-sel-serv-box\">" . setrio_bizcal_message('lblMedicalService');
			$o .= "\n\t\t\t\t\t\t<select id=\"bizcal-sel-serv\" class=\"bizcal-sel-serv ui-selectmenu-button ui-corner-all\" data-container-css-class=\"$select2ContainerCssClass\" style=\"width: 100%\"></select>";
			$o .= "\n\t\t\t\t\t</label>";

			$o .= "\n\t\t\t\t\t<label for=\"bizcal-sel-preferred-physician\" style=\"display: none\" class=\"col-12 bizcal-sel-preferred-physician-box\">" . setrio_bizcal_message('lblPreferredPhysician');
			$o .= "\n\t\t\t\t\t\t<select id=\"bizcal-sel-preferred-physician\" class=\"bizcal-sel-preferred-physician ui-selectmenu-button ui-corner-all\" data-container-css-class=\"$select2ContainerCssClass\" style=\"width: 100%\"></select>";
			$o .= "\n\t\t\t\t\t</label>";
		} else {
			$o .= "\n\t\t\t\t\t<label for=\"bizcal-sel-preferred-physician\" style=\"display: none\" class=\"col-12 bizcal-sel-preferred-physician-box\">" . setrio_bizcal_message('lblPreferredPhysician');
			$o .= "\n\t\t\t\t\t\t<select id=\"bizcal-sel-preferred-physician\" class=\"bizcal-sel-preferred-physician ui-selectmenu-button ui-corner-all\" data-container-css-class=\"$select2ContainerCssClass\" style=\"width: 100%\"></select>";
			$o .= "\n\t\t\t\t\t</label>";

			$o .= "\n\t\t\t\t\t<label for=\"bizcal-sel-serv\" style=\"display: none\" class=\"col-12 bizcal-sel-serv-box\">" . setrio_bizcal_message('lblMedicalService');
			$o .= "\n\t\t\t\t\t\t<select id=\"bizcal-sel-serv\" class=\"bizcal-sel-serv ui-selectmenu-button ui-corner-all\" data-container-css-class=\"$select2ContainerCssClass\" style=\"width: 100%\"></select>";
			$o .= "\n\t\t\t\t\t</label>";
		}

		$o .= "\n\t\t\t\t</div>"; // col

		$o .= "\n\t\t\t\t<div class=\"col-lg-4 col-xl-4 col-md-12 col-sm-12 bizcal-sel-appointment-date-container\">";
		$o .= "\n\t\t\t\t\t<label for=\"bizcal-sel-date\">" . setrio_bizcal_message('lblAppointmentDate');
		if ($setrio_bizcal_seldate_display_mode == 'inline')
			$o .= "\n\t\t\t\t\t\t<div id=\"bizcal-sel-date\" class=\"bizcal-datepicker\"></div>";
		else
			$o .= "\n\t\t\t\t\t\t<input type=\"text\" id=\"bizcal-sel-date\" class=\"text ui-widget-content ui-corner-all\" />";
		$o .= "\n\t\t\t\t\t</label>";
		if ($form_with_popups || $form_hidden) {
			$o .= "\n\t\t\t\t\t<div class=\"bizcal-sel-time-loading\" style=\"display: none\">";
			$o .= "\n\t\t\t\t\t\t<img src=\"" . plugins_url('/css/images/ajax-loader.gif', __FILE__) . "\">";
			$o .= "\n\t\t\t\t\t\t<span class=\"bizcal-sel-time-loading-text\">" . setrio_bizcal_message('lblCheckingAvailability') . "</span>";
			$o .= "\n\t\t\t\t\t</div>";
		}

		$o .= "\n\t\t\t\t</div>"; // col

		$o .= "\n\t\t\t</div>"; // row

		$o .= "\n\t\t\t\t<div class=\"w-100\"></div>";

		$o .= "\n\t\t\t<div class=\"row\" id=\"setrio-bizcal-page-2\">";

		$o .= "\n\t\t\t\t<div id=\"bizcal-select-time-form\" class=\"col-xl-12 col-lg-12 col-md-12 col-sm-12\">";
		$o .= "\n\t\t\t\t\t<div class=\"bizcal-sel-time-loading\" style=\"display: none\">";
		$o .= "\n\t\t\t\t\t\t<img src=\"" . plugins_url('/css/images/ajax-loader.gif', __FILE__) . "\">";
		$o .= "\n\t\t\t\t\t\t<span class=\"bizcal-sel-time-loading-text\">" . setrio_bizcal_message('lblCheckingAvailability') . "</span>";
		$o .= "\n\t\t\t\t\t</div>";
		$o .= "\n\t\t\t\t\t<div id=\"bizcal-sel-time-container\" style=\"display: none\">";
		$o .= "\n\t\t\t\t\t\t<label id=\"bizcal-sel-med-label\" style=\"display: none\" for=\"bizcal-sel-med\">" . setrio_bizcal_message('lblPhysician');
		$o .= "\n\t\t\t\t\t\t\t<select id=\"bizcal-sel-med\" class=\"bizcal-sel-med\" style=\"width: 100%\"></select>";
		$o .= "\n\t\t\t\t\t\t</label>";
		$o .= "\n\t\t\t\t\t\t<input type=\"hidden\" name=\"physician-uid-auto\" id=\"bizcal-ra-physician-uid-auto\" value=\"0\">";
		$o .= "\n\t\t\t\t\t\t<span id=\"bizcal-sel-time-physician\" style=\"display: none\"></span>";
		$o .= "\n\t\t\t\t\t\t<span id=\"bizcal-sel-time-service\" style=\"display: none\"></span>";
		$o .= "\n\t\t\t\t\t\t<label for=\"bizcal-sel-time\" id=\"bizcal-sel-time-label\" style=\"display: block; width: 100%\">" . setrio_bizcal_message('lblAppointmentTime') . "</label>";
		$o .= "\n\t\t\t\t\t\t<ul id=\"bizcal-sel-time\" class=\"bizcal-sel-time\">";
		$o .= "\n\t\t\t\t\t\t\t<li value=\"0\">-</li>";
		$o .= "\n\t\t\t\t\t\t</ul>";
		$o .= "\n\t\t\t\t\t</div>";
		$o .= "\n\t\t\t\t</div>"; // col
		$o .= "\n\t\t\t</div>"; // row

		$o .= "\n\t\t\t\t<div class=\"w-100\"></div>";

		$o .= "\n\t\t\t<div class=\"row\" id=\"setrio-bizcal-page-3\">";
		$o .= "\n\t\t\t\t<div class=\"col-xl-12 col-lg-12 col-md-12 col-sm-12\">";

		$o .= "\n\t\t\t\t\t<div id=\"bizcal-register-appointment-form\">";

		$o .= "\n\t\t\t\t\t\t<p class=\"bizcal-register-appointment-form-validate-tips\" style=\"display: none\"></p>";
		$o .= "\n\t\t\t\t\t\t<form>";
		$o .= "\n\t\t\t\t\t\t\t<fieldset>";
		$o .= "\n\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"physician-uid\" id=\"bizcal-ra-physician-uid\" value=\"\">";
		$o .= "\n\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"service-uid\" id=\"bizcal-ra-service-uid\" value=\"\">";
		$o .= "\n\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"payment-type-id\" id=\"bizcal-ra-payment-type-id\" value=\"\">";
		$o .= "\n\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"start-date\" id=\"bizcal-ra-start-date\" value=\"\">";
		$o .= "\n\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"end-date\" id=\"bizcal-ra-end-date\" value=\"\">";
		$o .= "\n\t\t\t\t\t\t\t\t<div class=\"bizcal-ra-first-name-container\">";
		$o .= "\n\t\t\t\t\t\t\t\t<label class=\"bizcal-ra-first-name-caption\" for=\"bizcal-ra-first-name\">" . setrio_bizcal_message('lblPatientLastName') . "</label>";
		$o .= "\n\t\t\t\t\t\t\t\t<input type=\"text\" name=\"last-name\" id=\"bizcal-ra-last-name\" value=\"\" class=\"text ui-widget-content ui-corner-all\">";
		$o .= "\n\t\t\t\t\t\t\t\t</div>";
		$o .= "\n\t\t\t\t\t\t\t\t<div class=\"bizcal-ra-last-name-container\">";
		$o .= "\n\t\t\t\t\t\t\t\t<label class=\"bizcal-ra-last-name-caption\" for=\"bizcal-ra-last-name\">" . setrio_bizcal_message('lblPatientFirstName') . "</label>";
		$o .= "\n\t\t\t\t\t\t\t\t<input type=\"text\" name=\"first-name\" id=\"bizcal-ra-first-name\" value=\"\" class=\"text ui-widget-content ui-corner-all\">";
		$o .= "\n\t\t\t\t\t\t\t\t</div>";
		$o .= "\n\t\t\t\t\t\t\t\t<div class=\"bizcal-ra-phone-container\">";
		$o .= "\n\t\t\t\t\t\t\t\t<label class=\"bizcal-ra-phone-caption\" for=\"bizcal-ra-phone\">" . setrio_bizcal_message('lblPatientPhone') . "</label>";
		$o .= "\n\t\t\t\t\t\t\t\t<input type=\"text\" name=\"phone\" id=\"bizcal-ra-phone\" value=\"\" class=\"text ui-widget-content ui-corner-all\">";
		$o .= "\n\t\t\t\t\t\t\t\t</div>";
		$o .= "\n\t\t\t\t\t\t\t\t<div class=\"bizcal-ra-email-container\">";
		$o .= "\n\t\t\t\t\t\t\t\t<label class=\"bizcal-ra-email-caption\" for=\"bizcal-ra-email\">" . setrio_bizcal_message('lblPatientEmail') . "</label>";
		$o .= "\n\t\t\t\t\t\t\t\t<input type=\"text\" name=\"email\" id=\"bizcal-ra-email\" value=\"\" class=\"text ui-widget-content ui-corner-all\">";
		$o .= "\n\t\t\t\t\t\t\t\t</div>";
		$o .= "\n\t\t\t\t\t\t\t\t<div class=\"bizcal-ra-observations-container\">";
		$o .= "\n\t\t\t\t\t\t\t\t<label class=\"bizcal-ra-observations-caption\" for=\"bizcal-ra-observations\">" . setrio_bizcal_message('lblPatientObservations') . "</label>";
		$o .= "\n\t\t\t\t\t\t\t\t<textarea name=\"observations\" id=\"bizcal-ra-observations\""
			. " class=\"col-xl-12 col-lg-12 col-md-12 col-sm-12 text ui-widget-content ui-corner-all\" style=\"width: 100%\"></textarea>";
		$o .= "\n\t\t\t\t\t\t\t\t</div>";

		$o .= "\n\t\t\t\t\t\t\t\t<a href=\"https://www.setrio.ro/bizmedica-clinici-medicale\" class=\"bizcal-bme-logo\" target=\"_blank\">&nbsp;</a>";

		if (($setrio_bizcal_reCaptchaSiteKey) && ($setrio_bizcal_reCaptchaSecretKey))
			$o .= "\n\t\t\t\t\t\t\t\t<div class=\"zzzg-recaptcha\" id=\"bizcal-g-recaptcha\" style=\"margin-top: 0.5em\" data-sitekey=\"$setrio_bizcal_reCaptchaSiteKey\"></div>";

		$enableTerms = get_option('setrio_bizcal_enable_terms', 1);
		if ($enableTerms) {
			$termsPostId = get_option('setrio_bizcal_terms_post_id', get_option('wp_page_for_privacy_policy'));
			$link = get_option('setrio_bizcal_terms_link');
			if ($termsPostId && ('' === '' . $link)) {
				$link = get_the_permalink($termsPostId);
			}
			$title = setrio_bizcal_message('lblTermsText');
			if (trim($title) === '' && $termsPostId) {
				$title = get_the_title($termsPostId);
			}
			$lblTerms = setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('lblTerms'), array(
				'link' => $link,
				'titlu' => $title,
			));

			if (setrio_bizcal_message('lblTerms') === $lblTerms) {

				if ('' !== trim($link)) {
					$lblTerms .= ' <a target="_BLANK" href="' . esc_attr($link) . '">';
					$lblTerms .= $title;
					$lblTerms .= '</a>';
				}
			}
			$o .= "\n\t\t\t\t\t\t\t\t<div class=\"bizcal-ra-terms-container\">";
			$o .= "\n\t\t\t\t\t\t\t\t<label class=\"bizcal-ra-terms-caption checkbox\" for=\"bizcal-ra-terms\">";
			$o .= "<input type=\"checkbox\" name=\"terms\" id=\"bizcal-ra-terms\" value=\"1\" /> " . $lblTerms . "</label>";
			$o .= "\n\t\t\t\t\t\t\t\t</div>";
		}
		$enableDataPolicy = get_option('setrio_bizcal_enable_data_policy', 1);
		if ($enableDataPolicy) {
			$dataPolicyPostId = get_option('setrio_bizcal_data_policy_post_id');
			$link = get_option('setrio_bizcal_data_policy_link');
			if ($dataPolicyPostId && ('' === '' . $link)) {
				$link = get_the_permalink($dataPolicyPostId);
			}
			$title = setrio_bizcal_message('lblDataPolicyText');
			if (trim($title) === '' && $dataPolicyPostId) {
				$title = get_the_title($dataPolicyPostId);
			}
			$lblDataPolicy = setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('lblDataPolicy'), array(
				'link' => $link,
				'titlu' => $title,
			));

			if (setrio_bizcal_message('lblDataPolicy') === $lblDataPolicy) {
				if ('' !== trim($link)) {
					$lblDataPolicy .= ' <a target="_BLANK" href="' . esc_attr($link) . '">';
					$lblDataPolicy .= $title;
					$lblDataPolicy .= '</a>';
				}
			}
			$o .= "\n\t\t\t\t\t\t\t\t<div class=\"bizcal-ra-data-policy-container\">";
			$o .= "\n\t\t\t\t\t\t\t\t<label class=\"bizcal-ra-data-policy-caption checkbox\" for=\"bizcal-ra-data-policy\">";
			$o .= "<input type=\"checkbox\" name=\"data_policy\" id=\"bizcal-ra-data-policy\" value=\"1\" /> " . $lblDataPolicy . "</label>";
			$o .= "\n\t\t\t\t\t\t\t\t</div>";
		}
		$enableNewsletter = get_option('setrio_bizcal_enable_newsletter', 1);
		if ($enableNewsletter) {
			$lblNewsletter = setrio_bizcal_message('lblNewsletter');
			$o .= "\n\t\t\t\t\t\t\t\t<div class=\"bizcal-ra-newsletter-container\">";
			$o .= "\n\t\t\t\t\t\t\t\t<label class=\"bizcal-ra-newsletter-caption checkbox\" for=\"bizcal-ra-newsletter\">";
			$o .= "<input type=\"checkbox\" name=\"newsletter\" id=\"bizcal-ra-newsletter\" value=\"1\" /> " . $lblNewsletter . "</label>";
			$o .= "\n\t\t\t\t\t\t\t\t</div>";
		}

		$o .= "\n\t\t\t\t\t\t\t\t<button type=\"submit\" id=\"bizcal-register-appointment-button\" class=\"ui-corner-all ui-button\" style=\"margin-top: 0.5em\">" . setrio_bizcal_message('btnRequestAppointment') . "</button>";

		$o .= "\n\t\t\t\t\t\t\t</fieldset>";
		$o .= "\n\t\t\t\t\t\t</form>";
		$o .= "\n\t\t\t\t\t</div>"; // bizcal-register-appointment-form
		$o .= "\n\t\t\t\t</div>"; // col
		$o .= "\n\t\t\t</div>"; // row
		$o .= "\n\t\t</div>"; // setrio-bizcal-main-box-content
		$o .= "\n\t</div>"; // setrio-bizcal-main-box

		$o .= "\n\t<div id=\"bizcal-info-dialog\" style=\"display: none\"></div>";
		$o .= "\n\t<div id=\"bizcal-warning-dialog\" style=\"display: none\"></div>";
		$o .= "\n\t<div id=\"bizcal-error-dialog\" style=\"display: none\"></div>";
	}

	// enclosing tags
	if (!is_null($content)) {
		// secure output by executing the_content filter hook on $content
		$o .= apply_filters('the_content', $content);

		// run shortcode parser recursively
		$o .= do_shortcode($content);
	}

	// return output
	return $o;
}

function setrio_gmdate(){
	$args = func_get_args();
	/* if(isset($args[1])){
		echo date("Z");
		die;
	} */
	return gmdate(...$args);
}
function setrio_bizcal_shortcode_vue($atts = [], $content = null, $tag = '')
{
	global $setrio_bizcal_shortcode_vue_loaded_scripts, $setrio_bizcal_needVueForm;
	global $setrio_bizcal_shortcode_vue_form_index;
	if(!$setrio_bizcal_shortcode_vue_form_index){
		$setrio_bizcal_shortcode_vue_form_index = 1;
	} else {
		$setrio_bizcal_shortcode_vue_form_index++;
	}
	$setrio_bizcal_needVueForm = true;
	if(!$setrio_bizcal_shortcode_vue_loaded_scripts){
		$plugin_version = '1.1.0.31';
		
		$setrio_bizcal_shortcode_vue_loaded_scripts = 1;
		wp_enqueue_style('setrio-bizcalendar-material',plugins_url('/vendor/materialdesign/css/materialdesignicons.min.css', __FILE__),[],"1.0.0.0");
		wp_enqueue_script('setrio-bizcal-vue', plugins_url('/vendor/vue/vue-2.6.12.js?sbcv=' . $plugin_version, __FILE__),[],"1.0.0.0");
		wp_enqueue_script('setrio-bizcal-vuex', plugins_url('/vendor/vue/vuex-3.6.0.js?sbcv=' . $plugin_version, __FILE__), array('setrio-bizcal-vue'),[],"1.0.0.0");
		wp_enqueue_style('setrio-bizcalendar-vuetify',plugins_url('/vendor/vuetify/vuetify.css?sbcv=' . $plugin_version, __FILE__), array(), $plugin_version);
		wp_enqueue_script('setrio-bizcalendar-vuetify', plugins_url('/vendor/vuetify/vuetify.js?sbcv=' . $plugin_version, __FILE__), array('setrio-bizcal-vue'), $plugin_version, false);
		wp_enqueue_script('setrio-bizcalendar-recaptcha', 'https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit', array('setrio-bizcal-vue'),"1.0.0.0");
		wp_enqueue_script('setrio-bizcalendar-vue-recaptcha', plugins_url('/vendor/recaptcha/vue-recaptcha.min.js?sbcv=' . $plugin_version, __FILE__), array('setrio-bizcalendar-recaptcha'), $plugin_version, false);

		wp_enqueue_script('setrio-bizcal-axios', plugins_url('/vendor/axios/axios.min.js?sbcv=' . $plugin_version, __FILE__), array('setrio-bizcal-vue'), '1.0.0.0', true);
		
		wp_enqueue_style('setrio-bizcalendar-vue', plugins_url('/css/bizcalendar-vue.css?sbcv=' . $plugin_version, __FILE__), array(), $plugin_version);
		wp_enqueue_script('setrio-bizcalendar-vue-functions', plugins_url('/js/bizcalendarvuefunctions.js?sbcv=' . $plugin_version, __FILE__), array('jquery', 'setrio-bizcal-vue'), $plugin_version, false);
		
		wp_enqueue_script('setrio-bizcalendar-vue', plugins_url('/js/bizcalendarvue.js?sbcv=' . $plugin_version, __FILE__), array('jquery'), $plugin_version, false);
		
		$enableCustomCss = get_option('setrio_bizcal_enable_custom_css', 0);
		if ($enableCustomCss) {
			$customCss = get_option('setrio_bizcal_custom_css', '');
			if ('' !== trim($customCss)) {
				if (!wp_style_is('setrio-bizcal-custom-css')) {
					wp_register_style('setrio-bizcal-custom-css', false);
					wp_enqueue_style('setrio-bizcal-custom-css');
					wp_add_inline_style('setrio-bizcal-custom-css', $customCss);
				}
			}
		}
	}
	
	// override default attributes with user attributes
	$wporg_atts = shortcode_atts([
		'type' => in_array($tag,array('bizcal','bizcalv')) ? get_option('setrio_bizcal_vue_inline_template','inline') : (in_array($tag,array('bizcalv_popup','bizcal_popup')) ? get_option('setrio_bizcal_vue_popup_template','popup') : 'hidden'),
		
		'titlu' => setrio_bizcal_message('txtPopupTitle'),
		'title' => null,
		
		'specialitate' => null,
		'locatie' => null,
		'serviciu' => null,
		'medic' => null,
		'data' => null,
		'plata' => null,
		
		'speciality' => null,
		'location' => null,
		'service' => null,
		'physician' => null,
		'date' => '',
		'payment_type' => null,
		
		// 'firstname' => 'Tudor',
		// 'lastname' => 'Chirvasa',
		// 'email' => 'tudor.chirvasa@lisal.ro',
		// 'phone' => '0771255279',
		'firstname' => null,
		'lastname' => null,
		'email' => null,
		'phone' => null,
		'observations' => null,
		
		'speciality_key' => null,
		'location_key' => null,
		'service_key' => null,
		'physician_key' => null,
		'payment_type_key' => null,
		'calendar' => get_option('setrio_bizcal_vue_calendar_type','inline'),
		'content_pos' => 0,
		
		'dialog' => false,
		'terms' => false,
		'data_policy' => false,
		'newsletter' => false,
		
		'autosel_speciality' => get_option('setrio_bizcal_autosel_speciality', true),
		'autosel_location' => get_option('setrio_bizcal_autosel_location', true),
		'autosel_service' => get_option('setrio_bizcal_autosel_service', true),
		'autosel_payment_type' => get_option('setrio_bizcal_autosel_payment_type', true),
		'autosel_physician' => get_option('setrio_bizcal_autosel_physician', true),
		
		'allow_search_physician' => get_option('setrio_bizcal_allow_search_physician', true),
		'appointment_param_order' => (int)get_option('setrio_bizcal_appointment_param_order', 0),
		'max_availabilities' => (int)get_option('setrio_bizcal_max_availabilities', 0),
		'min_days_to_appointment' => (int)get_option('setrio_bizcal_min_days_to_appointment', 0),
		'all_caps' => (bool)get_option('setrio_bizcal_all_caps', false),
		'enable_multiple_locations' => (bool)get_option('setrio_bizcal_enable_multiple_locations', false),
		'button_style' => '' !== $content ? 'custom' : 'normal',
		
		'style' => get_option('setrio_bizcal_vue_button_style', ''),
		'class' => get_option('setrio_bizcal_vue_button_class', 'button'),
		'control' => get_option('setrio_bizcal_vue_button_type', 'button'),
		'container' => '',
		
		'step' => 1,
		'force' => false,
		'txt_step_1' => setrio_bizcal_message('lblStep1'),
		'txt_step_2' => setrio_bizcal_message('lblStep2'),
		'txt_step_3' => setrio_bizcal_message('lblStep3'),
		'txt_step_4' => setrio_bizcal_message('lblStep4'),
		'txt_any_physician' => setrio_bizcal_message('lblAnyAvailablePhysician'),
		'txt_any_location' => setrio_bizcal_message('lblAnyAvailableLocation'),
		'txt_speciality' => setrio_bizcal_message('lblMedicalSpeciality'),
		'txt_speciality_placeholder' => setrio_bizcal_message('lblMedicalSpecialityPlaceholder'),
		'txt_no_specialities' => setrio_bizcal_message('txtNoSpecialities'),
		'txt_location' => setrio_bizcal_message('lblLocation'),
		'txt_location_placeholder' => setrio_bizcal_message('lblLocationPlaceholder'),
		'txt_no_locations' => setrio_bizcal_message('txtNoLocations'),
		'txt_payment_type' => setrio_bizcal_message('lblPaymentType'),
		'txt_payment_type_placeholder' => setrio_bizcal_message('lblPaymentTypePlaceholder'),
		'txt_no_payment_types' => setrio_bizcal_message('txtNoPaymentMethods'),
		'txt_service' => setrio_bizcal_message('lblMedicalService'),
		'txt_service_placeholder' => setrio_bizcal_message('lblMedicalServicePlaceholder'),
		'txt_no_services' => setrio_bizcal_message('txtNoServices'),
		'txt_physician' => setrio_bizcal_message('lblPhysician'),
		'txt_physician_placeholder' => setrio_bizcal_message('lblPhysicianPlaceholder'),
		'txt_no_physicians' => setrio_bizcal_message('txtNoPhysicians'),
		'txt_no_availabilities' => setrio_bizcal_message('txtNoAvailableAppointments'),
	], $atts, $tag);
	/* 
	// $wporg_atts = shortcode_atts([
		// 'specialitate' => 'MEDICINA DE FAMILIE',
		// 'locatie' => 'Bucuresti Preciziei',
		// 'serviciu' => '',
		// 'medic' => '',
		// 'data' => 'tomorrow',
		// 'plata' => 'Numerar/Card',
	// ], $atts, $tag);
	// $wporg_atts = shortcode_atts([
		// 'specialitate' => 'GASTROENTEROLOGIE',
		// 'locatie' => 'Bucuresti Preciziei',
		// 'serviciu' => 'COLONOSCOPIE',
		// 'medic' => '',
		// 'data' => 'today',
		// 'plata' => 'Numerar/Card',
	// ], $atts, $tag);
	// $wporg_atts = shortcode_atts([
		// 'type' => in_array($tag,array('bizcal','bizcalv')) ? 'inline' : (in_array($tag,array('bizcalv_popup','bizcal_popup')) ? 'popup' : 'hidden'),
		// 'specialitate' => 'ENDOCRINOLOGIE',
		// 'locatie' => 'Bucuresti Preciziei',
		// 'serviciu' => 'Consult endocrinologie',
		// 'medic' => 'CORNELIA ENDOCRINOLOGIE',
		// 'data' => 'today',
		// 'plata' => 'Numerar/Card',
	// ], $atts, $tag);
	// $wporg_atts = shortcode_atts([
		// 'specialitate' => 'MEDICINA DE FAMILIE',
		// 'locatie' => 'Ploiesti',
		// 'serviciu' => 'control med fam',
		// 'medic' => 'ADRIAN MF',
		// 'data' => 'today',
		// 'plata' => 'Bilet de trimitere',
	// ], $atts, $tag);
 */
	$wporg_atts['title'] = isset($wporg_atts['titlu']) ? $wporg_atts['titlu'] : (isset($wporg_atts['title']) ? trim($wporg_atts['title']) : null);
	unset($wporg_atts['titlu']);
	$wporg_atts['speciality'] = isset($wporg_atts['specialitate']) ? trim($wporg_atts['specialitate']) : (isset($wporg_atts['speciality']) ? $wporg_atts['speciality'] : null);
	unset($wporg_atts['specialitate']);
	$wporg_atts['location'] = isset($wporg_atts['locatie']) ? trim($wporg_atts['locatie']) : (isset($wporg_atts['location']) ? $wporg_atts['location'] : null);
	unset($wporg_atts['locatie']);
	$wporg_atts['service'] = isset($wporg_atts['serviciu']) ? trim($wporg_atts['serviciu']) : (isset($wporg_atts['service']) ? $wporg_atts['service'] : null);
	unset($wporg_atts['serviciu']);
	$wporg_atts['physician'] = isset($wporg_atts['medic']) ? trim($wporg_atts['medic']) : (isset($wporg_atts['physician']) ? $wporg_atts['physician'] : null);
	unset($wporg_atts['medic']);
	$wporg_atts['min_date'] = isset($wporg_atts['min_date']) ? trim($wporg_atts['min_date']) : '+' . (ceil(get_option('setrio_bizcal_min_days_to_appointment', 0)*24)) . ' hours';
	$wporg_atts['min_date_base'] = $wporg_atts['min_date'];
	$wporg_atts['min_date'] = $wporg_atts['min_date'] ? (new DateTime($wporg_atts['min_date']))->format('Y-m-d') : null;
	$wporg_atts['date'] = isset($wporg_atts['data']) ? trim($wporg_atts['data']) : (isset($wporg_atts['date']) ? $wporg_atts['date'] : null);
	unset($wporg_atts['data']);
	$wporg_atts['date_base'] = $wporg_atts['date'] ? $wporg_atts['date'] : $wporg_atts['min_date_base'];
	$wporg_atts['date'] = $wporg_atts['date'] ? (new DateTime($wporg_atts['date']))->format('Y-m-d') : $wporg_atts['min_date'];
	$wporg_atts['payment_type'] = isset($wporg_atts['plata']) ? trim($wporg_atts['plata']) : (isset($wporg_atts['payment_type']) ? $wporg_atts['payment_type'] : null);
	$wporg_atts['terms'] = filter_var($wporg_atts['terms'],FILTER_VALIDATE_BOOLEAN,FILTER_NULL_ON_FAILURE);
	$wporg_atts['dialog'] = filter_var($wporg_atts['dialog'],FILTER_VALIDATE_BOOLEAN,FILTER_NULL_ON_FAILURE);
	$wporg_atts['data_policy'] = filter_var($wporg_atts['data_policy'],FILTER_VALIDATE_BOOLEAN,FILTER_NULL_ON_FAILURE);
	$wporg_atts['newsletter'] = filter_var($wporg_atts['newsletter'],FILTER_VALIDATE_BOOLEAN,FILTER_NULL_ON_FAILURE);
	$wporg_atts['autosel_speciality'] = filter_var($wporg_atts['autosel_speciality'],FILTER_VALIDATE_BOOLEAN,FILTER_NULL_ON_FAILURE);
	$wporg_atts['autosel_location'] = filter_var($wporg_atts['autosel_location'],FILTER_VALIDATE_BOOLEAN,FILTER_NULL_ON_FAILURE);
	$wporg_atts['autosel_service'] = filter_var($wporg_atts['autosel_service'],FILTER_VALIDATE_BOOLEAN,FILTER_NULL_ON_FAILURE);
	$wporg_atts['autosel_payment_type'] = filter_var($wporg_atts['autosel_payment_type'],FILTER_VALIDATE_BOOLEAN,FILTER_NULL_ON_FAILURE);
	$wporg_atts['autosel_physician'] = filter_var($wporg_atts['autosel_physician'],FILTER_VALIDATE_BOOLEAN,FILTER_NULL_ON_FAILURE);
	$wporg_atts['allow_search_physician'] = filter_var($wporg_atts['allow_search_physician'],FILTER_VALIDATE_BOOLEAN,FILTER_NULL_ON_FAILURE);
	$wporg_atts['show_physician_details'] = filter_var(get_option('setrio_bizcal_show_physician_details', false),FILTER_VALIDATE_BOOLEAN,FILTER_NULL_ON_FAILURE);
	$wporg_atts['nonce'] = wp_create_nonce("getMedicalSpecialities");
	$wporg_atts['ajax_url'] = admin_url('admin-ajax.php');
	$wporg_atts['call_url'] = plugins_url('/call.php', __FILE__);
	
	$wporg_atts['force'] = 'popup' != $wporg_atts['type'] ? true : (!isset($wporg_atts['force']) ? $setrio_bizcal_button_control != 'vue' : filter_var($wporg_atts['force'],FILTER_VALIDATE_BOOLEAN,FILTER_NULL_ON_FAILURE));
	
	$output_vue = !empty($wporg_atts['force']);
	
	$enableCustomVue = get_option('setrio_bizcal_enable_custom_vue');
	$VUEUploadsUrl = plugins_url('/js/vue-params.jsonp', __FILE__);
	
	if($enableCustomVue){
		$VUEUploadsPath = get_option('setrio_bizcal_vue_uploads_path');
		$VUEUploadsFile = get_option('setrio_bizcal_vue_params_file');
		if($VUEUploadsFile){
			$vue_wp_upload_dir = wp_upload_dir($VUEUploadsPath, false);
			if(is_file($vue_wp_upload_dir['path'] . '/' . $VUEUploadsFile)){
				$VUEUploadsUrl = $vue_wp_upload_dir['url'] . '/' . $VUEUploadsFile;
			}
		}
	}
	
	$wporg_atts['config_file'] = isset($wporg_atts['config_file']) ? trim($wporg_atts['config_file']) : $VUEUploadsUrl;
	
	$forceAdminAjax = (bool)get_option('setrio_bizcal_force_adminajax', false);
	if($forceAdminAjax){
		$wporg_atts['call_url'] = $wporg_atts['ajax_url'];
	}
	
	$wporg_atts['autosel'] = array();
	$wporg_atts['texts'] = array();
	foreach($wporg_atts as $k=>$v){
		if(0 === strpos($k,'txt_')){
			$wporg_atts['texts'][$k] = $v;
		} elseif(0 === strpos($k,'autosel_')){
			$wporg_atts['autosel'][substr($k,8)] = $v;
		}
	}
	
	if(isset($wporg_atts['service'])){
		$wporg_atts['payment_type_key'] = 'value';
		$wporg_atts['payment_type'] = 1;
		$wporg_atts['autosel_speciality'] = 1;
		if($wporg_atts['enable_multiple_locations']){
			$wporg_atts['autosel_location'] = 1;
		}
	}
	// echo '<pre>';
	// print_r($wporg_atts);;
	// die;
	$setrio_bizcal_button_style = wp_kses_post($wporg_atts['style']);
	$setrio_bizcal_button_class = wp_kses_post($wporg_atts['class']);
	$setrio_bizcal_button_control = wp_kses_post($wporg_atts['control']);
	
	if('vue' === $setrio_bizcal_button_control){
		$wporg_atts['button_style'] = 'vue';
	}
	
	$setrio_bizcal_button_container = wp_kses_post($wporg_atts['container']);
	ob_start();
	?>
	<div class="setrio setrio-bizcal setrio-bizcal-related bizcal-vue bizcal-vue-<?php echo esc_attr($wporg_atts['type']); ?>">
	<?php if (-2 == $wporg_atts['content_pos']) BizCalendar\wp_kses_post($content); ?>
	<?php if ($wporg_atts['type'] === 'popup'){ ?>
	<?php if ($wporg_atts['button_style'] === 'normal'){ 
		if (!empty($setrio_bizcal_button_container)){ ?>
		<div class="<?php echo esc_attr($setrio_bizcal_button_container); ?>">
		<?php 
		}
	?>
	<?php switch ($setrio_bizcal_button_control){
		case 'a': ?>
	<a href="javascript:void(0);" class="setrio-bizcal-appointment-button <?php echo esc_attr($setrio_bizcal_button_class); ?>" style="<?php echo esc_attr($setrio_bizcal_button_style); ?>" onclick="SetrioBizCalendarVueModal(<?php if($output_vue) { ?>this<?php } else { ?>document.querySelector('#global_stabileste_programare-wrapper > .setrio').dataset.setrioVueIndex, <?php echo esc_attr(json_encode($wporg_atts)) ?><?php } ?>)"><?php echo wp_kses_post($wporg_atts['title']); ?></a>
	<?php break;
		case 'input': ?>
	<input type="button" class="setrio-bizcal-appointment-button <?php echo esc_attr($setrio_bizcal_button_class); ?>" style="<?php echo esc_attr($setrio_bizcal_button_style); ?>" onclick="SetrioBizCalendarVueModal(<?php if($output_vue) { ?>this<?php } else { ?>document.querySelector('#global_stabileste_programare-wrapper > .setrio').dataset.setrioVueIndex, <?php echo esc_attr(json_encode($wporg_atts)) ?><?php } ?>)" value="<?php echo esc_attr($wporg_atts['title']); ?>"/>
	<?php break;
		default: ?>
	<button type="button" class="setrio-bizcal-appointment-button <?php echo esc_attr($setrio_bizcal_button_class); ?>" style="<?php echo esc_attr($setrio_bizcal_button_style); ?>" onclick="SetrioBizCalendarVueModal(<?php if($output_vue) { ?>this<?php } else { ?>document.querySelector('#global_stabileste_programare-wrapper > .setrio').dataset.setrioVueIndex, <?php echo esc_attr(json_encode($wporg_atts)) ?><?php } ?>)"><?php echo wp_kses_post($wporg_atts['title']); ?></button>
	<?php break; ?>
	<?php } ?>
	<?php } ?>
	<?php if (!empty($setrio_bizcal_button_container)){ ?>
		</div><?php 
	} ?>
	<?php } ?>
	<?php if (-1 == $wporg_atts['content_pos']) BizCalendar\wp_kses_post($content); ?>
	<?php if ($output_vue || !empty($wporg_atts['content_pos'])) { ?>
	<div class="bizcal-vue-app">
	<?php if (empty($wporg_atts['content_pos'])) BizCalendar\wp_kses_post($content); ?>
	<?php if ($output_vue) { ?>
	<v-app
		v-initial="<?php echo esc_attr(json_encode($wporg_atts)); ?>"
		v-bind="props('app')"
		class="bizcal-vue-v-app bizcal-force">
		<?php if (1 == $wporg_atts['content_pos']) BizCalendar\wp_kses_post($content); ?>
		<template v-if="ready">
		<?php if (2 == $wporg_atts['content_pos']) BizCalendar\wp_kses_post($content); ?>
		<v-form
			ref="form"
			v-model="valid"
			lazy-validation
			class="bizcal-form-force"
		>
		<?php if (3 == $wporg_atts['content_pos']) BizCalendar\wp_kses_post($content); ?>
		<?php setrio_bizcal_get_template_part('vue/' . $wporg_atts['type'], $wporg_atts); ?>
		<?php if (4 == $wporg_atts['content_pos']) BizCalendar\wp_kses_post($content); ?>
		</v-form>
		<?php if (5 == $wporg_atts['content_pos']) BizCalendar\wp_kses_post($content); ?>
		</template>
	</v-app>
	<?php } ?>
	<?php if (6 == $wporg_atts['content_pos']) BizCalendar\wp_kses_post($content); ?>
	</div>
	<?php } ?>
	</div>
<?php
	return str_replace('\'', '&#x27;', ob_get_clean());
}
function setrio_bizcal_get_template_part($template_name, $data = array(), $load = true, $require_once = false) {
	$template_dir = 'bizcalendar-web';
    $file_paths = array(
		1   => trailingslashit( get_stylesheet_directory() ) . $template_dir,
		10  => trailingslashit( get_template_directory() ) . $template_dir,
		100 => BIZCALENDAR_PLUGIN_DIR . 'templates',
	);
	$file_paths = apply_filters( 'bizcalendar_template_paths', $file_paths ); 
	// Sort the file paths based on priority.
	ksort( $file_paths, SORT_NUMERIC ); 
	
	$file_paths = array_map( 'trailingslashit', $file_paths ); 
	$located = false;
	$checked_file_paths = array();
	foreach($file_paths as $file_path){
		if(isset($checked_file_paths[$file_path])) continue;
		$checked_file_paths[$file_path] = true;
		$full_file_path = $file_path . $template_name . '.php';
		if ( file_exists( $full_file_path ) ) {
			$located = $full_file_path;
			if($load){
				load_template( $located, $require_once, $data);
			}
			break;
		}
	}
	return $located;
}
function &setrio_bizcal_get_arr_val(array $data, $key, $default = null)
{
	// @assert $key is a non-empty string
	// @assert $data is a loopable array
	// @otherwise return $default value
	if (!is_string($key) || empty($key) || !count($data))
	{
		return $default;
	}

	// @assert $key contains a dot notated string
	if (strpos($key, '.') !== false)
	{
		$keys = explode('.', $key);

		foreach ($keys as $innerKey)
		{
			// @assert $data[$innerKey] is available to continue
			// @otherwise return $default value
			if (!array_key_exists($innerKey, $data))
			{
				return $default;
			}

			$data = $data[$innerKey];
		}

		return $data;
	}

	// @fallback returning value of $key in $data or $default value
	return array_key_exists($key, $data) ? $data[$key] : $default;
}
function setrio_bizcal_script_add_type_attribute($tag, $handle, $src) {
    // if not your script, do nothing and return original $tag
    if ( 'setrio-bizcalendar-vue-recaptcha' !== $handle ) {
        return $tag;
    }
    // change the script tag by adding type="module" and return it.
    $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
    return $tag;
}

function setrio_bizcal_detalii_programare_shortcode($atts = [], $content = null, $tag = '')
{
	// override default attributes with user attributes
	$wporg_atts = shortcode_atts([
		'key' => '',
		'sba_hash' => '',
	], $atts, $tag);
	
	$key = trim($wporg_atts['key']);
	$sba_hash = trim($wporg_atts['sba_hash']);
	if(empty($sba_hash)){
		$should_delete = true;
		$sba_hash = isset($_GET['sba_hash']) ? (string)$_GET['sba_hash'] : '';
	} else {
		$should_delete = false;
	}
	static $setrio_bizcal_detalii_programare_info;
	if (isset($setrio_bizcal_detalii_programare_info)) {
		$info = $setrio_bizcal_detalii_programare_info;
	} else {
		$setrio_bizcal_detalii_programare_info = false;
		$uuid4 = $sba_hash;
		$info = null;
		if ($uuid4) {
			$info = get_transient('setrio_bizcal_appointment_' . $uuid4);
			if ($info) {
				$setrio_bizcal_detalii_programare_info = $info;
			}
			if ($should_delete) {
				delete_transient('setrio_bizcal_appointment_' . $uuid4);
			}
		}
	}
	if (!$info) {
		return '';
	}
	if ('' !== $key) {
		if (!isset($info[$key])) {
			return '';
		}
		return $info[$key];
	}

	$o = setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('txtShortcodeDetaliiProgramare'), $info);

	return $o;
}

function setrio_bizcal_add_customer_body_class($classes)
{
	$wsUser = get_option('setrio_bizcal_wsuser', '');
	if (strlen(trim($wsUser)) > 0) {
		$customerClass = "_" . strtolower(preg_replace("/[^A-Za-z0-9]/", '_', $wsUser));
		$classes[] = $customerClass;
	}
	return $classes;
}

// MESAJE

function setrio_bizcal_message($messageId, $onlyDefault = false)
{
	$result = "";

	switch ($messageId) {
			// TITLURI
		case "msgError":
			$result = "Eroare";
			break;
		case "msgWarning":
			$result = "Atentie!";
			break;
		case "msgInfo":
			$result = "Informare";
			break;
		case "textNA":
			$result = "N/A";
			break;
		case "textYes":
			$result = "DA";
			break;
		case "textNo":
			$result = "NU";
			break;
		case "textNext":
			$result = "Continuă";
			break;
		case "textPrev":
			$result = "Înapoi";
			break;
		case "txtLoading":
			$result = "Se încarcă...";
			break;
		case "txtPopupTitle":
			$result = "Programează-te online";
			break;

			// SERVICIU
		case "msgErrServiceAddressMissing":
			$result = "Nu ați configurat adresa serviciului web BizMedica";
			break;
		case "msgErrServiceUserMissing":
			$result = "Nu ați configurat utilizatorul folosit pentru accesarea serviciului web BizMedica";
			break;
		case "msgErrServicePasswordMissing":
			$result = "Nu ați configurat parola folosită pentru accesarea serviciului web BizMedica";
			break;
		case "msgErrServiceUnknownError":
			$result = "<p>A apărut o eroare necunoscută la accesarea serviciului!</p>" . "\n" .
				"<p>Vă rugăm să încercați din nou.</p>";
			break;
		case "msgErrUnknownError":
			$result = "Eroare necunoscută";
			break;
		case "msgSuccessSubmitTitle":
			$result = "Programare efectuată cu succes";
			break;
		case "msgErrSubmitTitle":
			$result = "Ooops!";
			break;
		case "msgErrSubmitBody":
			$result = "Momentan serviciul web intampina dificultati in afisarea raspunsului pentru cererea dvs.";
			break;
		case "msgErrFormTitle":
			$result = "Formularul conține erori";
			break;
		case "msgErrFormBody":
			$result = "Stimate pacient, formularul conține erori ce împiedică trimiterea cu succes a formularului. Vă rugăm să corectați și să reîncercați.";
			break;
		case "msgErrRequestInProgress":
			$result = "Există o altă cerere de programare în curs de trimitere!";
			break;
		case "msgErrGetAppointmentHours":
			$result = "A apărut o eroare internă la preluarea intervalelor orare disponibile pentru medicul selectat!" . "\n" .
				"<br><br>" . "\n" .
				"Vă rugăm să reîncărcați pagina și să încercați din nou să faceți programarea.";
			break;
		case "txtNoAvailableAppointments":
			$result = "Niciun interval disponibil.";
			break;
		case "txtFoundAvailableAppointments":
			$result = "Au fost găsite intervale de timp pentru data aleasă.";
			break;
		case "txtLaterAvailableAppointments":
			$result = "Au fost găsite intervale de timp după data aleasă.";
			break;
		case "txtWarnNoAvailableAppointments":
			$result = "Stimate pacient, din păcate pentru data selectată nu există intervale de timp disponibile pentru programare (posibile motive: ori nu există program de lucru, ori acesta este deja ocupat).";
			break;
		case "txtWarnNoAvailableAppointmentsOnce":
			$result = "Acest mesaj se va afișa astfel doar aceasta dată, însă această informație este prezentă în chenar roșu.";
			break;
		case "txtWarnShowingClosestAvailableAppointments":
			$result = "Se vor afișa intervale pentru cea mai apropiată dată disponibilă.";
			break;
		case "txtWarnShowingAvailableAppointments":
			$result = "<strong>Sunt afișate intervale orare pentru prima disponibilitate la data <strong class=\"warning pa-1 white--text\">{ data }</strong></strong>";
			break;
		case "txtUnknown":
			$result = "Necunoscut";
			break;
		case "txtNoItems":
			$result = "Niciun rezultat găsit";
			break;
		case "txtNoLocations":
			$result = "Nicio locație găsită";
			break;
		case "txtNoPaymentMethods":
			$result = "Nicio modalitate de plată găsită";
			break;
		case "txtNoPhysicians":
			$result = "Niciun medic găsit";
			break;
		case "txtNoServices":
			$result = "Niciun serviciu medical găsit";
			break;
		case "txtNoSpecialities":
			$result = "Nicio specialitate găsită";
			break;
		case "msgWarnNoAvailableAppointments":
			$result = "<p>Stimate pacient, din păcate pentru data selectată nu exista intervale de timp disponibile pentru programare " .
				"la medicul și/sau specialitatea selectată (posibile motive: ori nu exista program de lucru, " .
				"ori acesta este deja ocupat).</p>" . "\n" .
				"<p><b>Prima dată disponibilă pentru programare este {data}</b></p>" . "\n" .
				"<p><b>Doriți afișarea intervalelor de timp disponibile pentru această dată?</b></p>";
			break;
		case "msgErrNoAvailableAppointments":
			$result = "<p>Stimate pacient, din păcate pentru data selectată nu exista intervale de timp disponibile pentru programare " .
				"la medicul și/sau specialitatea selectată (posibile motive: ori nu exista program de lucru, " .
				"ori acesta este deja ocupat).</p>" . "\n" .
				"<p>Vă rugăm să selectați o altă zi din calendar!</p>";
			break;
		case "msgErrAppointmentTimeMissing":
			$result = "Nu ați selectat ora la care doriți programarea!";
			break;
		case "msgErrPhysicianMissing":
			$result = "Nu ați selectat medicul dorit!";
			break;
		case "subjRegisterAppointmentFailed":
			$result = 'Programare esuata. Clientul { nume } { telefon } solicita ajutor.';
			break;
		case "msgErrRegisterAppointmentFailed":
			$result = 'Stimate pacient, vă rugăm să reîncercați programarea pe alt interval de timp disponibil, ' .
				'deoarece cel selectat a devenit între timp indisponbil (posibil rezervat de către recepție sau din site de către alt pacient).<br><br>' . "\n" .
				'Vă mulțumim pentru înțelegere!';
			break;

			// FORMULAR
		case "lblMedicalSpeciality":
			$result = "Specialitatea:";
			break;
		case "lblMedicalSpecialityPlaceholder":
			$result = "Selectați specialitatea dorită";
			break;
		case "lblLocation":
			$result = "Locația:";
			break;
		case "lblLocationPlaceholder":
			$result = "Selectați locația dorită";
			break;
		case "lblPaymentType":
			$result = "Modalitatea de plată:";
			break;
		case "lblPaymentTypePlaceholder":
			$result = "Selectați modalitatea de plată dorită";
			break;
		case "lblPaymentTypeField":
			$result = "modalitatea de plată";
			break;
		case "lblPreferredPhysician":
			$result = "Medicul dorit:";
			break;
		case "lblPhysician":
			$result = "Alegeți medicul:";
			break;
		case "lblPhysicianPlaceholder":
			$result = "Selectați medicul dorit";
			break;
		case "lblPhysicianField":
			$result = "medicul";
			break;
		case "lblAnyAvailablePhysician":
			$result = "Orice medic disponibil";
			break;
		case "lblAnyAvailableLocation":
			$result = "Orice locație disponibilă";
			break;
		case "lblPhysicianPrice":
			$result = "Tarif medic pentru serviciul selectat:";
			break;
		case "lblService":
			$result = "Serviciul medical";
			break;
		case "lblMedicalService":
			$result = "Alegeți serviciul";
			break;
		case "lblMedicalServicePlaceholder":
			$result = "Selectați serviciul medical";
			break;
		case "lblMedicalServiceField":
			$result = "serviciul";
			break;
		case "lblAppointmentDate":
			$result = "Alegeți data dorită pentru programare";
			break;
		case "lblAppointmentTimeStartField":
			$result = "ora de început dorită";
			break;
		case "lblAppointmentTimeEndField":
			$result = "ora de sfârșit dorită";
			break;
		case "lblRequestAppointmentTitle":
			$result = "Solicitare programare";
			break;
		case "lblCheckingAvailability":
			$result = "Se verifică intervalele orare disponibile...";
			break;
		case "lblAvailabilityFound":
			$result = "Există intervale de timp disponibile pentru medicul {medic}<br>";
			break;
		case "btnCheckAvailability":
			$result = "Solicită programare";
			break;
		case "lblAppointmentTime":
			$result = "Alegeți medicul și intervalul orar dorit";
			break;
		case "lblPatientLastName":
			$result = "Nume";
			break;
		case "lblLastNameField":
			$result = "numele";
			break;
		case "lblPatientFirstName":
			$result = "Prenume";
			break;
		case "lblFirstNameField":
			$result = "prenumele";
			break;
		case "lblPatientPhone":
			$result = "Telefon";
			break;
		case "lblPhoneField":
			$result = "numărul de telefon";
			break;
		case "lblPhoneFieldNotValid":
			$result = "Numărul de telefon poate conține doar cifre!";
			break;
		case "lblPhoneFieldMinMax":
			$result = "Numărul de telefon trebuie sa contina 10-15 cifre!";
			break;
		case "lblPatientEmail":
			$result = "Adresă de e-mail";
			break;
		case "lblEmailFieldNotValid":
			$result = "Adresa de e-mail nu este într-un format corect!";
			break;
		case "lblTermsNotAgreed":
			$result = "Nu ați bifat termenii și condițiile";
			break;
		case "lblDataPolicyNotAgreed":
			$result = "Nu ați bifat acordul cu informațiile prezentate în nota de informare legată de protecția datelor cu caracter personal";
			break;
		case "lblPatientObservations":
			$result = "Observații";
			break;
		case "lblReCaptchaFieldNotValid":
			$result = "Vă rugăm să bifați caseta \"Nu sunt robot\" pentru a valida cererea!";
			break;
		case "btnRequestAppointment":
			$result = "Solicită programare";
			break;
		case "btnCannotRequestAppointment":
			$result = "Solicită să fii contactat";
			break;
		case "btnNotifyAdmin":
			$result = "Trimite";
			break;
		case "lblAppointmentPaymentStatusExpired":
            $result = "<p>Stimate pacient, vă mulțumim pentru alegerea făcută, dar cererea a expirat.</p>";
            break;
		case "lblAppointmentPaymentStatusPending":
            $result = "<p>Stimate pacient, vă mulțumim pentru alegerea făcută și așteptăm confirmarea automată de la procesatorul de plăți pentru programarea dumneavoastră în data de {data}, la medicul {medic}, specialitatea {specialitatea}. Pagina se reincarca in mod automat la fiecare 5 minute. Alternativ accesati butonul de reincarcare pagina. Puteti reincerca plata accesand butonul 'Reincearca Plata'. Puteti crea o alta programare in formularul de programare. Cererea curenta expira dupa 1 ora.</p>";
            break;
		case "lblAppointmentConfirmation":
            $result = "<p>Stimate pacient, vă mulțumim pentru alegerea făcută și confirmăm programarea dumneavoastră în data de {data}, la medicul {medic}, specialitatea {specialitatea}.</p>
<br/><br/>
<center><b>Vă așteptăm cu drag!</b></center>
<p>*În cazul în care doriți să anulați programarea vă rugăm să ne contactați pe e-mail: {email_clinica} sau telefonic: {telefon_clinica}</p>
<p>Înregistrați-vă programarea în calendarul dvs:
<ul>
	<li><a href=\"{ ics_href }\" title=\"Descarca programare.ics\" download=\"programare.ics\"><img src=\"{cal_img_src}\" width=\"25\" /> programare.ics</a></li>
	<li><a title=\"Descarca programare.vcs\" href=\"{ vcs_href }\" download=\"programare.vcs\"><img src=\"{cal_img_src}\" width=\"25\" /> programare.vcs</a></li>
</ul>
</p>";
            break;
		case "lblAppointmentNotification":
            $result = "<p>Stimate pacient, am fost informați de dificultățile întâmpinate de dumneavoastră. Vă vom contacta în cel mai scurt timp posibil.</p>
			<p>Înțelegem că doriti programare în data de {data}, la medicul {medic},specialitatea {specialitatea}, locația {locatia}.</p>
			<p>Dacă doriți să urgentați solicitarea de ajutor, vă rugăm să ne contactați pe e-mail: {email_clinica} sau telefonic: {telefon_clinica}</p>";
            break;
        case "lblAppointmentConfirmationWithLocation":
            $result = "<p>Stimate pacient, vă mulțumim pentru alegerea făcută și confirmăm programarea dumneavoastră în data de {data}, la medicul {medic}, specialitatea {specialitatea}, locația {locatia}.</p>
<br/><br/>
<center><b>Vă așteptăm cu drag!</b></center>
<p>*În cazul în care doriți să anulați programarea vă rugăm să ne contactați pe e-mail: {email_clinica} sau telefonic: {telefon_clinica}</p>
<p>Înregistrați-vă programarea în calendarul dvs:
<ul>
	<li><a href=\"{ ics_href }\" title=\"Descarca programare.ics\" download=\"programare.ics\"><img src=\"{cal_img_src}\" width=\"25\" /> programare.ics</a></li>
	<li><a title=\"Descarca programare.vcs\" href=\"{ vcs_href }\" download=\"programare.vcs\"><img src=\"{cal_img_src}\" width=\"25\" /> programare.vcs</a></li>
</ul>
</p>";
            break;
		case "btnGotIt":
			$result = "Am înțeles";
			break;
		case "btnCancel":
			$result = "Renunță";
			break;
		case "lblFieldMissing":
			$result = "Nu ați introdus";
			break;
		case "lblPrice":
			$result = "Tarif:";
			break;
		case "lblPriceValue":
			$result = "%s lei";
			break;
		case "lblPriceMissing":
			$result = "preț necunoscut";
			break;
		case "lblPriceIntervalAndCurrency":
			$result = "{ pret_min } - { pret_max } { valuta }";
			break;
		case "lblPriceValueAndCurrency":
			$result = "{ pret } { valuta }";
			break;
		case "lblMedicalServiceMissing":
			$result = "Nu ați specificat serviciul pentru care doriți să preluați prețul!";
			break;
		case "lblMedicalServiceNotAvailableForPhysician":
			$result = "Serviciul selectat nu este disponibil pentru medicul selectat!";
			break;
		case "lblAuxObservationsCNAS":
			$result = "Consultație cu bilet de trimitere decontat CNAS.";
			break;
		case "lblPaymentTypeCNAS":
			$result = "Gratuit - CNAS";
			break;
		case "lblPaymentTypeTicket":
			$result = "Bilet de trimitere";
			break;
		case "lblPaymentTypeOnline":
			$result = "Plata Online/Numerar/Card";
			break;
		case "lblPaymentTypeCashCard":
			$result = "Numerar/Card";
			break;
		case "lblSelectedPhysician":
			$result = "Medicul selectat:";
			break;
		case "lblSelectedService":
			$result = "Serviciul selectat:";
			break;
		case "lblSelectedServicePrice":
			$result = "Tarif serviciu:";
			break;
		case "lblSelectedDate":
			$result = "Data și interval orar:";
			break;
		case "lblStep1":
			$result = "1. Serviciu";
			break;
		case "lblStep2":
			$result = "2. Ora";
			break;
		case "lblStep3":
			$result = "3. Detalii";
			break;
		case "lblStep4":
			$result = "4. Gata";
			break;
		case "lblRedirectPayment":
			$result = "Veți fi  redirecționat catre procesatorul de plăti online";
			break;

			// E-mail confirmare
		case "msgAppointmentConfirmationEmail":
			$result = "<h2>Programare confirmată online!</h2><ul>" . "\n" .
				"{purchase_id !=}<li>Plata dumneavoastră a fost finalizată cu SUCCES cu următorul ID de comandă: { order_id }</li>" . "\n{purchase_id /}" .
				"<li>Nume și prenume pacient: { nume }</li>" . "\n" .
				"<li>Telefon: { telefon }</li>" . "\n" .
				"<li>E-mail: { email }</li>" . "\n" .
				"<li>Specialitatea: { specialitatea }</li>" . "\n" .
				"<li>Serviciul medical: { serviciu }</li>" . "\n" .
				"<li>Tip plată: { plata }</li>" . "\n" .
				"<li>Medic: { medic }</li>" . "\n" .
				"<li>Data: { data }</li>" . "\n" .
				"<li>Preț: { pret }</li>" . "\n" .
				"<li>Observații: { observatii }</li>" . "\n" .
				"<li>Acord termeni si conditii: { acord_termeni }</li>" . "\n" .
				"<li>Acord GDPR - Protectia datelor cu caracter personal: { acord_gdpr }</li>" . "\n" .
				"<li>Acord Marketing - inscriere la Newsletter: { acord_newsletter }</li>" . "\n" .
				"</ul>";

			break;
		case "msgAppointmentPaymentDetails":
			$result = "Programare { data } { serviciu } { medic } ({ specialitatea }) { locatia } { pret }";

			break;
		case "msgAppointmentConfirmationEmailWithLocation":
			$result = "<h2>Programare confirmată online!</h2><ul>" . "\n" .
				"{purchase_id !=}<li>Plata dumneavoastră a fost finalizată cu SUCCES cu următorul ID de comandă: { order_id }</li>" . "\n{purchase_id /}" .
				"<li>Nume și prenume pacient: { nume }</li>" . "\n" .
				"<li>Telefon: { telefon }</li>" . "\n" .
				"<li>E-mail: { email }</li>" . "\n" .
				"<li>Specialitatea: { specialitatea }</li>" . "\n" .
				"<li>Locația: { locatia }</li>" . "\n" .
				"<li>Serviciul medical: { serviciu }</li>" . "\n" .
				"<li>Tip plată: { plata }</li>" . "\n" .
				"<li>Medic: { medic }</li>" . "\n" .
				"<li>Data: { data }</li>" . "\n" .
				"<li>Preț: { pret }</li>" . "\n" .
				"<li>Observații: { observatii }</li>" . "\n" .
				"<li>Acord termeni si conditii: { acord_termeni }</li>" . "\n" .
				"<li>Acord GDPR - Protectia datelor cu caracter personal: { acord_gdpr }</li>" . "\n" .
				"<li>Acord Marketing - inscriere la Newsletter: { acord_newsletter }</li>" . "\n" .
				"</ul>";
			break;
		case "msgAppointmentFailedEmail":
			$result = "<h2>Programare online eșuată!</h2>" . "\n" .
				"<p><strong>Cod eroare</strong>: <strong>{ error_code }</strong></p>" .  "\n" .
				"<p><strong>Mesaj eroare</strong>: <span>{ error_message }</span></p>" . "\n" .
				"<ul>" . "\n" .
				"{purchase_id !=}<li>ID comanda: { order_id }</li>" . "\n{purchase_id /}" .
				"<li>Nume și prenume pacient: { nume }</li>" . "\n" .
				"<li>Telefon: { telefon }</li>" . "\n" .
				"<li>E-mail: { email }</li>" . "\n" .
				"<li>Specialitatea: { specialitatea }</li>" . "\n" .
				"<li>Locația: { locatia }</li>" . "\n" .
				"<li>Serviciul medical: { serviciu }</li>" . "\n" .
				"<li>Tip plată: { plata }</li>" . "\n" .
				"<li>Medic: { medic }</li>" . "\n" .
				"<li>Data: { data }</li>" . "\n" .
				"<li>Preț: { pret }</li>" . "\n" .
				"<li>Observații: { observatii }</li>" . "\n" .
				"<li>Acord termeni si conditii: { acord_termeni }</li>" . "\n" .
				"<li>Acord GDPR - Protectia datelor cu caracter personal: { acord_gdpr }</li>" . "\n" .
				"<li>Acord Marketing - inscriere la Newsletter: { acord_newsletter }</li>" . "\n" .
				"</ul>";
			break;
		case "msgAppointmentFailedMobilPayEmailSubject":
			$result = "Programare client eșuată - Mobilpay";
			break;
		case "msgAppointmentFailedMobilPayEmailBody":
			$result = "<h2>Plata eșuată Programare online!</h2>" . "\n" .
				"<p><strong>Tip eroare</strong>: <strong>{ error_type }</strong></p>" .  "\n" .
				"<p><strong>Cod eroare</strong>: <strong>{ error_code }</strong></p>" .  "\n" .
				"<p><strong>Mesaj eroare</strong>: <span>{ error_message }</span></p>" . "\n" .
				"<p><strong>ID comanda</strong>: <span>{ uuid4 }</span></p>" . "\n" .
				"<p><strong>Data</strong>: <span>{ date }</span></p>" . "\n" .
				"<ul>" . "\n" .
				"<li>Nume și prenume pacient: { nume }</li>" . "\n" .
				"<li>Telefon: { telefon }</li>" . "\n" .
				"<li>E-mail: { email }</li>" . "\n" .
				"<li>Specialitatea: { specialitatea }</li>" . "\n" .
				"<li>Locația: { locatia }</li>" . "\n" .
				"<li>Serviciul medical: { serviciu }</li>" . "\n" .
				"<li>Tip plată: { plata }</li>" . "\n" .
				"<li>Medic: { medic }</li>" . "\n" .
				"<li>Data: { data }</li>" . "\n" .
				"<li>Preț: { pret }</li>" . "\n" .
				"<li>Observații: { observatii }</li>" . "\n" .
				"<li>Acord termeni si conditii: { acord_termeni }</li>" . "\n" .
				"<li>Acord GDPR - Protectia datelor cu caracter personal: { acord_gdpr }</li>" . "\n" .
				"<li>Acord Marketing - inscriere la Newsletter: { acord_newsletter }</li>" . "\n" .
				"</ul>";
			break;
		case "msgNotifiedSubmitTitle":
			$result = "Notificare trimisă";
			break;
		case "msgCalAppointmentSummary":
			$result = "Programare { serviciu } { data } { locatia } { medic }";
			break;
		case "msgCalAppointmentDescription":
			$result = "Programare { serviciu }" . "; \n" .
				"Data programarii: { data }" . ";  \n" .
				"Nume pacient: { nume }" . ";  \n" .
				"Specialitatea: { specialitatea }" . ";  \n" .
				"Locația: { locatia }" . ";  \n" .
				"Serviciul medical: { serviciu }" . ";  \n" .
				"Medic: { medic }" . ";  \n" .
				"Preț: { pret }" . ";  \n" .
				"Tip plată: { plata }" . ";  \n" .
				"";
			break;

			// ACORDURI
		case "lblTerms":
			$result = "Confirm că am citit și înțeles informațiile prezentate în <a target=\"_blank\" href=\"{ link }\">{ titlu }</a>";
			break;
		case "lblTermsText":
			$result = "Termenii și condițiile de utilizare";
			break;
		case "lblDataPolicy":
			$result = "Confirm că am citit și înteles informațiile prezentate în nota de informare legată de protecția datelor cu caracter personal prezentată la <a target=\"_blank\" href=\"{ link }\">{ titlu }</a>";
			break;
		case "lblDataPolicyText":
			$result = "secțiunea GDPR";
			break;
		case "lblNewsletter":
			$result = "Sunt de acord cu folosirea e-mailului pentru informări periodice, marketing, publicitate. Informațiile furnizate nu vor fi transferate către alte entități.";
			break;

			// LINK TRACKING
		case "txtWarnHelpAppointments":
			$result = "" .
				"<p>În aceste cazuri, dorim să vă contactăm și să vă ajutăm direct.</p>" . "\n" .
				"<p><b>Completați</b> în formular <b>numele dumneavoastră și numărul de telefon</b> pentru a putea fi contactat în cel mai scurt timp posibil.</p>" . "\n";
				"<p>Oferiți detalii în cazul în care considerați necesar în câmpul <b>Observații</b>.</p>" . "\n";
			break;
		case "txtCannotRequestAppointment":
			$result = "<p>Serviciul web întâmpină dificultăți</p>";
			break;
		case "txtShortcodeDetaliiProgramare":
			$result = "<h2>Programare confirmată online!</h2><ul>" . "\n" .
				"{purchase_id !=}<li>Plata dumneavoastră a fost finalizată cu SUCCES cu următorul ID de comandă: { order_id }</li>" . "\n{purchase_id /}" .
				"<li>Nume și prenume pacient: <b>{ nume }</b></li>" . "\n" .
				"<li>Telefon: <b>{ telefon }</b></li>" . "\n" .
				"<li>E-mail: <b>{ email }</b></li>" . "\n" .
				"<li>Specialitatea: <b>{ specialitatea }</b></li>" . "\n" .
				"<li>Locația: <b>{ locatia }</b></li>" . "\n" .
				"<li>Serviciul medical: <b>{ serviciu }</b></li>" . "\n" .
				"<li>Tip plată: <b>{ plata }</b></li>" . "\n" .
				"<li>Medic: <b>{ medic }</b></li>" . "\n" .
				"<li>Data: <b>{ data }</b></li>" . "\n" .
				"<li>Preț: <b>{ pret }</b></li>" . "\n" .
				"<li>Observații: <b>{ observatii }</b></li>" . "\n" .
				"<li>Acord termeni si conditii: <b>{ acord_termeni }</b></li>" . "\n" .
				"<li>Acord GDPR - Protectia datelor cu caracter personal: <b>{ acord_gdpr }</b></li>" . "\n" .
				"<li>Acord Marketing - inscriere la Newsletter: <b>{ acord_newsletter }</b></li>" . "\n" .
				"<li>Înregistrează programarea în calendarul tău:\n<ul>\n" .
				"\t<li><a href=\"{ ics_href }\" title=\"Descarca programare.ics\" download=\"programare.ics\"><img src=\"{cal_img_src}\" width=\"25\" /> programare.ics</a></li>\n" .
				"\t<li><a title=\"Descarca programare.vcs\" href=\"{ vcs_href }\" download=\"programare.vcs\"><img src=\"{cal_img_src}\" width=\"25\" /> programare.vcs</a></li>" . "\n" .
				"</ul>" . "\n" .
				"</li>" . "\n" .
				"</ul>";
			break;

			// DEFAULT VALUE
		default:
			$result = "&lt;&lt;MESAJ_NECUNOSCUT&gt;&gt;";
	}

	if (!$onlyDefault) {
		$customMessage = get_option('setrio_bizcal_msg_' . $messageId, "");
		if ($customMessage !== "")
			$result = $customMessage;
	}
	return $result;
}
// INLOCUIRE TAGURI
function setrio_bizcal_replace_tags($message, $data, $function_prefix = null, $function_arguments = null, $default_key = true)
{
	return preg_replace_callback('/{+\s*(\w+)\s*(?:}|(\!(?:\=))(.*?)}(.*?){\s*\1\s*\/})+/s', function ($matches) use ($data, $function_prefix, $function_arguments, $default_key) {
		if(isset($matches[2])){
			$r = setrio_bizcal_replace_tags('{' . $matches[1] . '}', $data, $function_prefix, $function_arguments, false);
			if('=' == $matches[2]){
				if($r != (isset($matches[3]) ? $matches[3] : '')) return '';
				return setrio_bizcal_replace_tags($matches[4], $data, $function_prefix, $function_arguments);
			}
			if('!=' == $matches[2]){
				if($r == (isset($matches[3]) ? $matches[3] : '')) return '';
				return setrio_bizcal_replace_tags($matches[4], $data, $function_prefix, $function_arguments);
			}
			return '-?-?-';
		}
		if(array_key_exists($matches[1],$data)){
			return $data[$matches[1]];
		}
		if (isset($function_prefix)) {
			$function = $function_prefix . $matches[1];
			if (function_exists($function)) {
				return $function($function_arguments);
			}
		}
		return $default_key ? $matches[0] : '';
	}, $message);
}

// INLOCUIRE TAGURI SI SPRINTF

function setrio_bizcal_replace_tags_sprintf($message, $data, $function_prefix = null, $function_arguments = null)
{
	return setrio_bizcal_replace_tags(setrio_bizcal_sprintf($message, $data), $data, $function_prefix, $function_arguments);
}

// SPRINTF CU NUMAR DINAMIC DE PARAMETRI (pentru cazul in care de la update la update textele salvate au mai putini parametri, macar sa nu dea ditai eroarea)

function setrio_bizcal_sprintf()
{
	$data = func_get_args(); // get all the arguments
	$string = array_shift($data); // the string is the first one
	if (isset($data[0]) && is_array($data[0])) { // if the second one is an array, use that
		$data = $data[0];
	}
	$data_by_index = array_values($data);
	array_unshift($data_by_index, '');
	$index = 0;

	// get possible matches, and feed them to our function https://www.php.net/manual/en/function.sprintf.php
	// https://regex101.com/
	// OK&NOK	%sHello %s %%s %1$s %1$s %%as asd asd %G
	// ISOK		%8s
	// ISOK		%-8s
	// ISOK		%08s
	// ISOK		%'*8s
	// ISOK		%'.8s
	// NOTOK	%%8.8s
	// ISOK		%8...8s
	// ISOK		%01.2f

	return preg_replace_callback('/((?<!\%)\%(\%\%)*(?!\%))([\'\*\-\.\d\w]+?)?(\d+)?(\$)?([bcdeEufFgGosxX])/', function ($matches) use (&$index, $data_by_index) {
		$has_dollar_sign = !empty($matches[5]);
		$should_decrement = false;
		if ($has_dollar_sign) {
			if ('' === $matches[4]) {
				$matches[4] = $matches[3];
				$matches[3] = '';
			}
			$needed_index = $matches[4];
			$matches[4] = '';
		} else {
			$needed_index = ++$index;
			$should_decrement = true;
		}
		if (!isset($data_by_index[$needed_index])) {
			if ($should_decrement) {
				$index--;
			};
			return $matches[0];
		}
		$return = @sprintf($matches[1] . $matches[3] . $matches[4] . $matches[6], $data_by_index[$needed_index]);
		if (false === $return) {
			if ($should_decrement) {
				$index--;
			};
			return $matches[0];
		}
		return $return;
	}, $string);
}

/**
 * Delete all transients from the database whose keys have a specific prefix.
 *
 * @param string $prefix The prefix. Example: 'my_cool_transient_'.
 */
function setrio_bizcal_delete_transients_with_prefix( $prefix ) {
	foreach ( setrio_bizcal_get_transient_keys_with_prefix( $prefix ) as $key ) {
		delete_transient( $key );
	}
}

/**
 * Gets all transient keys in the database with a specific prefix.
 *
 * Note that this doesn't work for sites that use a persistent object
 * cache, since in that case, transients are stored in memory.
 *
 * @param  string $prefix Prefix to search for.
 * @return array          Transient keys with prefix, or empty array on error.
 */
function setrio_bizcal_get_transient_keys_with_prefix( $prefix ) {
	global $wpdb;

	$prefix = $wpdb->esc_like( '_transient_' . $prefix );
	$sql    = "SELECT `option_name` FROM $wpdb->options WHERE `option_name` LIKE %s'";
	$keys   = $wpdb->get_results( $wpdb->prepare( "$sql", $prefix . '%' ), ARRAY_A );

	if ( is_wp_error( $keys ) ) {
		return [];
	}

	return array_map( function( $key ) {
		// Remove '_transient_' from the option name.
		return ltrim( $key['option_name'], '_transient_' );
	}, $keys );
}

// METODE APEL WEBSERVICE BIZMEDICA

function setrio_bizcal_ajax_get_medical_specialities()
{
	global $setrio_bizcal_debug, $setrio_bizcal_securemode;

	if ($setrio_bizcal_securemode)
		check_ajax_referer("getMedicalSpecialities");
	
	$capsType = get_option('setrio_bizcal_caps_speciality', 'lcaseucfirst');
	if(!$capsType){
		$capsType = get_option('setrio_bizcal_caps', '');
	}
	if(!$capsType || ('none'==$capsType)){
		$capsType = false;
	}
	
	try {
		$client = new SetrioBizCal_BizMedicaServiceClient();
		//$localClient = new SetrioBizCal_LocalDataClient();

		/*$responseDate = $client->getMedicalSpecialitiesDate();
		if (!setrio_bizcal_is_valid_json($responseDate))
			throw new Exception(setrio_bizcal_parse_service_exception($responseDate));
		
		$remoteDate = json_decode($responseDate);
		$localDate = $localClient->getMedicalSpecialitiesDate();
		if (($remoteDate->ErrorCode == 0) && ($remoteDate->ErrorMessage == ""))
		{
			if ($remoteDate->Date > $localDate)
			{*/
		$response = $client->getMedicalSpecialities();
		$specialityOrder = (int)get_option('setrio_bizcal_speciality_order', 0);
		$response_object = json_decode($response, true);
		if ($specialityOrder) {
			$specialityOrderItems = get_option('setrio_bizcal_speciality_order_items');
			if ($specialityOrderItems) {
				$specialityOrderItemsDecoded = json_decode($specialityOrderItems, true);
				if ($specialityOrderItemsDecoded && is_array($specialityOrderItemsDecoded)) {

					
					if ($response_object && !empty($response_object['Specialities'])) {

						$speciality_items = array();
						foreach ($response_object['Specialities'] as $speciality) {
							$speciality_items[$speciality['Code']] = $speciality;
						}
						$response_object['Specialities'] = setrio_bizcal_sortArrayByArray($speciality_items, $specialityOrderItemsDecoded);
						$response_object['Specialities'] = array_values($response_object['Specialities']);
						
					}
				}
			}
		}
		
		foreach($response_object['Specialities'] as &$speciality_i){
			$speciality_i['Name'] = setrio_bizcal_caps($speciality_i['Name'], $capsType);
		}
		
		$response = json_encode($response_object);

		//$localClient->updateMedicalSpecialities($localDate, $remoteDate->Date, $response);
		/*}
			else
			{
				$response = $localClient->getMedicalSpecialities();
			}
		}*/

		if (!setrio_bizcal_is_valid_json($response))
			throw new Exception(setrio_bizcal_parse_service_exception($response));

		wp_send_json($response);
	} catch (Exception $e) {
		wp_send_json(json_encode(["ErrorCode" => 1000, "ErrorMessage" => $e->getMessage()]));
	}

	wp_send_json(json_encode(["ErrorCode" => 2000, "ErrorMessage" => setrio_bizcal_message('msgErrUnknownError')]));
}

function setrio_bizcal_ajax_get_medical_services()
{
	global $setrio_bizcal_debug, $setrio_bizcal_securemode;

	if ($setrio_bizcal_securemode)
		check_ajax_referer("getMedicalSpecialities");
	
	$capsType = get_option('setrio_bizcal_caps_service', 'lcaseucfirst');
	
	if(!$capsType){
		$allCaps = (bool)get_option('setrio_bizcal_all_caps', false);
		if($allCaps){
			$capsType = 'ucase';
		} else {
			$capsType = get_option('setrio_bizcal_caps', '');
		}
	}
	if(!$capsType || ('none'==$capsType)){
		$capsType = false;
	}

	try {
		$enableMultipleLocations = get_option('setrio_bizcal_enable_multiple_locations', false);

		if (isset($_POST['location_uid']))
			$locationUID = sanitize_text_field($_POST['location_uid']);
		else
			$locationUID = null;

		if (isset($_POST['physician_uid']))
			$physicianUID = sanitize_text_field($_POST['physician_uid']);
		else
			$physicianUID = null;

		// if ((($locationUID == null) || (!$locationUID)) && ($enableMultipleLocations))
			// throw new Exception("Nu ați selectat locația dorită");

		$requestParams = ["SpecialityCode" => sanitize_text_field($_POST['speciality_code'])];
		if ($enableMultipleLocations && $locationUID)
			$requestParams["LocationUID"] = $locationUID;
		if ($physicianUID)
			$requestParams["PhysicianUID"] = $physicianUID;

		$serviceParams = json_encode($requestParams);

		$client = new SetrioBizCal_BizMedicaServiceClient();

		$response = $client->getMedicalServices($serviceParams);

		if (!setrio_bizcal_is_valid_json($response))
			throw new Exception(setrio_bizcal_parse_service_exception($response));

		$items = array();
		$services = json_decode($response);
		if (($services->ErrorCode == 0) && ($services->ErrorMessage == "")) {
			foreach ($services->MedicalServices as $medicalService) {
				$isEuro = false;
				$onlinePay = false;
				if (property_exists($medicalService, "IsEuro"))
					$isEuro = $medicalService->IsEuro;
				
				if (property_exists($medicalService, "PrepaymentIsMandatoryForOnlineAppointment"))
					$onlinePay = !!$medicalService->PrepaymentIsMandatoryForOnlineAppointment;

				
				$items[] = [
					'UID' => $medicalService->UID,
					'Name' => setrio_bizcal_caps($medicalService->Name, $capsType),
					'OnlinePay' => $onlinePay,
					'Price' => ($medicalService->MinPrice != $medicalService->MaxPrice)
						? setrio_bizcal_replace_tags_sprintf(
							setrio_bizcal_message('lblPriceIntervalAndCurrency'),
							array(
								'pret_min' => $medicalService->MinPrice,
								'pret_max' => $medicalService->MaxPrice,
								'valuta' => (($isEuro) ? "€" : "lei"),
							)
						) : setrio_bizcal_replace_tags_sprintf(
							setrio_bizcal_message('lblPriceValueAndCurrency'),
							array(
								'pret' => $medicalService->MinPrice,
								'valuta' => (($isEuro) ? "€" : "lei"),
							)
						),
				];
			}

			$response = json_encode(["ErrorCode" => 0, "ErrorMessage" => "", "MedicalServices" => $items]);
		}

		wp_send_json($response);
	} catch (Exception $e) {
		wp_send_json(json_encode(["ErrorCode" => 1000, "ErrorMessage" => $e->getMessage()]));
	}

	wp_send_json(json_encode(["ErrorCode" => 2000, "ErrorMessage" => setrio_bizcal_message('msgErrUnknownError')]));
}

function setrio_bizcal_ajax_get_physicians()
{
	global $setrio_bizcal_debug, $setrio_bizcal_securemode, $wpdb;
	
	$capsType = get_option('setrio_bizcal_caps_physician', 'lcaseucwords');
	if(!$capsType){
		$capsType = get_option('setrio_bizcal_caps', '');
	}
	if(!$capsType || ('none'==$capsType)){
		$capsType = false;
	}
	
	if ($setrio_bizcal_securemode)
		check_ajax_referer("getMedicalSpecialities");

	try {
		$serviceParams = [
			"SpecialityCode" => sanitize_text_field($_POST['speciality_code']),
			"PaymentType" => sanitize_text_field($_POST['payment_type'])
		];
		
		if(!empty($_POST['service_uid'])){
			$serviceParams["ServiceUID"] = sanitize_text_field($_POST['service_uid']);
		}
		if(!empty($_POST['location_uid'])){
			$serviceParams["LocationUID"] = sanitize_text_field($_POST['location_uid']);
		}
		$serviceParams = json_encode($serviceParams);

		$client = new SetrioBizCal_BizMedicaServiceClient();
		$response = $client->getPhysicians($serviceParams);

		if (!setrio_bizcal_is_valid_json($response))
			throw new Exception(setrio_bizcal_parse_service_exception($response));

		$physiciansReponse = json_decode($response);

		if ($physiciansReponse->ErrorMessage != "")
			throw new Exception($physiciansReponse->ErrorMessage);
		if ($physiciansReponse->ErrorCode != 0)
			throw new Exception("Eroare preluare medici cu codul " . $physiciansReponse->ErrorCode);

		$newResponse = array();
		$newResponse["ErrorMessage"] = "";
		$newResponse["ErrorCode"] = 0;
		$newResponse["Physicians"] = array();

		foreach ($physiciansReponse->Physicians as $physicianItem) {
			if (($physicianItem) && ($physicianItem->UID)){
				$sql = "SELECT * FROM {$wpdb->prefix}bizcal_physicians_description WHERE physician_uid = %s";
				$physicianRow = $wpdb->get_row($wpdb->prepare("$sql",[$physicianItem->UID]));
			} else
				$physicianRow = false;
			if ($physicianRow) {
				$physician_description = $physicianRow->description;
				$physician_picture_id = $physicianRow->physician_picture_id;
				if ($physician_picture_id === null)
					$physician_picture_id = 0;
			} else {
				$physician_description = '';
				$physician_picture_id = 0;
			}
			$physician_url = wp_get_attachment_url($physician_picture_id);
			if (!$physician_url)
				$physician_url = "";

			$newResponse["Physicians"][] = array(
				"UID" => $physicianItem->UID,
				"Name" => setrio_bizcal_caps($physicianItem->Name, $capsType),
				"AllowCNAS" => $physicianItem->AllowCNAS,
				"AllowPrivate" => $physicianItem->AllowPrivate,
				"Description" => $physician_description,
				"PictureURL" => $physician_url
			);
		}

		wp_send_json(json_encode($newResponse));
	} catch (Exception $e) {
		wp_send_json(json_encode(["ErrorCode" => 1000, "ErrorMessage" => $e->getMessage()]));
	}

	wp_send_json(json_encode(["ErrorCode" => 2000, "ErrorMessage" => setrio_bizcal_message('msgErrUnknownError')]));
}
function setrio_bizcal_mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false) {
	if(function_exists('mb_strtoupper')){
	  $first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
	} else {
	  $first_letter = strtoupper(substr($str, 0, 1));
	}
	$str_end = "";
	if ($lower_str_end) {
		if(function_exists('mb_strtolower')){
			$str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
		} else {
			$str_end = strtolower(substr($str, 1, strlen($str)));
		}
	}
	else {
		if(function_exists('mb_substr')){
			$str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
		} else {
			$str_end = substr($str, 1, strlen($str));
		}
	}
	$str = $first_letter . $str_end;
	return $str;
}
$setrio_bizcal_blog_charset = strtoupper(get_option('blog_charset'));
global $setrio_bizcal_blog_charset;
function setrio_bizcal_caps($text, $capsType){
	$text = preg_replace('/\s+/',' ', trim($text));
	global $setrio_bizcal_blog_charset;
	switch($capsType){
		case 'ucase':
			if(function_exists('mb_strtoupper')){
				$text = mb_strtoupper($text, $setrio_bizcal_blog_charset);
			} else {
				$text = strtoupper($text);
			}
		case 'lcase':
		case 'lcaseucwords':
		case 'lcaseucfirst':
			if(function_exists('mb_strtolower')){
				$text = mb_strtolower($text, $setrio_bizcal_blog_charset);
			} else {
				$text = strtolower($text);
			}
		break;
	}
	switch($capsType){
		case 'ucwords':
		case 'lcaseucwords':
			$text = preg_replace_callback('/[^\s\-]+/',function($matches){
				global $setrio_bizcal_blog_charset;
				return setrio_bizcal_mb_ucfirst($matches[0], $setrio_bizcal_blog_charset);
			},$text);
		break;
		case 'ucfirst':
		case 'lcaseucfirst':
			$text = setrio_bizcal_mb_ucfirst($text, $setrio_bizcal_blog_charset);
		break;
	}
	return $text;
}
function setrio_bizcal_ajax_get_medical_services_with_prices()
{
	global $setrio_bizcal_debug, $setrio_bizcal_securemode;

	$addServicesWithoutPrice = false;
	
	$capsType = get_option('setrio_bizcal_caps_service', 'lcaseucfirst');
	if(!$capsType){
		$allCaps = (bool)get_option('setrio_bizcal_all_caps', false);
		if($allCaps){
			$capsType = 'ucase';
		} else {
			$capsType = get_option('setrio_bizcal_caps', '');
		}
	}
	if(!$capsType || ('none'==$capsType)){
		$capsType = false;
	}

	if ($setrio_bizcal_securemode)
		check_ajax_referer("getMedicalSpecialities");

	try {
		$enableMultipleLocations = get_option('setrio_bizcal_enable_multiple_locations', false);

		if (!empty($_POST['location_uid']))
			$locationUID = sanitize_text_field($_POST['location_uid']);
		else
			$locationUID = null;

		if (isset($_POST['physician_uid']))
			$physicianUID = sanitize_text_field($_POST['physician_uid']);
		else
			$physicianUID = null;

		// if ((($locationUID == null) || (!$locationUID)) && ($enableMultipleLocations))
			// throw new Exception("Nu ați selectat locația dorită");

		$requestParams = ["SpecialityCode" => sanitize_text_field($_POST['speciality_code'])];
		if ($enableMultipleLocations && $locationUID)
			$requestParams["LocationUID"] = $locationUID;
		if ($physicianUID)
			$requestParams["PhysicianUID"] = $physicianUID;

		$serviceParams = json_encode($requestParams);

		$priceServiceParams = json_encode(["PhysicianUID" => sanitize_text_field($_POST["physician_uid"])]);
		$selectedServiceUID = "";
		$selectedServiceFound = false;

		if (isset($_POST["selected_service_uid"]))
			$selectedServiceUID = sanitize_text_field($_POST['selected_service_uid']);

		$client = new SetrioBizCal_BizMedicaServiceClient();
		$servicesResponse = $client->getMedicalServices($serviceParams);
		$pricesResponse = $client->getMedicalServicesPriceList($priceServiceParams);
		$items = array();
		$services = json_decode($servicesResponse);
		if (($services->ErrorCode == 0) && ($services->ErrorMessage == "")) {
			$prices = json_decode($pricesResponse);
			if (($prices->ErrorCode == 0) && ($prices->ErrorMessage == "")) {
				foreach ($services->MedicalServices as $medicalService) {
					$priceFound = false;
					$onlinePay = false;
					if (property_exists($medicalService, "PrepaymentIsMandatoryForOnlineAppointment"))
						$onlinePay = !!$medicalService->PrepaymentIsMandatoryForOnlineAppointment;
					foreach ($prices->PriceList as $price) {
						if ($medicalService->UID == $price->ServiceUID) {
							$isEuro = false;
							if (property_exists($price, "IsEuro"))
								$isEuro = $price->IsEuro;
						
							$items[] = [
								'UID' => $medicalService->UID,
								'OnlinePay' => $onlinePay,
								'Name' => setrio_bizcal_caps($medicalService->Name, $capsType),
								'Price' => setrio_bizcal_replace_tags_sprintf(
									setrio_bizcal_message('lblPriceValueAndCurrency'),
									array(
										'pret' => (($price->Price != false) ? $price->Price : "-"),
										'valuta' => (($price->Price != false) ? (($isEuro) ? "€" : "lei") : ""),
									)
								),
							];
							$priceFound = true;
							if ($medicalService->UID == $selectedServiceUID)
								$selectedServiceFound = true;
						}
					}
					if ((!$priceFound) && ($addServicesWithoutPrice)) {
						$items[] = [
							'UID' => $medicalService->UID,
							'Name' => setrio_bizcal_caps($medicalService->Name, $capsType),
							'Price' => setrio_bizcal_message('lblPriceMissing')
						];
					}
				}

				if ((!$selectedServiceFound) && ($selectedServiceUID != ""))
					wp_send_json(json_encode(["ErrorCode" => -4000, "ErrorMessage" => setrio_bizcal_message('lblMedicalServiceNotAvailableForPhysician')]));
				else
					wp_send_json(json_encode(["ErrorCode" => 0, "ErrorMessage" => "", "MedicalServices" => $items]));
			} else {
				if (!setrio_bizcal_is_valid_json($pricesResponse))
					throw new Exception(setrio_bizcal_parse_service_exception($pricesResponse));

				wp_send_json($pricesResponse);
			}
		} else {
			if (!setrio_bizcal_is_valid_json($servicesResponse))
				throw new Exception(setrio_bizcal_parse_service_exception($servicesResponse));

			wp_send_json($servicesResponse);
		}
	} catch (Exception $e) {
		wp_send_json(json_encode(["ErrorCode" => 1000, "ErrorMessage" => $e->getMessage()]));
	}

	wp_send_json(json_encode(["ErrorCode" => 2000, "ErrorMessage" => setrio_bizcal_message('msgErrUnknownError')]));
}

function setrio_bizcal_ajax_get_payment_types()
{
	global $setrio_bizcal_debug, $setrio_bizcal_securemode;

	if ($setrio_bizcal_securemode)
		check_ajax_referer("getMedicalSpecialities");
	
	$capsType = get_option('setrio_bizcal_caps_payment_type', '');
	if(!$capsType){
		$capsType = get_option('setrio_bizcal_caps', '');
	}
	if(!$capsType || ('none'==$capsType)){
		$capsType = false;
	}

	try {
		$client = new SetrioBizCal_BizMedicaServiceClient();
		$response = $client->getPaymentTypes();

		if (!setrio_bizcal_is_valid_json($response))
			throw new Exception(setrio_bizcal_parse_service_exception($response));

		wp_send_json($response);
	} catch (Exception $e) {
		wp_send_json(json_encode(["ErrorCode" => 1000, "ErrorMessage" => $e->getMessage()]));
	}

	wp_send_json(json_encode(["ErrorCode" => 2000, "ErrorMessage" => setrio_bizcal_message('msgErrUnknownError')]));
}

function setrio_bizcal_ajax_get_allowed_payment_types()
{
	global $setrio_bizcal_debug, $setrio_bizcal_securemode;

	if ($setrio_bizcal_securemode)
		check_ajax_referer("getMedicalSpecialities");
	
	$capsType = get_option('setrio_bizcal_caps_payment_type', '');
	if(!$capsType){
		$capsType = get_option('setrio_bizcal_caps', '');
	}
	if(!$capsType || ('none'==$capsType)){
		$capsType = false;
	}

	$speciality_code = sanitize_text_field($_POST['speciality_code']);

	try {
		$params = json_encode([
			"SpecialityCode" => $speciality_code,
		]);

		$client = new SetrioBizCal_BizMedicaServiceClient();
		$response = $client->getAllowedPaymentTypes($params);

		if (!setrio_bizcal_is_valid_json($response))
			throw new Exception(setrio_bizcal_parse_service_exception($response));
		
		$response = json_decode($response);
		if(!empty($response->PaymentTypeList)){
			foreach($response->PaymentTypeList as &$payment_type_i){
				if($payment_type_i->ID == 2){
					if($speciality_code == 'MEDICINA DE FAMILIE'){
						$payment_type_i->Description = setrio_bizcal_message('lblPaymentTypeCNAS');
					} else {
						$payment_type_i->Description = setrio_bizcal_message('lblPaymentTypeTicket');
					}
				} elseif($payment_type_i->ID == 1){
					if(setrio_bizcal_online_enabled()){
						$payment_type_i->Description = setrio_bizcal_message('lblPaymentTypeOnline');
					} else {
						$payment_type_i->Description = setrio_bizcal_message('lblPaymentTypeCashCard');
					}
				}
				
				$payment_type_i->Description = setrio_bizcal_caps($payment_type_i->Description, $capsType);
			}
		}
		wp_send_json(json_encode($response));
	} catch (Exception $e) {
		wp_send_json(json_encode(["ErrorCode" => 1000, "ErrorMessage" => $e->getMessage(), "Payload" => $params]));
	}

	wp_send_json(json_encode(["ErrorCode" => 2000, "ErrorMessage" => setrio_bizcal_message('msgErrUnknownError'), "Payload" => $params]));
}

function setrio_bizcal_ajax_get_date_availabilities()
{
	global $setrio_bizcal_debug, $setrio_bizcal_securemode, $wpdb;

	if ($setrio_bizcal_securemode)
		check_ajax_referer("getMedicalSpecialities");
	
	$capsType = get_option('setrio_bizcal_caps_physician', 'lcaseucwords');
	if(!$capsType){
		$capsType = get_option('setrio_bizcal_caps', '');
	}
	if(!$capsType || ('none'==$capsType)){
		$capsType = false;
	}

	$speciality_code = sanitize_text_field($_POST['speciality_code'] ?? null);
	$physician_uid = sanitize_text_field($_POST['physician_uid'] ?? null);
	$service_uid = sanitize_text_field($_POST['service_uid'] ?? null);
	$location_uid = sanitize_text_field($_POST['location_uid'] ?? null);
	$payment_type_id = sanitize_text_field($_POST['payment_type_id'] ?? null);

	if (!$speciality_code)
		$speciality_code = null;
	if ((!$physician_uid) || ($physician_uid === "0"))
		$physician_uid = null;
	if ((!$service_uid) || ($service_uid === "0"))
		$service_uid = null;
	if ((!$location_uid) || ($location_uid === "0"))
		$location_uid = null;

	try {
		$enableMultipleLocations = get_option('setrio_bizcal_enable_multiple_locations', false);

		// if ((($locationUID == null) || (!$locationUID)) && ($enableMultipleLocations))
			// throw new Exception("Nu ați selectat locația dorită");
		$params = [
			"SpecialityCode" => $speciality_code,
			"StartDate" => sanitize_text_field($_POST['desired_date']),
			"Days" => 30,
		];
		if ($physician_uid){
			$params['PhysicianUID'] = $physician_uid;
		}
		if ($service_uid){
			$params['ServiceUID'] = $service_uid;
		}
		if ($payment_type_id){
			$params['PaymentTypeID'] = $payment_type_id;
		}
		if ($location_uid){
			$params['LocationUID'] = $location_uid;
		}
		$params = json_encode($params);

		$client = new SetrioBizCal_BizMedicaServiceClient();
		$response = $client->getDateAvailabilities($params);

		if (!setrio_bizcal_is_valid_json($response))
			throw new Exception(setrio_bizcal_parse_service_exception($response));

		$data = json_decode($response);
		if ($data && is_object($data)) {
			$data->Payload = json_decode($params);
			$response = json_encode($data);
		}
		wp_send_json($response);
	} catch (Exception $e) {
		wp_send_json(json_encode(["ErrorCode" => 1000, "ErrorMessage" => $e->getMessage(), "Payload" => $params]));
	}

	wp_send_json(json_encode(["ErrorCode" => 2000, "ErrorMessage" => setrio_bizcal_message('msgErrUnknownError'), "Payload" => $params]));
}

function setrio_bizcal_ajax_get_availability()
{
	global $setrio_bizcal_debug, $setrio_bizcal_securemode, $wpdb;

	if ($setrio_bizcal_securemode)
		check_ajax_referer("getMedicalSpecialities");
	
	$capsType = get_option('setrio_bizcal_caps_physician', 'lcaseucwords');
	if(!$capsType){
		$capsType = get_option('setrio_bizcal_caps', '');
	}
	if(!$capsType || ('none'==$capsType)){
		$capsType = false;
	}

	$speciality_code = sanitize_text_field($_POST['speciality_code']);
	$physician_uid = sanitize_text_field($_POST['physician_uid']);
	$service_uid = sanitize_text_field($_POST['service_uid']);

	if (!$speciality_code)
		$speciality_code = null;
	if ((!$physician_uid) || ($physician_uid === "0"))
		$physician_uid = null;
	if ((!$service_uid) || ($service_uid === "0"))
		$service_uid = null;

	try {
		$enableMultipleLocations = get_option('setrio_bizcal_enable_multiple_locations', false);

		if (isset($_POST['location_uid']))
			$locationUID = sanitize_text_field($_POST['location_uid']);
		else
			$locationUID = null;

		// if ((($locationUID == null) || (!$locationUID)) && ($enableMultipleLocations))
			// throw new Exception("Nu ați selectat locația dorită");
		$params = [
			"SpecialityCode" => $speciality_code,
			"PhysicianUID" => $physician_uid,
			"ServiceUID" => $service_uid,
			"PaymentTypeID" => sanitize_text_field($_POST['payment_type_id']),
			"Date" => sanitize_text_field($_POST['desired_date'])
		];
		if ($enableMultipleLocations && $locationUID){
			$params['LocationUID'] = $locationUID;
		}
		$params = json_encode($params);

		$client = new SetrioBizCal_BizMedicaServiceClient();
		$response = $client->getAppointmentAvailabilities($params);

		if (!setrio_bizcal_is_valid_json($response))
			throw new Exception(setrio_bizcal_parse_service_exception($response));

		$data = json_decode($response);
		if (($data->ErrorCode == 0) && ($data->ErrorMessage == "")) {
			$physiciansParams = json_encode(["SpecialityCode" => $speciality_code]);
			$physiciansReponse = $client->getPhysicians($physiciansParams);
			if (!setrio_bizcal_is_valid_json($physiciansReponse))
				throw new Exception(setrio_bizcal_parse_service_exception($physiciansReponse));
			$physiciansReponse = json_decode($physiciansReponse);
			if ($physiciansReponse->ErrorMessage != "")
				throw new Exception($physiciansReponse->ErrorMessage);
			if ($physiciansReponse->ErrorCode != 0)
				throw new Exception("Eroare preluare medici cu codul " . $physiciansReponse->ErrorCode);

			foreach ($data->Availabilities as $index => $availability) {
				if (($service_uid !== null) && ((!isset($availability->Price)) || ($availability->Price == null))) {
					$priceParams = json_encode([
						"PhysicianUID" => $availability->PhysicianUID
					]);
					$priceResponse = $client->getMedicalServicesPriceList($priceParams);
					if (!setrio_bizcal_is_valid_json($priceResponse))
						throw new Exception(setrio_bizcal_parse_service_exception($priceResponse));
					$priceResponse = json_decode($priceResponse);
					if (($priceResponse->ErrorCode == 0) && ($priceResponse->ErrorMessage == "")) {
						foreach ($priceResponse->PriceList as $priceItem) {
							if ($priceItem->ServiceUID == $service_uid) {
								$isEuro = false;
								if (property_exists($priceItem, "IsEuro"))
									$isEuro = $priceItem->IsEuro;
								$data->Availabilities[$index]->Price = $priceItem->Price . (($isEuro) ? " €" : " lei");
								break;
							}
						}
					} else
						throw new Exception($priceResponse->ErrorMessage);
				}

				foreach ($physiciansReponse->Physicians as $physicianItem)
					if ($physicianItem->UID == $availability->PhysicianUID) {
						$data->Availabilities[$index]->PhysicianName = setrio_bizcal_caps($physicianItem->Name, $capsType);

						$physicianRow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}bizcal_physicians_description WHERE physician_uid = %s",[$physicianItem->UID]));
						if ($physicianRow) {
							$physician_description = $physicianRow->description;
							$physician_picture_id = $physicianRow->physician_picture_id;
							if ($physician_picture_id === null)
								$physician_picture_id = 0;
						} else {
							$physician_description = '';
							$physician_picture_id = 0;
						}
						$physician_url = wp_get_attachment_url($physician_picture_id);
						if (!$physician_url)
							$physician_url = "";

						$data->Availabilities[$index]->Description = $physician_description;
						$data->Availabilities[$index]->PictureURL = $physician_url;

						break;
					}
			}
			$data->Payload = json_decode($params);
			$response = json_encode($data);
		}

		wp_send_json($response);
	} catch (Exception $e) {
		wp_send_json(json_encode(["ErrorCode" => 1000, "ErrorMessage" => $e->getMessage(), "Payload" => $params]));
	}

	wp_send_json(json_encode(["ErrorCode" => 2000, "ErrorMessage" => setrio_bizcal_message('msgErrUnknownError'), "Payload" => $params]));
}
function setrio_bizcal_online_payment_mobilpay_status(){
	$uuid4 = isset($_GET['orderId']) ? $_GET['orderId'] : '';
	$info = get_transient('setrio_bizcal_appointment_payment_mobilpay_' . $uuid4);
	// echo '<pre>';
	// print_r($info);
	// die;
	ini_set('display_errors', 0);
	if(!$info){
		get_header()
		?>
		<div id="primary" class="content-area" style="min-height:600px; padding-top:200px;">
		<main id="main" class="site-main">
		<div class="entry-content">
		<div style="max-width:1024px;margin:auto;"><?php
		BizCalendar\wp_kses_post(setrio_bizcal_message('lblAppointmentPaymentStatusExpired'));
		?>
		</div>
		</div><!-- .entry-content -->
		</main>
		</div>
		<?php get_footer();
		exit;
	}
	
	$should_refresh = true;
	$found_confirmed = false;
	$confirmed = false;
	$retriable = true;
	if(!empty($info['statuses'])){
		$found_confirmed = !!array_filter($info['statuses'], function($s){ return isset($s['action']) && $s['action']=='confirmed'; });
		$confirmed = !!array_filter($info['statuses'], function($s){ return empty($s['type']) && empty($s['code']) && isset($s['action']) && $s['action']=='confirmed'; });
		$should_refresh = !$confirmed;
	}
	// if($info && !empty($info['accessed_status'])){
		// setrio_bizcal_online_payment_mobilpay_log($uuid4, [
			// 'accessed_status' => 1,
		// ]);
	// } else {
		// setrio_bizcal_online_payment_mobilpay_log($uuid4, [
			// 'info' => $info,
			// 'accessed_status' => 1,
		// ]);
	// }
	
	get_header()
	?>
	<div id="primary" class="content-area" style="min-height:600px; padding-top:200px;">
	<main id="main" class="site-main">
	<div class="entry-content">
	<div style="max-width:1024px;margin:auto;">
	<a href="<?php echo esc_url( add_query_arg( 'orderId', $uuid4, add_query_arg( 'setrio-bizcal-mobilpay-status',1, site_url())) ) ?>">Reincarcare pagina</a>
	<?php
	if(!$confirmed){
		if(!$found_confirmed){
			BizCalendar\wp_kses_post(setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('lblAppointmentPaymentStatusPending'), $info['info']));
		}
		?>
		<br />
		<p>Statusuri de la procesatorul de plati: (se asteapta statusul "confirmed")</p>
		<ul>
		<?php
			if(!empty($info['statuses']))
			foreach($info['statuses'] as $status){ 
			?>
			<li><b><?php echo wp_kses_post($status['date']) ?></b>: <b><?php echo wp_kses_post($status['action']) ?></b> <?php echo wp_kses_post($status['message']) ?></li>
		<?php
			}?>
		</ul>
		<?php
	}
	if($confirmed){
		$info = get_transient('setrio_bizcal_appointment_' . $uuid4);
		if(!$info){ ?>
				<script>setTimeout(() => window.location.href='<?php echo esc_js(site_url()) ?>', 500)</script>
			<?php 
		}
		$enableSuccessRedirect = get_option('setrio_bizcal_enable_success_redirect', 0);
		if ($enableSuccessRedirect) {

			$successRedirectPostId = get_option('setrio_bizcal_success_redirect_post_id');
			$successRedirectLink = get_option('setrio_bizcal_success_redirect_link');

			$redirect_link = trim($successRedirectLink);
			if ('' === $redirect_link) {
				if ($successRedirectPostId) {
					$redirect_link = get_the_permalink($successRedirectPostId);
				}
			}
			
			if($redirect_link){ ?>
				<script>setTimeout(() => window.location.href='<?php echo esc_js(add_query_arg('sba_hash', $uuid4, $redirect_link)) ?>', 500)</script>
			<?php 
			} else {
				BizCalendar\wp_kses_post(setrio_bizcal_detalii_programare_shortcode(['sba_hash' => $uuid4]));
			}
		} else {
			BizCalendar\wp_kses_post(setrio_bizcal_detalii_programare_shortcode(['sba_hash' => $uuid4]));
		}
	} else {
		if($info){
			if(!$found_confirmed){
				echo '<br />';
				BizCalendar\wp_kses_post(setrio_bizcal_online_payment_mobilpay($info['info'], $uuid4, $info['post']));
			}
			
			echo '<br />';
			echo '<br />';
			$post = $info['post'];
			// echo '<pre>';
			// print_r($post);
			// echo '</pre>';
			$atts = [
				'control' => 'button',
				'type' => 'popup',
				// 'title' => 'sdf',
				'titlu' => 'Incearca reprogramare',
				'button_style' => 'normal',
				'speciality' => !empty($post['speciality_name']) ? $post['speciality_name'] : null,
				'location' => !empty($post['location_name']) ? $post['location_name'] : null,
				'service' => !empty($post['service_name']) ? $post['service_name'] : null,
				'physician' => !empty($post['physician_name']) ? $post['physician_name'] : null,
				'payment_type' => !empty($post['payment_type_name']) ? $post['payment_type_name'] : null,
				
				'firstname' => !empty($post['first_name']) ? $post['first_name'] : null,
				'lastname' => !empty($post['last_name']) ? $post['last_name'] : null,
				'email' => !empty($post['email']) ? $post['email'] : null,
				'phone' => !empty($post['phone']) ? $post['phone'] : null,
				'observations' => !empty($post['observations']) ? $post['observations'] : null,
				'data' => !empty($post['date']) ? $post['date'] : null,
				
				// 'speciality_key' => !empty($post['speciality_code']) ? $post['speciality_code'] : null,
				// 'location_key' => !empty($post['location_uid']) ? $post['location_uid'] : null,
				// 'service_key' => !empty($post['service_uid']) ? $post['service_uid'] : null,
				// 'physician_key' => !empty($post['physician_uid']) ? $post['physician_uid'] : null,
				// 'payment_type_key' => !empty($post['payment_type_id']) ? $post['payment_type_id'] : null,
			];
			// print_r($atts);
			// print_r($post);
			// echo '</pre>';
			// die;
			BizCalendar\wp_kses_post(setrio_bizcal_shortcode_vue($atts, 'Programeaza', 'bizcalv_popup'));
		}
	}
	if($should_refresh){
	?>
	<script>setTimeout(() => window.location.reload(), 5 * 60 * 1000)</script>
	<?php
	}
	?>
	</div>
	</div><!-- .entry-content -->
	</main>
	</div>
	<?php get_footer();
	// echo '<pre>';
	// var_dump($info);
	exit;
}
function setrio_bizcal_online_payment_mobilpay_confirm(){
	require_once __DIR__ . '/payment/MobilPay/composer-master/vendor/autoload.php';
	$errorCode 		= 0;
	$errorType		= Netopia\Payment\Request\PaymentAbstract::CONFIRM_ERROR_TYPE_NONE;
	$errorMessage	= '';
	$purchaseId = null;
	$panMasked = null;
	$uuid4 = null;
	$info = null;
	$action = null;
	$objPmReq = null;
	$obcontents = null;
	$force_permanent_error = false;
	$setrio_bizcal_online_payment_mobilpay_log_status = function() use (&$errorCode, &$errorType, &$errorMessage, &$uuid4, &$info, &$action, &$purchaseId, &$panMasked, &$objPmReq, &$obcontents){
		setrio_bizcal_online_payment_mobilpay_log($uuid4, [
			'errorCode' => $errorCode,
			'errorType' => $errorType,
			'errorMessage' => $errorMessage,
			'action' => $action,
			'server' => $_SERVER,
			'post' => $_POST,
			'info' => $info,
			'purchaseId' => $purchaseId,
			'panMasked' => $panMasked,
			'objPmReq' => $objPmReq,
			'response' => $obcontents,
		]);
	};
	$cipher     = 'rc4';
	$iv         = null;
	/* 
	if(!empty($_GET['test'])){
		$_POST = json_decode('{}', true);
		$_SERVER['REQUEST_METHOD'] = 'POST';
	} 
	*/
	if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') == 0)
	{
		if(array_key_exists('cipher', $_POST)){
			$cipher = (string)$_POST['cipher'];
			if(array_key_exists('iv', $_POST))
			{
				$iv = (string)$_POST['iv'];
			}
		}

		if(isset($_POST['env_key']) && isset($_POST['data']))
		{
			$mobilpay_test = get_option('setrio_bizcal_payment_mobilpay_test', 'sandbox');
			
			$mobilpay_signature = get_option('setrio_bizcal_payment_mobilpay_signature', '');
			#calea catre cheia privata
			#cheia privata este generata de mobilpay, accesibil in Admin -> Conturi de comerciant -> Detalii -> Setari securitate
			if ($mobilpay_test == 'sandbox') {
				$privateKeyFilePath 	= WP_CONTENT_DIR .'/Mobilpay/Certificates/sandbox.'.$mobilpay_signature.'private.key';
			}
			else {
				$privateKeyFilePath 	= WP_CONTENT_DIR .'/Mobilpay/Certificates/live.'.$mobilpay_signature.'private.key';
			}
			
			try
			{
				$objPmReq = Netopia\Payment\Request\PaymentAbstract::factoryFromEncrypted($_POST['env_key'], $_POST['data'], $privateKeyFilePath, null, $cipher, $iv);
				#uncomment the line below in order to see the content of the request
				//print_r($objPmReq);
				
				register_shutdown_function($setrio_bizcal_online_payment_mobilpay_log_status);
				
				$rrn = $objPmReq->objPmNotify->rrn;
				// action = status only if the associated error code is zero
				$uuid4 = $objPmReq->orderId;
				$info = get_transient('setrio_bizcal_appointment_payment_mobilpay_' . $uuid4);
				$purchaseId = $objPmReq->objPmNotify->purchaseId;
				$panMasked = $objPmReq->objPmNotify->panMasked;
				$action = $objPmReq->objPmNotify->action;
				if ($objPmReq->objPmNotify->errorCode == 0) {
					if(!$info){
						$errorType		= Netopia\Payment\Request\PaymentAbstract::CONFIRM_ERROR_TYPE_PERMANENT;
						$errorCode 		= Netopia\Payment\Request\PaymentAbstract::ERROR_CONFIRM_INVALID_POST_METHOD;
						$errorMessage 	= 'Payment Order expired or non-existing';
					} else {
						$confirmed = false;
						if(!empty($info['statuses'])){
							$confirmed = !!array_filter($info['statuses'], function($s){ return empty($s['type']) && empty($s['code']) && isset($s['action']) && $s['action']=='confirmed'; });
							if($confirmed){
								$errorType		= Netopia\Payment\Request\PaymentAbstract::CONFIRM_ERROR_TYPE_PERMANENT;
								$errorCode 		= Netopia\Payment\Request\PaymentAbstract::ERROR_CONFIRM_INVALID_POST_METHOD;
								$errorMessage 	= 'Order already confirmed';
							}
						}
					if(!$confirmed)
					switch($objPmReq->objPmNotify->action){
						#orice action este insotit de un cod de eroare si de un mesaj de eroare. Acestea pot fi citite folosind $cod_eroare = $objPmReq->objPmNotify->errorCode; respectiv $mesaj_eroare = $objPmReq->objPmNotify->errorMessage;
						#pentru a identifica ID-ul comenzii pentru care primim rezultatul platii folosim $id_comanda = $objPmReq->orderId;
						case 'confirmed':
							$force_permanent_error = true;
							$retur_bani = false;
							// throw new Exception('Fortat');
							#cand action este confirmed avem certitudinea ca banii au plecat din contul posesorului de card si facem update al starii comenzii si livrarea produsului
						//update DB, SET status = "confirmed/captured"
						$errorMessage = $objPmReq->objPmNotify->errorMessage;
						
						$price = $info['info']['pret'];
						$amount = floatval($price);
						$currency_code = 'EUR';
						if(preg_match('/\s+lei$/', $price)){
							$currency_code = 'RON';
						}
						if(abs(number_format($objPmReq->invoice->amount,0,'.','') - number_format($amount,0,'.',''))>1){
							$errorType		= Netopia\Payment\Request\PaymentAbstract::CONFIRM_ERROR_TYPE_PERMANENT;
							$errorCode 		= Netopia\Payment\Request\PaymentAbstract::ERROR_CONFIRM_INVALID_POST_METHOD;
							$errorMessage = "Invalid amount paid. Expected: " . ($amount) . ', Got: ' . $objPmReq->invoice->amount;
							
							$retur_bani = true;
						}
						try{
							// throw new Exception ('Fortat');
							setrio_bizcal_ajax_register_appointment($uuid4, (isset($info['post']) ? $info['post'] : []), (isset($info['server']) ? $info['server'] : []), ['purchaseId'  => $purchaseId, 'panMasked' => $panMasked]);
						} catch(Exception $e){
							$errorType		= Netopia\Payment\Request\PaymentAbstract::CONFIRM_ERROR_TYPE_PERMANENT;
							$errorCode 		= Netopia\Payment\Request\PaymentAbstract::ERROR_CONFIRM_INVALID_POST_METHOD;
							$errorMessage   = $e->getMessage();
							$retur_bani = true;
						}
						if(!(get_option('setrio_bizcal_payment_mobilpay_username', '') && get_option('setrio_bizcal_payment_mobilpay_password', ''))){
							$errorMessage   .= "<br /><h3>Returul banilor neefectuat, contactati clinica pentru a face returul banilor.</h3>";
						} elseif($retur_bani){
							if ($mobilpay_test == 'sandbox') {
								$soap = new SoapClient('http://sandboxsecure.mobilpay.ro/api/payment2/?wsdl', Array('cache_wsdl' => WSDL_CACHE_NONE));
							} else {
								$soap = new SoapClient('https://secure.mobilpay.ro/api/payment2/?wsdl', Array('cache_wsdl' => WSDL_CACHE_NONE));
							}
							//test mode WSDL location
							$loginReq = new stdClass();
							$loginReq->username = get_option('setrio_bizcal_payment_mobilpay_username', '');
							$loginReq->password = get_option('setrio_bizcal_payment_mobilpay_password', '');

							$loginResponse = $soap->logIn(Array('request' => $loginReq));
							$sessId = $loginResponse->logInResult->id;
							

							$sacId = $mobilpay_signature;


							// Credit example
							$req = new stdClass();
							$req->sessionId = $sessId; //the id we previously got from the login method
							$req->sacId = $sacId;
							$req->orderId = $uuid4;
							$req->amount = $objPmReq->invoice->amount; // amount to credit/capture

							// var_dump($req);
							// die;
							try
							{
								$response = $soap->credit(Array('request' => $req)); //credit
								
								
							// $response = $soap->capture(Array('request' => $req)); //capture

								if ($response->code != 0x00)
								{
									$errorMessage .= " Creditare esuata ([" . $response->code . "] " . $response->message . ")";
								} else {
									$errorMessage .= "<br /><h3>Suma platita de dumneavoastra a fost creditata inapoi pe card.</h3>";
								}
							}
							catch(SoapFault $e)
							{
								// $errorMessage .= " Creditare esuata SOAP ([" . $e->faultcode . "] " . $e->faultstring . ")<pre>" . print_r($e, true) . '</pre>';
								$errorMessage .= "<br /><p>Creditare esuata SOAP ([" . $e->faultcode . "] " . $e->faultstring . ")</p>";
							}
							catch(Exception $e)
							{
								$errorMessage .= "<br /><p>Eroare ([" . $e->getCode() . "] " . $e->getMessage() . ")</p>";
							}
						}
						
						
						break;
						case 'confirmed_pending':
							#cand action este confirmed_pending inseamna ca tranzactia este in curs de verificare antifrauda. Nu facem livrare/expediere. In urma trecerii de aceasta verificare se va primi o noua notificare pentru o actiune de confirmare sau anulare.
						//update DB, SET status = "pending"
						$errorMessage = $objPmReq->objPmNotify->errorMessage;
						break;
						case 'paid_pending':
							#cand action este paid_pending inseamna ca tranzactia este in curs de verificare. Nu facem livrare/expediere. In urma trecerii de aceasta verificare se va primi o noua notificare pentru o actiune de confirmare sau anulare.
						//update DB, SET status = "pending"
						$errorMessage = $objPmReq->objPmNotify->errorMessage;
						break;
						case 'paid':
							#cand action este paid inseamna ca tranzactia este in curs de procesare. Nu facem livrare/expediere. In urma trecerii de aceasta procesare se va primi o noua notificare pentru o actiune de confirmare sau anulare.
						//update DB, SET status = "open/preauthorized"
						$errorMessage = $objPmReq->objPmNotify->errorMessage;
						break;
						case 'canceled':
							#cand action este canceled inseamna ca tranzactia este anulata. Nu facem livrare/expediere.
						//update DB, SET status = "canceled"
						$errorMessage = $objPmReq->objPmNotify->errorMessage;
						break;
						case 'credit':
							#cand action este credit inseamna ca banii sunt returnati posesorului de card. Daca s-a facut deja livrare, aceasta trebuie oprita sau facut un reverse. 
						//update DB, SET status = "refunded"
						$errorMessage = $objPmReq->objPmNotify->errorMessage;
						break;
						default:
						$errorType		= Netopia\Payment\Request\PaymentAbstract::CONFIRM_ERROR_TYPE_PERMANENT;
						$errorCode 		= Netopia\Payment\Request\PaymentAbstract::ERROR_CONFIRM_INVALID_ACTION;
						$errorMessage 	= 'mobilpay_refference_action paramaters is invalid';
						break;
					}
					}
				}
				else {
					//update DB, SET status = "rejected"
					$errorMessage = $objPmReq->objPmNotify->errorMessage;
				}
			}
			catch(Exception $e)
			{
				$errorType 		= Netopia\Payment\Request\PaymentAbstract::CONFIRM_ERROR_TYPE_TEMPORARY;
				$errorCode		= $e->getCode();
				if(!$errorCode){
					$errorCode = Netopia\Payment\Request\PaymentAbstract::ERROR_CONFIRM_INVALID_POST_METHOD;
				}
				$errorMessage 	= $e->getMessage();
				if($force_permanent_error){
					$errorType 		= Netopia\Payment\Request\PaymentAbstract::CONFIRM_ERROR_TYPE_PERMANENT;
				}
			}
				
			if($info){
				$info2 = get_transient('setrio_bizcal_appointment_payment_mobilpay_' . $uuid4);
				if($info2){
					$info = $info2;
				}
				$info['statuses'][] = [
					'date' => gmdate("Y-m-d H:i:s"),
					'panMasked' => $panMasked,
					'purchaseId' => $purchaseId,
					'action' => $action,
					'type' => $errorType,
					'code' => $errorCode,
					'message' => $errorMessage,
				];
				
				set_transient('setrio_bizcal_appointment_payment_mobilpay_' . $uuid4, $info, 3600); // 1 ora
			}
		}
		else
		{
			$errorType 		= Netopia\Payment\Request\PaymentAbstract::CONFIRM_ERROR_TYPE_PERMANENT;
			$errorCode		= Netopia\Payment\Request\PaymentAbstract::ERROR_CONFIRM_INVALID_POST_PARAMETERS;
			$errorMessage 	= 'mobilpay.ro posted invalid parameters';
		}
	}
	else 
	{
		$errorType 		= Netopia\Payment\Request\PaymentAbstract::CONFIRM_ERROR_TYPE_PERMANENT;
		$errorCode		= Netopia\Payment\Request\PaymentAbstract::ERROR_CONFIRM_INVALID_POST_METHOD;
		$errorMessage 	= 'invalid request metod for payment confirmation';
	}

	header('Content-type: application/xml');
	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
	if($errorCode == 0)
	{
		BizCalendar\wp_kses_post("<crc><![CDATA[{$errorMessage}]]></crc>");
	}
	else
	{
		// header('HTTP/1.1 500 Aruncat Eroare', TRUE, 500);
		BizCalendar\wp_kses_post("<crc error_type=\"{$errorType}\" error_code=\"{$errorCode}\"><![CDATA[{$errorMessage}]]></crc>");
		if(isset($uuid4)){
			$clinicErrEmail = get_option('setrio_bizcal_err_email', '');
			$copy_to = preg_split('/[,\s]+/', strtolower($clinicErrEmail));
			$copy_to = array_map('trim', $copy_to);
			$copy_to = array_unique($copy_to);
			$copy_to = array_filter($copy_to, function($address){ 
				return filter_var(filter_var($address, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
			});
			$email_to = array_shift($copy_to);
			if(!empty($email_to)){
				$headers = [];
				foreach($copy_to as $email){
					$headers[] = 'Cc: '.$email;
				}
				$mail_info = $info && !empty($info['info']) ? $info['info'] : [];
				$server = $info && !empty($info['server']) ? $info['server'] : $_SERVER;
				$ip = '';
				if (!empty($SERVER['HTTP_CLIENT_IP'])) {
					$ip = $SERVER['HTTP_CLIENT_IP'];
				} elseif (!empty($SERVER['HTTP_X_FORWARDED_FOR'])) {
					$ip = $SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
					$ip = $SERVER['REMOTE_ADDR'];
				}
				$mail_info['uuid4'] = $uuid4;
				$mail_info['action'] = $action;
				$mail_info['error_type'] = $errorType;
				$mail_info['error_code'] = $errorCode;
				$mail_info['error_message'] = $errorMessage;
				$mail_info['ip'] = $ip;
				$mail_info['date'] = gmdate("Y-m-d H:i:s");
				$mail_info['user_agent'] = isset($server['HTTP_USER_AGENT']) ? $server['HTTP_USER_AGENT'] : '';
				$mail_info['user_accept_language'] = isset($server['HTTP_ACCEPT_LANGUAGE']) ? $server['HTTP_ACCEPT_LANGUAGE'] : '';
				foreach($post as $k=>$v){
					$mail_info['post_' . $k] = json_encode($v);
				}
				foreach($server as $k=>$v){
					$mail_info['server_' . $k] = json_encode($v);
				}
				$appointmentFailedEmailSubject = setrio_bizcal_message('msgAppointmentFailedMobilPayEmailSubject');
				if('' === trim($appointmentFailedEmailSubject)){
					$appointmentFailedEmailSubject = 'Programare client esuata - Mobilpay';
				} else {
					$appointmentFailedEmailSubject = setrio_bizcal_replace_tags_sprintf($appointmentFailedEmailSubject, $mail_info);
				}
				$appointmentFailedEmailBody = setrio_bizcal_message('msgAppointmentFailedMobilPayEmailBody');
				if('' === trim($appointmentFailedEmailBody)){
					$appointmentFailedEmailBody = '<pre>' . json_encode($mail_info, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . '</pre>';
				} else {
					$appointmentFailedEmailBody = setrio_bizcal_replace_tags_sprintf($appointmentFailedEmailBody, $mail_info);
				}
				$mail_sent = wp_mail(
					$email_to,
					$appointmentFailedEmailSubject,
					$appointmentFailedEmailBody,
					$headers
				);
			}
		}
	}
	
	$obcontents = ob_get_contents();
	
	exit;
}
function setrio_bizcal_online_payment_mobilpay_log($uuid4 = null, $message = '') { 
	if(empty($uuid4)){
		$uuid4 = gmdate('Y-m-d-H-i-s-u');
	}
	$log_folder = WP_CONTENT_DIR .'/Mobilpay/Logs/';
	if(!is_dir($log_folder)){
		$created = wp_mkdir_p($log_folder);
	}
	$message = json_encode($message, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	
	$now = DateTime::createFromFormat('U.u', microtime(true));
	
	$string_to_encrypt = $now->format("Y-m-d H:i:s.u") . ': ' . $message . PHP_EOL;
	$encrypted_string = $string_to_encrypt;
	// $mobilpay_signature = get_option('setrio_bizcal_payment_mobilpay_signature', '');
	
	// $password = $mobilpay_signature;
	/* if(function_exists('openssl_encrypt')){
		$encrypted_string=openssl_encrypt($string_to_encrypt,"AES-128-ECB",$password);
	} */
	if (!class_exists('WP_Filesystem')){
		require_once(ABSPATH.'wp-admin/includes/class-wp-filesystem-base.php');
	}
	if (!class_exists('WP_Filesystem_Direct')){
		require_once(ABSPATH.'wp-admin/includes/class-wp-filesystem-base.php');
		require_once(ABSPATH.'wp-admin/includes/class-wp-filesystem-direct.php');
	}
	WP_Filesystem();
	global $wp_filesystem;
	
	$wp_filesystem->put_contents($log_folder . $uuid4 . '.log', $filesystem->get_contents($log_folder . $uuid4 . '.log') . $encrypted_string);
}
function setrio_bizcal_online_payment_mobilpay($info, $force_uuid4 = false, $post=null){
	require_once __DIR__ . '/payment/MobilPay/composer-master/vendor/autoload.php';
	$form_action = 'https://sandboxsecure.mobilpay.ro';
	
	$mobilpay_test = get_option('setrio_bizcal_payment_mobilpay_test', 'sandbox');
	if ($mobilpay_test == 'live') {
		$form_action = 'https://secure.mobilpay.ro';
	}
	
	$mobilpay_signature = get_option('setrio_bizcal_payment_mobilpay_signature', '');
	if ($mobilpay_test == 'sandbox') {
		$x509FilePath 	= WP_CONTENT_DIR .'/Mobilpay/Certificates/sandbox.'.$mobilpay_signature.'.public.cer';
	}
	else {
		$x509FilePath 	= WP_CONTENT_DIR .'/Mobilpay/Certificates/live.'.$mobilpay_signature.'.public.cer';
	}
	
	$price = $info['pret'];
	$amount = floatval($price);
	$currency_code = 'EUR';
	if(preg_match('/\s+lei$/', $price)){
		$currency_code = 'RON';
	}
	
	$firstname = $info['prenume'];
	$lastname = $info['numefam'];
	$phone = $info['telefon_client'];
	$email = $info['email_client'];
	
	try
	{	
		$uuid4 = $force_uuid4 ? $force_uuid4 : wp_generate_uuid4();
		if(!$force_uuid4){
			set_transient('setrio_bizcal_appointment_payment_mobilpay_' . $uuid4, array('post' => $_POST, 'info' => $info, 'server' => $_SERVER), 3600); // 1 ora
		}
		
		$objPmReqCard = new Netopia\Payment\Request\Card();			
		
		$objPmReqCard->signature = $mobilpay_signature;

		$objPmReqCard->orderId   = $uuid4;
		
		$objPmReqCard->confirmUrl 			= esc_url( add_query_arg( 'setrio-bizcal-mobilpay-confirm', 1, site_url()) );
		$objPmReqCard->returnUrl 			= esc_url( add_query_arg( 'setrio-bizcal-mobilpay-status', 1, site_url()) ) ;
		
		// $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		#detalii cu privire la plata: moneda, suma, descrierea
		$objPmReqCard->invoice = new Netopia\Payment\Invoice();
		$objPmReqCard->invoice->currency	= $currency_code;
		$objPmReqCard->invoice->amount		= $amount;
		$objPmReqCard->invoice->installments	= '1,2,3,4,5,6,7,8,9,10,11,12';
		$objPmReqCard->invoice->details		= setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('msgAppointmentPaymentDetails'), $info);
		
		#detalii cu privire la adresa posesorului cardului	
		$billingAddress 				= new Netopia\Payment\Address();
		$billingAddress->type			= 'person';
		$billingAddress->firstName		= $firstname;
		$billingAddress->lastName		= $lastname;
		// $billingAddress->country		= $order_info['payment_iso_code_3'];			
		// $billingAddress->county			= $order_info['payment_iso_code_2'];;
		// $billingAddress->city			= $order_info['payment_city'];
		// $billingAddress->zipCode		= $order_info['payment_postcode'];
		// $billingAddress->address		= $order_info['payment_address_1'];
		$billingAddress->email			= $email;
		$billingAddress->mobilePhone	= $phone;
		$objPmReqCard->invoice->setBillingAddress($billingAddress);
		
		#detalii cu privire la adresa de livrare
		/* $shippingAddress 				= new Mobilpay_Payment_Address();
		
		$shippingAddress->type			= 'person';
		$shippingAddress->firstName		= $order_info['shipping_firstname'];
		$shippingAddress->lastName		= $order_info['shipping_lastname'];
		$shippingAddress->country		= $order_info['shipping_country'];			
		$shippingAddress->city			= $order_info['shipping_city'];
		$shippingAddress->zipCode		= $order_info['shipping_postcode'];
		$shippingAddress->address		= $order_info['shipping_address_1'];
		$shippingAddress->email			= $order_info['email'];
		$shippingAddress->mobilePhone	= $order_info['telephone'];
		$objPmReqCard->invoice->setShippingAddress($shippingAddress); */
		
		// echo '<pre>';
		// print_r($objPmReqCard);
		// die;
		$objPmReqCard->encrypt($x509FilePath);

	}
	catch(Exception $e)
	{
		echo '<pre>';
		print_r(wp_kses_post($e->getMessage()));
		die;
	}
	
	$env_key = $objPmReqCard->getEnvKey();
	$data = $objPmReqCard->getEncData();
	ob_start();
?>
<form action="<?php echo esc_attr($form_action); ?>" method="post" id="bizcalendar-payment">
	<input type="hidden" name="env_key" value="<?php echo esc_attr($objPmReqCard->getEnvKey());?>"/>
	<input type="hidden" name="data" value="<?php echo esc_attr($objPmReqCard->getEncData());?>"/>
	<input type="hidden" name="cipher" value="<?php echo esc_attr($objPmReqCard->getCipher());?>" /> 
	<input type="hidden" name="iv" value="<?php echo esc_attr($objPmReqCard->getIv());?>" />
	<button type="submit" class="button">Reincearca plata</button>
</form>
<?php
// echo '<pre>';print_r($post); print_r($info); echo '</pre>';
	$form = ob_get_clean();
	if($force_uuid4){
		return $form;
	}
	$response = ["ErrorCode" => 0, "ErrorMessage" => ''];
	$response['form'] = $form;
	$response['message'] = 'In curs de redirectionare catre procesatorul de plati...';
	wp_send_json(json_encode($response));
die;
}
function setrio_bizcal_online_general(){
	return get_option('setrio_bizcal_payment_mobilpay_general', 0);
}
function setrio_bizcal_online_enabled($validate = false){
	$paymentMobilPayStatus = get_option('setrio_bizcal_payment_mobilpay_status', 0) && get_option('setrio_bizcal_payment_mobilpay_signature', '');
	if($paymentMobilPayStatus){
		$paymentMobilPayAdmin = get_option('setrio_bizcal_payment_mobilpay_admin', 0);
		if($paymentMobilPayAdmin && !current_user_can('administrator')){
			$paymentMobilPayStatus = false;
		}
	}
	if($paymentMobilPayStatus){
		if($validate){
			$paymentMobilPayStatus = setrio_bizcal_online_valid();
		}
	}
	return $paymentMobilPayStatus;
}
function setrio_bizcal_online_valid(){
	$mobilpay_test = get_option('setrio_bizcal_payment_mobilpay_test', 'sandbox');
	$mobilpay_signature = get_option('setrio_bizcal_payment_mobilpay_signature', '');
	$paymentMobilPayStatus = false;
	if($mobilpay_signature){
		if ($mobilpay_test == 'sandbox') {
			$x509FilePath 	= WP_CONTENT_DIR .'/Mobilpay/Certificates/sandbox.'.$mobilpay_signature.'.public.cer';
		}
		else {
			$x509FilePath 	= WP_CONTENT_DIR .'/Mobilpay/Certificates/live.'.$mobilpay_signature.'.public.cer';
		}
		if ($mobilpay_test == 'sandbox') {
			$privateKeyFilePath 	= WP_CONTENT_DIR .'/Mobilpay/Certificates/sandbox.'.$mobilpay_signature.'private.key';
		}
		else {
			$privateKeyFilePath 	= WP_CONTENT_DIR .'/Mobilpay/Certificates/live.'.$mobilpay_signature.'private.key';
		}
		$paymentMobilPayStatus = is_file($x509FilePath) && is_file($privateKeyFilePath);
	}
	return $paymentMobilPayStatus;
}
function setrio_bizcal_online_payment($info){
	return setrio_bizcal_online_payment_mobilpay($info);
}
function setrio_bizcal_zip_logs(){
	$log_folder = WP_CONTENT_DIR .'/Mobilpay/Logs/';
	$zip = new ZipArchive;
	$zip->open($log_folder . gmdate('Y-m-d') . '.zip', ZipArchive::CREATE);
	
	$files = glob($log_folder . '.log');
	if ($files) {
		foreach ($files as $file) {
			$zip->addFile($file, basename($file));
		}
	}
	$zip->close();

}
function setrio_bizcal_ajax_register_appointment($uuid4 = null, $post = null, $server = null, $transaction_details = null){
	$wsFakeRegister = get_option('setrio_bizcal_fake_register', 0);
	
	$is_internal = isset($post);
	$POST = isset($post) ? $post : $_POST;
	$SERVER = isset($server) ? $server : $_SERVER;
	
	global $setrio_bizcal_debug, $setrio_bizcal_securemode, $setrio_bizcal_reCaptchaResponse, $setrio_bizcal_reCaptcha;

	if ($setrio_bizcal_securemode)
		check_ajax_referer("getMedicalSpecialities");
	$default_capsType = get_option('setrio_bizcal_caps', '');
	$capsType_speciality = get_option('setrio_bizcal_caps_speciality', 'lcaseucfirst');
	
	if(!$capsType_speciality){
		$capsType_speciality = $default_capsType;
	}
	if(!$capsType_speciality || ('none'==$capsType_speciality)){
		$capsType_speciality = false;
	}
	
	$capsType_location = get_option('setrio_bizcal_caps_location', 'lcaseucwords');
	if(!$capsType_location){
		$capsType_location = $default_capsType;
	}
	if(!$capsType_location || ('none'==$capsType_location)){
		$capsType_location = false;
	}
	$capsType_physician = get_option('setrio_bizcal_caps_physician', 'lcaseucwords');
	if(!$capsType_physician){
		$capsType_physician = $default_capsType;
	}
	if(!$capsType_physician || ('none'==$capsType_physician)){
		$capsType_physician = false;
	}
	$capsType_service = get_option('setrio_bizcal_caps_service', 'lcaseucfirst');
	if(!$capsType_service){
		$allCaps = (bool)get_option('setrio_bizcal_all_caps', false);
		if($allCaps){
			$capsType_service = 'ucase';
		} else {
			$capsType_service = get_option('setrio_bizcal_caps', '');
		}
	}
	if(!$capsType_service || ('none'==$capsType_service)){
		$capsType_service = false;
	}
	$capsType_payment_type = get_option('setrio_bizcal_caps_payment_type', '');
	if(!$capsType_payment_type){
		$capsType_payment_type = $default_capsType;
	}
	if(!$capsType_payment_type || ('none'==$capsType_payment_type)){
		$capsType_payment_type = false;
	}
	
	try {
		if (!$is_internal && $setrio_bizcal_reCaptcha) {
			if (!empty($POST["recaptcha"])) {
				$setrio_bizcal_reCaptchaResponse = $setrio_bizcal_reCaptcha->verifyResponse(
					$SERVER["REMOTE_ADDR"],
					sanitize_text_field($POST["recaptcha"])
				);

				if ($setrio_bizcal_reCaptchaResponse == null)
					throw new Exception(setrio_bizcal_message('lblReCaptchaFieldNotValid') . '<span style="display:none;">(1)</span>');
				if (!$setrio_bizcal_reCaptchaResponse->success)
					throw new Exception(setrio_bizcal_message('lblReCaptchaFieldNotValid') . '<span style="display:none;">(2)</span>');
			} else
				throw new Exception(setrio_bizcal_message('lblReCaptchaFieldNotValid') . '<span style="display:none;">(3)</span>');
		}
		$error = isset($POST['error']) ? (array)$POST['error'] : null;
		$error_code = isset($POST['error_code']) ? $POST['error_code'] : 0;
		$error_message = isset($POST['error_message']) ? $POST['error_message'] : '';
		$notify_only = !empty($POST['notify_only']);
		$registered = false;
		$no_notify = false;
		
		$terms = null;
		if (get_option('setrio_bizcal_enable_terms', 1)) {
			$terms = !empty($POST['terms']);
		}
		$data_policy = null;
		if (get_option('setrio_bizcal_enable_data_policy', 1)) {
			$data_policy = !empty($POST['data_policy']);
		}

		$newsletter = null;
		if (get_option('setrio_bizcal_enable_newsletter', 1)) {
			$newsletter = !empty($POST['newsletter']);
		}
		

		$enableMultipleLocations = get_option('setrio_bizcal_enable_multiple_locations', false);

		if (isset($POST['location_uid']))
			$locationUID = sanitize_text_field($POST['location_uid']);
		else
			$locationUID = null;

		if (!$notify_only && empty($locationUID) && ($enableMultipleLocations))
			throw new Exception("Nu ați selectat locația dorită");
		
		
		$onlinePay = !empty($POST['online_pay']) ? filter_var($POST['online_pay'], FILTER_VALIDATE_BOOLEAN) : false;
		
		if(!$is_internal){
			$requestParams = ["SpecialityCode" => sanitize_text_field($POST['speciality_code'])];
			if ($enableMultipleLocations && $locationUID)
				$requestParams["LocationUID"] = $locationUID;
			if (!empty($POST['physician_uid']))
				$requestParams["PhysicianUID"] = sanitize_text_field($POST['physician_uid']);

			$serviceParams = json_encode($requestParams);

			$client = new SetrioBizCal_BizMedicaServiceClient();

			$response = $client->getMedicalServices($serviceParams);
			if (!setrio_bizcal_is_valid_json($response))
				throw new Exception(setrio_bizcal_parse_service_exception($response));

			$items = array();
			$services = json_decode($response);
			if (($services->ErrorCode == 0) && ($services->ErrorMessage == "")) {
				foreach ($services->MedicalServices as $medicalService) {
					if($medicalService->UID != $POST['service_uid']) continue;
					$isEuro = false;
					$onlinePay = false;
					if (property_exists($medicalService, "IsEuro"))
						$isEuro = $medicalService->IsEuro;
					
					if (property_exists($medicalService, "PrepaymentIsMandatoryForOnlineAppointment"))
						$onlinePay = !!$medicalService->PrepaymentIsMandatoryForOnlineAppointment;
				}
			}
		}
		
		$online_payment = false;
		if(!$notify_only){
			$online_general = setrio_bizcal_online_general();
			$online_payment = setrio_bizcal_online_enabled();
			if(empty($POST['price']) || floatval($POST['price']) <= 0.000000001){
				$online_payment = false;
				if(get_option('setrio_bizcal_payment_mobilpay_free', 0)){
					$online_payment = true;
					if(!get_option('setrio_bizcal_payment_mobilpay_free_cnas', 0) && $POST['payment_type_id'] == 2){
						$online_payment = false;
					}
				}
			}
			if($online_payment && !setrio_bizcal_online_valid()){
				throw new Exception("Metoda de plata incorect configurata");
			}
			if($online_payment && !$online_general && !$onlinePay){
				$online_payment = false;
			}
		}
		
		// var_dump($onlinePay); die;
		
		$date = sanitize_text_field(@$POST['date']);
		if(!empty($POST['start_date'])){
			$formattedStartDate = sanitize_text_field(@$POST['start_date']);
		} else {
			$formattedStartDate = preg_replace('/[^0-9]/','',$date);
		}
		if(!empty($POST['end_date'])){
			$formattedEndDate = sanitize_text_field(@$POST['end_date']);
		} else {
			$formattedEndDate = preg_replace('/[^0-9]/','',$date);
		}
		$formattedAppointmentDate = substr($formattedStartDate, 6, 2) . '.' . substr($formattedStartDate, 4, 2) . '.' . substr($formattedStartDate, 0, 4) . ' ' .
			substr($formattedStartDate, 9, 2) . ':' . substr($formattedStartDate, 12, 2) . ' - ' .
			substr($formattedEndDate, 9, 2) . ':' . substr($formattedEndDate, 12, 2);

		$formattedStartDate2 = substr($formattedStartDate, 6, 2) . '.' . substr($formattedStartDate, 4, 2) . '.' . substr($formattedStartDate, 0, 4) . ' ' .
			substr($formattedStartDate, 9, 2) . ':' . substr($formattedStartDate, 12, 2);

		$formattedEndDate2 = substr($formattedStartDate, 6, 2) . '.' . substr($formattedStartDate, 4, 2) . '.' . substr($formattedStartDate, 0, 4) . ' ' .
			substr($formattedEndDate, 9, 2) . ':' . substr($formattedEndDate, 12, 2);

		$formattedDateInterval = substr($formattedStartDate, 9, 2) . ':' . substr($formattedStartDate, 12, 2) . ' - ' .
			substr($formattedEndDate, 9, 2) . ':' . substr($formattedEndDate, 12, 2);


		
		$clinicPhone = get_option('setrio_bizcal_phone', '');
		$clinicEmail = get_option('setrio_bizcal_email', '');

		$info = array(
			'nume' => sanitize_text_field(@$POST['first_name']) . ' ' . sanitize_text_field(@$POST['last_name']),
			'telefon' => sanitize_text_field(@$POST['phone']),
			'email' => sanitize_text_field(@$POST['email']),
			'specialitatea' => setrio_bizcal_caps((isset($POST['speciality_name']) ? sanitize_text_field($POST['speciality_name']) : sanitize_text_field(@$POST['speciality_code'])), $capsType_speciality),
			'locatia' => setrio_bizcal_caps(sanitize_text_field(@$POST['location_name']),$capsType_location),
			'serviciu' => setrio_bizcal_caps(sanitize_text_field(@$POST['service_name']), $capsType_service),
			'plata' => setrio_bizcal_caps((isset($POST['payment_type_name']) ? sanitize_text_field($POST['payment_type_name']) : (@$POST['payment_type_id'] == 2 ? (@$POST['speciality_code'] == "MEDICINA DE FAMILIE" ? setrio_bizcal_message('lblPaymentTypeCNAS') : setrio_bizcal_message('lblPaymentTypeTicket')) : setrio_bizcal_message('lblPaymentTypeCashCard'))), $capsType_payment_type),
			'medic' => setrio_bizcal_caps(sanitize_text_field(@$POST['physician_name']), $capsType_physician),
			'data' => $formattedAppointmentDate,
			'pret' => sanitize_text_field(@$POST['price']),
			'observatii' => sanitize_textarea_field(@$POST['observations']),
			'acord_termeni' => get_option('setrio_bizcal_enable_terms') ? (!empty($POST['terms']) ? setrio_bizcal_message('textYes') : setrio_bizcal_message('textNo')) : setrio_bizcal_message('textNA'),
			'acord_gdpr' => get_option('setrio_bizcal_enable_data_policy') ? (!empty($POST['data_policy']) ? setrio_bizcal_message('textYes') : setrio_bizcal_message('textNo')) : setrio_bizcal_message('textNA'),
			'acord_newsletter' => get_option('setrio_bizcal_enable_newsletter') ? (!empty($POST['newsletter']) ? setrio_bizcal_message('textYes') : setrio_bizcal_message('textNo')) : setrio_bizcal_message('textNA'),
			'prenume' => sanitize_text_field(@$POST['first_name']),
			'numefam' => sanitize_text_field(@$POST['last_name']),
			'data_st' => $formattedStartDate2,
			'data_sf' => $formattedEndDate2,
			'orele' => $formattedDateInterval,
			'telefon_client' => sanitize_text_field(@$POST['phone']),
			'email_client' => sanitize_text_field(@$POST['email']),
			'telefon_clinica' => $clinicPhone,
			'email_clinica' => $clinicEmail,
			'data_preferata' => $date,
		);
		
		$max_register_per_ip = (int)get_option('setrio_bizcal_max_register_per_ip', 0);
		if($max_register_per_ip){
			if (!empty($SERVER['HTTP_CLIENT_IP'])) {
				$ip = $SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = $SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $SERVER['REMOTE_ADDR'];
			}
			
			$cur_nr = (int)get_transient('setrio_bizcal_ip_' . $ip);
			if($cur_nr >= $max_register_per_ip){
				throw new Exception("Limita de programari depasita");
			}
			
			if(!$is_internal && $online_payment){
				return setrio_bizcal_online_payment($info);
			}
			
			set_transient('setrio_bizcal_ip_' . $ip, $cur_nr+1, 86400);
		} else {
			if(!$is_internal && $online_payment){
				return setrio_bizcal_online_payment($info);
			}
		}
		
		$aux_observations = "";

		$addServiceNameInObservations = (bool)get_option('setrio_bizcal_add_service_to_obs', false);

		if (($addServiceNameInObservations) && (isset($POST['service_name'])))
			$aux_observations .= sanitize_text_field($POST['service_name']) . "\r\n\r\n";

		if ($POST['payment_type_id'] == 2)
			$aux_observations .= setrio_bizcal_message('lblAuxObservationsCNAS') . "\r\n\r\n";

		$service_uid = sanitize_text_field($POST['service_uid']);

		if ((!$service_uid) || ($service_uid === "0"))
			$service_uid = null;
		
		$response = ["ErrorCode" => 0, "ErrorMessage" => ''];
		if(!$notify_only){
			$params = [
				"SpecialityCode" => sanitize_text_field(@$POST['speciality_code']),
				"PhysicianUID" => sanitize_text_field(@$POST['physician_uid']),
				"ServiceUID" => sanitize_text_field($service_uid),
				"PaymentTypeID" => sanitize_text_field(@$POST['payment_type_id']),
				"StartDate" => sanitize_text_field(@$POST['start_date']),
				"EndDate" => sanitize_text_field(@$POST['end_date']),
				"LastName" => sanitize_text_field(@$POST['last_name']),
				"FirstName" => sanitize_text_field(@$POST['first_name']),
				"Phone" => sanitize_text_field(@$POST['phone']),
				"Email" => sanitize_text_field(@$POST['email']),
				"Observations" => $aux_observations . sanitize_textarea_field(@$POST['observations']),
				"LocationUID" => $locationUID,
				"AcordSiteTermeniSiConditii" => $terms,
				"AcordSiteGDPRProgramare" => $data_policy,
				"AcordSiteMKTNewsletter" => $newsletter,
			];
			
			if ($enableMultipleLocations){
				$params['LocationUID'] = $locationUID;
			}
			if($is_internal){
				$params['PlatitCuCardul'] = 1;
				if($transaction_details){
					$params['OnLineOrderID'] = $uuid4;
					if($transaction_details['purchaseId']){
						$params['PurchaseId'] = $transaction_details['purchaseId'];
					}
					if($transaction_details['panMasked']){
						$params['CardMascat'] = $transaction_details['panMasked'];
					}
				}
			}
			$params = json_encode($params);
			$client = new SetrioBizCal_BizMedicaServiceClient();
			if($wsFakeRegister){
				$response = json_encode(array(
					'ErrorCode' => 0,
					'ErrorMessage' => '',
				));
			} else {
				$response = $client->registerAppointment($params);
			}

			if (!setrio_bizcal_is_valid_json($response))
				throw new Exception(setrio_bizcal_parse_service_exception($response));
			
			if($is_internal){
				setrio_bizcal_online_payment_mobilpay_log($uuid4, ['registerAppointment_response' => $response]);
			}
			$response = json_decode($response, true);
			
			if(isset($response['ErrorMessage']) && ($response['ErrorMessage'] == "Intervalul de timp solicitat pentru programare nu este disponibil.")){
				$no_notify = true;
				$response['ErrorMessage'] = setrio_bizcal_message('msgErrRegisterAppointmentFailed');
			}
			
			if(empty($response['ErrorCode']) && empty($response['ErrorMessage'])){
				$registered = true;
			} else {
				if($is_internal){
					throw new Exception($response['ErrorMessage'], intval($response['ErrorCode']));
				}
			}
			if(!empty($response['ErrorCode'])){
				$error_code = $response['ErrorCode'];
			}
			if(!empty($response['ErrorMessage'])){
				$error_message = $response['ErrorMessage'];
			}
		}
		
		if(!$no_notify && ($notify_only || !$registered)){
			$notify_only = true;
		}

		
		$info['error_code'] = $error_code;
		$info['error_message'] = $error_message;
		
		add_filter('wp_mail_content_type', 'setrio_bizcal_set_email_content_type');

		$ics = setrio_bizcal_ics($info);
		$info['ics_href'] = 'data:text/calendar;charset=utf8,' . htmlspecialchars($ics, ENT_QUOTES) . '';
		$vcs = str_replace("\r\nVERSION:2.0\r\n", "\r\nVERSION:1.0\r\n", $ics);
		$info['vcs_href'] = 'data:text/calendar;charset=utf8,' . htmlspecialchars($vcs, ENT_QUOTES) . '';
		$info['cal_img_src'] = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA2NCA2NCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNjQgNjQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj48c3R5bGUgdHlwZT0idGV4dC9jc3MiPiAuc3Qwe2ZpbGw6Izc3QjNENDt9IC5zdDF7b3BhY2l0eTowLjI7fSAuc3Qye2ZpbGw6IzIzMUYyMDt9IC5zdDN7ZmlsbDojRkZGRkZGO30gLnN0NHtmaWxsOiNDNzVDNUM7fSAuc3Q1e2ZpbGw6IzRGNUQ3Mzt9IC5zdDZ7ZmlsbDojRTBFMEQxO30gPC9zdHlsZT48ZyBpZD0iTGF5ZXJfMSI+PGc+PGNpcmNsZSBjbGFzcz0ic3QwIiBjeD0iMzIiIGN5PSIzMiIgcj0iMzIiLz48L2c+PGc+PGcgY2xhc3M9InN0MSI+PHBhdGggY2xhc3M9InN0MiIgZD0iTTEyLDI1djI1YzAsMi4yLDEuOCw0LDQsNGgzMmMyLjIsMCw0LTEuOCw0LTRWMjVIMTJ6Ii8+PC9nPjxnPjxwYXRoIGNsYXNzPSJzdDMiIGQ9Ik0xMiwyM3YyNWMwLDIuMiwxLjgsNCw0LDRoMzJjMi4yLDAsNC0xLjgsNC00VjIzSDEyeiIvPjwvZz48ZyBjbGFzcz0ic3QxIj48cGF0aCBjbGFzcz0ic3QyIiBkPSJNNDgsMTRIMTZjLTIuMiwwLTQsMS44LTQsNHY3aDQwdi03QzUyLDE1LjgsNTAuMiwxNCw0OCwxNHoiLz48L2c+PGc+PHBhdGggY2xhc3M9InN0NCIgZD0iTTQ4LDEySDE2Yy0yLjIsMC00LDEuOC00LDR2N2g0MHYtN0M1MiwxMy44LDUwLjIsMTIsNDgsMTJ6Ii8+PC9nPjxnPjxwYXRoIGNsYXNzPSJzdDUiIGQ9Ik0zMiw0OGMtMS4xLDAtMi0wLjktMi0yYzAtNS41LDEuOC05LjUsMy41LTEySDI3Yy0xLjEsMC0yLTAuOS0yLTJzMC45LTIsMi0yaDExYzAuOSwwLDEuNiwwLjYsMS45LDEuNCBzMCwxLjctMC43LDIuMkMzOSwzMy44LDM0LDM3LjUsMzQsNDZDMzQsNDcuMSwzMy4xLDQ4LDMyLDQ4eiIvPjwvZz48ZyBjbGFzcz0ic3QxIj48cGF0aCBjbGFzcz0ic3QyIiBkPSJNMjAsMjFjLTEuMSwwLTItMC45LTItMnYtN2MwLTEuMSwwLjktMiwyLTJsMCwwYzEuMSwwLDIsMC45LDIsMnY3QzIyLDIwLjEsMjEuMSwyMSwyMCwyMUwyMCwyMXoiLz48L2c+PGcgY2xhc3M9InN0MSI+PHBhdGggY2xhc3M9InN0MiIgZD0iTTQ1LDIxYy0xLjEsMC0yLTAuOS0yLTJ2LTdjMC0xLjEsMC45LTIsMi0ybDAsMGMxLjEsMCwyLDAuOSwyLDJ2N0M0NywyMC4xLDQ2LjEsMjEsNDUsMjFMNDUsMjF6Ii8+PC9nPjxnPjxwYXRoIGNsYXNzPSJzdDYiIGQ9Ik0yMCwxOWMtMS4xLDAtMi0wLjktMi0ydi03YzAtMS4xLDAuOS0yLDItMmwwLDBjMS4xLDAsMiwwLjksMiwydjdDMjIsMTguMSwyMS4xLDE5LDIwLDE5TDIwLDE5eiIvPjwvZz48Zz48cGF0aCBjbGFzcz0ic3Q2IiBkPSJNNDUsMTljLTEuMSwwLTItMC45LTItMnYtN2MwLTEuMSwwLjktMiwyLTJsMCwwYzEuMSwwLDIsMC45LDIsMnY3QzQ3LDE4LjEsNDYuMSwxOSw0NSwxOUw0NSwxOXoiLz48L2c+PC9nPjwvZz48ZyBpZD0iTGF5ZXJfMiI+PC9nPjwvc3ZnPiA=';
		
		
		$info['order_id'] = $uuid4;
		if($transaction_details){
			if($transaction_details['purchaseId']){
				$info['purchase_id'] = $transaction_details['purchaseId'];
			}
			if($transaction_details['panMasked']){
				$info['card'] = $transaction_details['panMasked'];
			}
		}

		$info2 = $info;
		$info2['email'] = $info2['email_clinica'];
		$info2['telefon'] = $info2['telefon_clinica'];
		$info_wo_loc = $info;
		$info2_wo_loc = $info2;
		unset($info_wo_loc['locatia']);
		unset($info2_wo_loc['locatia']);
		$message = null;
		if ($notify_only || $registered) {
			
			$appointmentEmailTo = get_option('setrio_bizcal_appointment_email_to');
			$appointmentEmailTo = trim($appointmentEmailTo);
			
			$appointmentEmailSubject = get_option('setrio_bizcal_appointment_email_subject');
			if('' === trim($appointmentEmailSubject)){
				$appointmentEmailSubject = 'Programare client';
			}
			if($notify_only){
				$appointmentEmailSubject = setrio_bizcal_message('subjRegisterAppointmentFailed');
			}
			if (!$enableMultipleLocations){
				$appointmentEmailBody = setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('msgAppointmentConfirmationEmail'), $info_wo_loc);
			} else {
				$appointmentEmailBody = setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('msgAppointmentConfirmationEmailWithLocation'), $info);
			}
			if($notify_only){
				$appointmentEmailBody = setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('msgAppointmentFailedEmail'), $info);
			}
			if($notify_only){
				$message = setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('lblAppointmentNotification'), $info2);
			} else {
				if (!$enableMultipleLocations){
					$message = setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('lblAppointmentConfirmation'), $info2_wo_loc);
				} else {
					$message = setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('lblAppointmentConfirmationWithLocation'), $info2);
				}
			}
			
			if ($appointmentEmailTo) {
				$headers = [];
				if($notify_only){
					$clinicErrEmail = get_option('setrio_bizcal_err_email', '');
					$copy_to = preg_split('/[,\s]+/', strtolower($clinicErrEmail));
					$copy_to = array_map('trim', $copy_to);
					$copy_to = array_unique($copy_to);
					$copy_to = array_filter($copy_to, function($address){
						return filter_var(filter_var($address, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
					});
					foreach($copy_to as $email){
						if($email == strtolower($appointmentEmailTo)) continue;
						
						$headers[] = 'Cc: '.$email;
					}
				}
				$mail_sent = wp_mail(
					$appointmentEmailTo,
					setrio_bizcal_replace_tags_sprintf($appointmentEmailSubject, $info),
					$appointmentEmailBody,
					$headers
				);
				if(!$mail_sent){
					$message = '<p>Nu s-a putut trimite email. Trimiterea de email a esuat.</p>' . $message;
				}
			} else {
				$message = '<p>Nu s-a putut trimite email. Acesta nu a fost configurat.</p>' . $message;
			}
		}
		
		$uuid4 = !empty($uuid4) ? $uuid4 : wp_generate_uuid4();
		set_transient('setrio_bizcal_appointment_' . $uuid4, $info, 120);
		
		if($is_internal) return true;
		
		if($registered){
			$enableSuccessRedirect = get_option('setrio_bizcal_enable_success_redirect', 0);
			if ($enableSuccessRedirect) {

				$successRedirectPostId = get_option('setrio_bizcal_success_redirect_post_id');
				$successRedirectLink = get_option('setrio_bizcal_success_redirect_link');

				$redirect_link = trim($successRedirectLink);
				if ('' === $redirect_link) {
					if ($successRedirectPostId) {
						$redirect_link = get_the_permalink($successRedirectPostId);
					}
				}
				$response['redirect'] = $redirect_link;
				$response['sba_hash'] = $uuid4;
			}
		}
		$response['data'] = $info;
		$response['message'] = $message;
		
		wp_send_json(json_encode($response));
	} catch (Exception $e) {
		if($is_internal) throw new Exception(wp_kses_post($e->getMessage()), intval(1000 + $e->getCode()));
		wp_send_json(json_encode(["ErrorCode" => 1000, "ErrorMessage" => $e->getMessage()]));
	}
	if($is_internal) throw new Exception(wp_kses_post(setrio_bizcal_message('msgErrUnknownError')), 2000);
	wp_send_json(json_encode(["ErrorCode" => 2000, "ErrorMessage" => setrio_bizcal_message('msgErrUnknownError')]));
}

function setrio_bizcal_ajax_get_price_for_service()
{
	global $setrio_bizcal_debug, $setrio_bizcal_securemode;

	if ($setrio_bizcal_securemode)
		check_ajax_referer("getMedicalSpecialities");

	try {
		$params = json_encode(["PhysicianUID" => sanitize_text_field($_POST["physician_uid"])]);
		$serviceUID = "";
		$result = "";

		if (isset($_POST["service_uid"]))
			$serviceUID = sanitize_text_field($_POST['service_uid']);

		if ($serviceUID == "")
			throw new Exception(setrio_bizcal_message('lblMedicalServiceMissing'));

		$client = new SetrioBizCal_BizMedicaServiceClient();
		$response = $client->getMedicalServicesPriceList($params);

		if (!setrio_bizcal_is_valid_json($response))
			throw new Exception(setrio_bizcal_parse_service_exception($response));

		$prices = json_decode($response);
		if (($prices->ErrorCode == 0) && ($prices->ErrorMessage == "")) {
			foreach ($prices->PriceList as $price) {
				if ($serviceUID == $price->ServiceUID) {
					$isEuro = false;
					if (property_exists($price, "IsEuro"))
						$isEuro = $price->IsEuro;

					$result = $price->Price . ' ' . (($isEuro) ? '€' : 'lei');
					break;
				}
			}

			if ($result == "")
				throw new Exception(setrio_bizcal_message('lblMedicalServiceNotAvailableForPhysician'));
		}

		wp_send_json(json_encode(["ErrorCode" => $prices->ErrorCode, "ErrorMessage" => $prices->ErrorMessage, "Price" => $result]));
	} catch (Exception $e) {
		wp_send_json(json_encode(["ErrorCode" => 1000, "ErrorMessage" => $e->getMessage()]));
	}

	wp_send_json(json_encode(["ErrorCode" => 2000, "ErrorMessage" => setrio_bizcal_message('msgErrUnknownError')]));
}

function setrio_bizcal_ajax_get_locations()
{
	global $setrio_bizcal_debug, $setrio_bizcal_securemode;

	if ($setrio_bizcal_securemode)
		check_ajax_referer("getMedicalSpecialities");
	
	$capsType = get_option('setrio_bizcal_caps_location', 'lcaseucwords');
	if(!$capsType){
		$capsType = get_option('setrio_bizcal_caps', '');
	}
	if(!$capsType || ('none'==$capsType)){
		$capsType = false;
	}

	try {
		$params = json_encode(["SpecialityCode" => sanitize_text_field($_POST["speciality_code"])]);

		$client = new SetrioBizCal_BizMedicaServiceClient();
		$response = $client->getLocationsForSpeciality($params);

		if (!setrio_bizcal_is_valid_json($response))
			throw new Exception(setrio_bizcal_parse_service_exception($response));

		$response = json_decode($response);
		
		if(!empty($response->Locations)){
			foreach($response->Locations as &$location_i){
				$location_i->LocationName = setrio_bizcal_caps($location_i->LocationName, $capsType);
			}
		}

		if (($response->ErrorCode == 0) && ($response->ErrorMessage == null))
			$response->ErrorMessage = "";

		wp_send_json(json_encode($response));
	} catch (Exception $e) {
		wp_send_json(json_encode(["ErrorCode" => 1000, "ErrorMessage" => $e->getMessage()]));
	}

	wp_send_json(json_encode(["ErrorCode" => 2000, "ErrorMessage" => setrio_bizcal_message('msgErrUnknownError')]));
}

function setrio_bizcal_ajax_dates()
{
	$response = array();
	if(isset($_POST["dates"])){
		if(is_array(@$_POST["dates"])){
			$dates = (array)@$_POST["dates"];
		} else {
			$dates = explode(',',@$_POST["dates"]);
		}
		foreach($dates as $date){
			$d =@ (new DateTime($date));
			if(!$d){
				$response[] = gmdate('Y-m-d');
			} else {
				$response[] = $d->format('Y-m-d');
			}
		}
	}
	wp_send_json($response);
}

?>