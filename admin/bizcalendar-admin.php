<?php
if (!class_exists('WP_List_Table'))
{
    require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');
}

require_once(plugin_dir_path( __FILE__ )."../common.php");
require_once(plugin_dir_path( __FILE__ )."../communication.php");

add_action('admin_menu', 'setrio_bizcal_setup_menu');
add_action('plugins_loaded', 'setrio_bizcal_admin_init');
//add_action('init', 'Setrio_Bizcal_Post_Select::init');
//add_action('admin_enqueue_scripts', 'Setrio_Bizcal_Post_Select::enqueue_scripts_and_styles');
 
function setrio_bizcal_admin_init()
{  
    global $bizcalAdmin;
    $bizcalAdmin = new SetrioBizCalAdmin();
    $bizcalAdmin->init();    
}

function setrio_bizcal_setup_menu()
{
    add_menu_page('Configurare BizCalendar', 'BizCalendar', 'manage_options', 'setrio_bizcal_admin', 'setrio_bizcal_admin_display');
	
	add_action( 'admin_print_scripts', 'setrio_bizcal_enqueue_admin_scripts' );
}

function setrio_bizcal_language_items()
{
    return [
        "Mesaje generale" => [
            "msgError",
            "msgWarning",
            "msgInfo",
            "textNA",
            "textYes",
            "textNo",
            "textNext",
            "textPrev",
            "txtLoading",
            "txtPopupTitle",
            "txtUnknown",
            "txtNoItems",
            "txtNoLocations",
            "txtNoPaymentMethods",
            "txtNoPhysicians",
            "txtNoServices",
            "txtNoSpecialities",
            "msgSuccessSubmitTitle",
            
            ],
        "Mesaje de eroare" => [
            "msgErrServiceAddressMissing",
            "msgErrServiceUserMissing",
            "msgErrServicePasswordMissing",
            "msgErrServiceUnknownError",
            "msgErrUnknownError",
            "msgErrRequestInProgress",
            "msgErrGetAppointmentHours",
            "msgWarnNoAvailableAppointments",
            "msgErrNoAvailableAppointments",
            "msgErrAppointmentTimeMissing",
            "msgErrPhysicianMissing",
            "msgErrRegisterAppointmentFailed",
			"msgErrSubmitTitle",
			"msgErrSubmitBody",
            "msgErrFormTitle",
            "msgErrFormBody",
            "txtNoAvailableAppointments",
            "txtFoundAvailableAppointments",
            "txtLaterAvailableAppointments",
            "txtWarnNoAvailableAppointments",
            "txtWarnNoAvailableAppointmentsOnce",
            "txtWarnShowingClosestAvailableAppointments",
            "txtWarnShowingAvailableAppointments",
            ],
        "Elemente formular" => [
            "lblMedicalSpeciality",
            "lblMedicalSpecialityPlaceholder",
            "lblLocation",
            "lblLocationPlaceholder",
            "lblPaymentType",
            "lblPaymentTypePlaceholder",
            "lblPaymentTypeField",
            "lblPhysician",
            "lblPreferredPhysician",
            "lblPhysicianPlaceholder",
            "lblPhysicianField",
            "lblAnyAvailablePhysician",
            "lblAnyAvailableLocation",
            "lblPhysicianPrice",
            "lblMedicalService",
            "lblMedicalServicePlaceholder",
            "lblMedicalServiceField",
            "lblAppointmentDate",
            "lblAppointmentTimeStartField",
            "lblAppointmentTimeEndField",
            "lblRequestAppointmentTitle",
            "lblCheckingAvailability",
            "lblAvailabilityFound",
            "btnCheckAvailability",
            "lblAppointmentTime",
            "lblPatientLastName",
            "lblLastNameField",
            "lblPatientFirstName",
            "lblFirstNameField",
            "lblPatientPhone",
            "lblPhoneField",
            "lblPhoneFieldNotValid",
            "lblPhoneFieldMinMax",
            "lblPatientEmail",
            "lblEmailFieldNotValid",
            "lblTermsNotAgreed",
            "lblDataPolicyNotAgreed",
            "lblPatientObservations",
            "lblReCaptchaFieldNotValid",
            "btnRequestAppointment",
            "btnCannotRequestAppointment",
            "btnNotifyAdmin",
            "lblAppointmentConfirmation",
            "lblAppointmentConfirmationWithLocation",
            "btnGotIt",
            "btnCancel",
            "lblFieldMissing",
            "lblPrice",
            "lblPriceValue",
            "lblPriceMissing",
            "lblPriceIntervalAndCurrency",
            "lblPriceValueAndCurrency",
            "lblMedicalServiceMissing",
            "lblMedicalServiceNotAvailableForPhysician",
            "lblReCaptchaMissing",
            "lblAuxObservationsCNAS",
            "lblPaymentTypeCNAS",
            "lblPaymentTypeTicket",
            "lblPaymentTypeCashCard",
            "lblPaymentTypeOnline",
            "lblSelectedPhysician",
            "lblSelectedService",
            "lblSelectedServicePrice",
            "lblSelectedDate",
            "lblStep1",
            "lblStep2",
            "lblStep3",
            "lblStep4",
            "lblRedirectPayment",
            ],
        "Mesaj de confirmare programare" => [
            "msgAppointmentPaymentDetails",
            "msgAppointmentConfirmationEmail",
            "msgAppointmentConfirmationEmailWithLocation",
            "msgAppointmentFailedEmail",
            "msgCalAppointmentSummary",
            "msgCalAppointmentDescription",
            "msgAppointmentFailedMobilPayEmailSubject",
            "msgAppointmentFailedMobilPayEmailBody",
            ],          
        "Acorduri" => [
            "lblTerms",
            "lblTermsText",
            "lblDataPolicy",
            "lblDataPolicyText",
            "lblNewsletter",
            ],         
        "Link tracking" => [
            "txtShortcodeDetaliiProgramare",
            ],            
        ];
}

function setrio_bizcal_enqueue_admin_scripts()
{
	$settings = wp_enqueue_code_editor(
		array(
			'type'       => 'text/css',
			'codemirror' => array(
				'indentUnit' => 4,
				'tabSize'    => 4,
			),
		)
	);
	
	$settings2 = wp_enqueue_code_editor(
		array(
			'type'       => 'javascript',
			'codemirror' => array(
				'indentUnit' => 4,
				'tabSize'    => 4,
			),
		)
	);
	
	wp_enqueue_script('setrio-bizcal-admin', plugin_dir_url(__FILE__ ) . 'bizcalendar-admin2.js', array('jquery'), '1.0.0.0', true);
	wp_add_inline_script( 'setrio-bizcal-admin', sprintf( 'setrioBizcalCustomCSSinit( %s );', wp_json_encode( $settings ) ), 'after' );
	wp_add_inline_script( 'setrio-bizcal-admin', sprintf( 'setrioBizcalCustomCSS2init( %s );', wp_json_encode( $settings2 ) ), 'after' );
}
function setrio_bizcal_admin_display()
{
    $wsAddress = get_option('setrio_bizcal_wsaddr', '');
    $wsUser = get_option('setrio_bizcal_wsuser', '');
    $wsPass = get_option('setrio_bizcal_wspass', '');
    $wsFakeRegister = get_option('setrio_bizcal_fake_register', 0);
    $reCaptchaSiteKey = get_option('setrio_bizcal_g_site_key', '');
    $reCaptchaSecretKey = get_option('setrio_bizcal_g_secret_key', '');
    $enableMultipleLocations = get_option('setrio_bizcal_enable_multiple_locations', false);
    $clinicPhone = get_option('setrio_bizcal_phone', '');
    $clinicEmail = get_option('setrio_bizcal_email', '');
    $clinicErrEmail = get_option('setrio_bizcal_err_email', '');
    $showPhysicianDetails = (bool)get_option('setrio_bizcal_show_physician_details', false);
    $allCaps = (bool)get_option('setrio_bizcal_all_caps', false);
    $forceVue = (bool)get_option('setrio_bizcal_force_vue', false === get_option('setrio_bizcal_max_availabilities'));
    $forceAdminAjax = (bool)get_option('setrio_bizcal_force_adminajax', false);
    $autoSelectMedicalSpeciality = (bool)get_option('setrio_bizcal_autosel_speciality', true);
    $autoSelectLocation = (bool)get_option('setrio_bizcal_autosel_location', true);
    $autoSelectPaymentType = (bool)get_option('setrio_bizcal_autosel_payment_type', true);
    $autoSelectMedicalService = (bool)get_option('setrio_bizcal_autosel_service', true);
    $autoSelectPhysician = (bool)get_option('setrio_bizcal_autosel_physician', true);
    $allowSearchForPhysician = (bool)get_option('setrio_bizcal_allow_search_physician', true);
    $maxAvailabilities = (int)get_option('setrio_bizcal_max_availabilities', 0);
    $minDaysToAppointment = (int)get_option('setrio_bizcal_min_days_to_appointment', 0);
    $max_register_per_ip = (int)get_option('setrio_bizcal_max_register_per_ip', 0);
    $appointmentEmailTo = get_option('setrio_bizcal_appointment_email_to');
    $appointmentEmailSubject = get_option('setrio_bizcal_appointment_email_subject');
    $addServiceNameInObservations = (bool)get_option('setrio_bizcal_add_service_to_obs', false);
    $appointmentParamOrder = (int)get_option('setrio_bizcal_appointment_param_order', 0);
    $specialityOrder = (int)get_option('setrio_bizcal_speciality_order', 0);
    $specialityOrderItems = get_option('setrio_bizcal_speciality_order_items');
	$enableNewsletter = get_option('setrio_bizcal_enable_newsletter', 1);
	$enableSuccessRedirect = get_option('setrio_bizcal_enable_success_redirect', 0);
	$successRedirectPostId = get_option('setrio_bizcal_success_redirect_post_id');
	$successRedirectLink = get_option('setrio_bizcal_success_redirect_link');
	
	$enableCustomCss = get_option('setrio_bizcal_enable_custom_css', 0);
	$customCss = get_option('setrio_bizcal_custom_css', '');
	
	$enableTerms = get_option('setrio_bizcal_enable_terms', 1);
	$enableDataPolicy = get_option('setrio_bizcal_enable_data_policy', 1);
	$dataPolicyPostId = get_option('setrio_bizcal_data_policy_post_id');
	
	$termsPostId = get_option('setrio_bizcal_terms_post_id', get_option( 'wp_page_for_privacy_policy' ));
	$termsLink = get_option('setrio_bizcal_terms_link');
	$dataPolicyLink = get_option('setrio_bizcal_data_policy_link');
	$jQueryUIParams = get_option('setrio_bizcal_jquery_ui_params');
	$jQueryUIUploadsPath = get_option('setrio_bizcal_jquery_ui_uploads_path');
	$enableCustomJQueryUI = get_option('setrio_bizcal_enable_custom_jquery_ui');
	$VUEParams = get_option('setrio_bizcal_vue_params');
	$enableCustomVue = get_option('setrio_bizcal_enable_custom_vue');
	$VueInlineTemplate = get_option('setrio_bizcal_vue_inline_template','inline');
	$VuePopupTemplate = get_option('setrio_bizcal_vue_popup_template','popup');
	$VueButtonClass = get_option('setrio_bizcal_vue_button_class','');
	$VueButtonStyle = get_option('setrio_bizcal_vue_button_style','');
	$VueButtonType = get_option('setrio_bizcal_vue_button_type','button');
	$VueCalendarType = get_option('setrio_bizcal_vue_calendar_type','inline');
	$VUEUploadsPath = get_option('setrio_bizcal_vue_uploads_path');
	$VUEUploadsFile = get_option('setrio_bizcal_vue_params_file');
	$VUEUploadsUrl = null;
	
	$paymentMobilPayStatus = get_option('setrio_bizcal_payment_mobilpay_status', 0);
	$paymentMobilPayGeneral = get_option('setrio_bizcal_payment_mobilpay_general', 0);
	$paymentMobilPayFree = get_option('setrio_bizcal_payment_mobilpay_free', 0);
	$paymentMobilPayFreeCNAS = get_option('setrio_bizcal_payment_mobilpay_free_cnas', 0);
	$paymentMobilPayTest = get_option('setrio_bizcal_payment_mobilpay_test', 'sandbox');
	$paymentMobilPayAdmin = get_option('setrio_bizcal_payment_mobilpay_admin', 0);
	$paymentMobilPaySignature = get_option('setrio_bizcal_payment_mobilpay_signature', '');
	$paymentMobilPayUsername = get_option('setrio_bizcal_payment_mobilpay_username', '');
	$paymentMobilPayPassword = get_option('setrio_bizcal_payment_mobilpay_password', '');
	if (!class_exists('WP_Filesystem_Direct')){
		require_once(ABSPATH.'wp-admin/includes/class-wp-filesystem-base.php');
		require_once(ABSPATH.'wp-admin/includes/class-wp-filesystem-direct.php');
	}
	$filesystem = new WP_Filesystem_Direct( true );
	if($VUEUploadsFile){
		$vue_wp_upload_dir = wp_upload_dir($VUEUploadsPath, false);
		if($filesystem->is_file($vue_wp_upload_dir['path'] . '/' . $VUEUploadsFile)){
			$VUEUploadsUrl = $vue_wp_upload_dir['url'] . '/' . $VUEUploadsFile;
		}
	}
	
    ?>
    <div class="wrap">
        <h1><?php echo wp_kses_post( get_admin_page_title() ); ?></h1>
        
        <?php
            if (isset($_GET['tab']))
                $active_tab = $_GET[ 'tab' ];
            else
                $active_tab = 'serviciu';
        ?>

        <h2 class="nav-tab-wrapper">
            <a href="?page=setrio_bizcal_admin&tab=serviciu" class="nav-tab<?php echo esc_attr((($active_tab == 'serviciu') ? ' nav-tab-active' : '')); ?>">Configurare serviciu</a>
            <a href="?page=setrio_bizcal_admin&tab=clinica" class="nav-tab<?php echo esc_attr((($active_tab == 'clinica') ? ' nav-tab-active' : '')); ?>">Date clinică</a>
            <a href="?page=setrio_bizcal_admin&tab=mobilpay&orderby=date_modified&order=desc" class="nav-tab<?php echo esc_attr((($active_tab == 'mobilpay') ? ' nav-tab-active' : '')); ?>">Mobilpay</a>
            <a href="?page=setrio_bizcal_admin&tab=medici" class="nav-tab<?php echo esc_attr((($active_tab == 'medici') ? ' nav-tab-active' : '')); ?>">Medici</a>
            <a href="?page=setrio_bizcal_admin&tab=aspect" class="nav-tab<?php echo esc_attr((($active_tab == 'aspect') ? ' nav-tab-active' : '')); ?>">Aspect</a>
            <a href="?page=setrio_bizcal_admin&tab=caching" class="nav-tab<?php echo esc_attr((($active_tab == 'caching') ? ' nav-tab-active' : '')); ?>">Caching</a>
            <a href="?page=setrio_bizcal_admin&tab=aspectjqueryui" class="nav-tab<?php echo esc_attr((($active_tab == 'aspectjqueryui') ? ' nav-tab-active' : '')); ?>">Aspect jQuery UI</a>
            <a href="?page=setrio_bizcal_admin&tab=aspectvue" class="nav-tab<?php echo esc_attr((($active_tab == 'aspectvue') ? ' nav-tab-active' : '')); ?>">Aspect VUE</a>
            <a href="?page=setrio_bizcal_admin&tab=email" class="nav-tab<?php echo esc_attr((($active_tab == 'email') ? ' nav-tab-active' : '')); ?>">E-mail confirmare</a>
            <a href="?page=setrio_bizcal_admin&tab=mesaje" class="nav-tab<?php echo esc_attr((($active_tab == 'mesaje') ? ' nav-tab-active' : '')); ?>">Mesaje</a>
            <a href="?page=setrio_bizcal_admin&tab=acorduri" class="nav-tab<?php echo esc_attr((($active_tab == 'acorduri') ? ' nav-tab-active' : '')); ?>">Acorduri</a>
            <a href="?page=setrio_bizcal_admin&tab=link-tracking" class="nav-tab<?php echo esc_attr((($active_tab == 'link-tracking') ? ' nav-tab-active' : '')); ?>">Link Tracking</a>
            <a href="?page=setrio_bizcal_admin&tab=custom-css" class="nav-tab<?php echo esc_attr((($active_tab == 'custom-css') ? ' nav-tab-active' : '')); ?>">CSS Personalizat</a>
            <a href="?page=setrio_bizcal_admin&tab=log" class="nav-tab<?php echo esc_attr((($active_tab == 'log') ? ' nav-tab-active' : '')); ?>">Log</a>
        </h2>
		<form method="get" id="getForm" name="getForm"></form>
        <form method="post" action="<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="setrio-bizcal-admin-tab" value="<?php echo esc_attr($active_tab); ?>" />
    
        <div id="setrio-bizcal-wscontainer">
            <?php if ($active_tab == 'serviciu') : ?>
            <h2>Serviciul web BizMedica</h2>
 
            <div class="options">
                <p>
                    <label>Adresa serviciului</label>
                    <br />
                    <input type="text" class="regular-text" name="setrio-bizcal-wsaddress" value="<?php echo esc_attr($wsAddress); ?>" />
                </p>
                <p>
                    <label>Utilizator</label>
                    <br />
                    <input type="text" class="regular-text" name="setrio-bizcal-wsuser" value="<?php echo esc_attr($wsUser); ?>" />
                </p>
                <p>
                    <label>Parola</label>
                    <br />
                    <input type="text" class="regular-text" name="setrio-bizcal-wspassword" value="<?php echo esc_attr($wsPass); ?>" />
                </p>
					<input type="hidden" name="setrio-bizcal-fake-register" value="0" />
					<p>
                        <label>
                        <input type="checkbox" class="regular-text" name="setrio-bizcal-fake-register" value="1"
                               <?php echo ($wsFakeRegister?"checked=\"1\"":"")?> />
                        Rezervari fictive (nu se face rezervare reala la medic)</label>
                    </p>
            </div>

            <div id="setrio-bizcal-recaptcha">
                <h2>Configurare ReCaptcha</h2>
     
                <div class="options">
                    <p>
                        <label>Cheie site</label>
                        <br />
                        <input type="text" class="regular-text" name="setrio-bizcal-g-site-key" value="<?php echo esc_attr($reCaptchaSiteKey); ?>" />
                    </p>
                    <p>
                        <label>Cheie secretă</label>
                        <br />
                        <input type="text" class="regular-text" name="setrio-bizcal-g-secret-key" value="<?php echo esc_attr($reCaptchaSecretKey); ?>" />
                    </p>
                </div>
            </div>
            
            <div id="options-advanced">
                <h2>Opțiuni serviciu</h2>
     
                <div class="options">
                    <p>
                        <label for="setrio-bizcal-enable-multiple-locations">
                            <input id="setrio-bizcal-enable-multiple-locations" type="checkbox" name="setrio-bizcal-enable-multiple-locations" value="1"
                                   <?php echo ($enableMultipleLocations?"checked=\"1\"":"")?> />
                            Activează sistemul cu mai multe locații
                        </label>
                    </p>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($active_tab == 'medici') : ?>
            <?php
                if (isset($_GET['action']))
                    $action = $_GET['action'];
                else
                    $action = '';
                if (isset($_GET['physician_uid']))
                    $physician_uid = $_GET['physician_uid'];
                else
                    $physician_uid = '';
                
            ?>
            <div id="setrio-bizcal-medici">
                <h2>Detalii medici</h2>

                <?php if ($action == "physician_edit") : ?>
                <div class="options">
                    <?php
                        wp_enqueue_media();
                        global $wpdb;
                        $physician = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}bizcal_physicians_description WHERE physician_uid = ?",[$physician_uid]));
                        if ($physician)
                        {
                            $physician_name = $physician->physician_name;
                            $physician_description = $physician->description;
                            $physician_picture_id = $physician->physician_picture_id;
                            if ($physician_picture_id === null)
                                $physician_picture_id = 0;
                        }
                        else
                        {
                            $physician_name = '';
                            $physician_description = '';
                            $physician_picture_id = 0;
                        }
                    ?>
                    <div class="options">
                        <input type="hidden" name="setrio-bizcal-physician-uid" value="<?php echo esc_attr($physician_uid); ?>" />
                        <p>
                            <label>Nume și prenume medic</label>
                            <br />
                            <input type="text" class="regular-text" readonly="readonly" name="setrio-bizcal-physician-name" value="<?php echo esc_attr($physician_name); ?>" />
                        </p>
                        <p>
                            <label>Descriere</label>
                            <br />
                            <input type="text" class="regular-text" name="setrio-bizcal-physician-description" value="<?php echo esc_attr($physician_description); ?>" />
                        </p>
                        <p>
                            <label>Poză</label>
                            <br />
                            <div class='image-preview-wrapper'>
                                <img id='setrio-bizcal-picture-preview' src='<?php echo esc_attr(wp_get_attachment_url($physician_picture_id )); ?>'
                                     width='100' height='100' style='max-height: 100px; width: auto;'>
                            </div>
                            <input id="upload_image_button" type="button" class="button" value="<?php esc_attr(__( 'Upload image' )); ?>" />
                            <input type='hidden' name='setrio-bizcal-physician-picture-id' id='setrio-bizcal-physician-picture-id' value='<?php echo esc_attr($physician_picture_id); ?>'>
                        </p>
                    </div>
                    <?php
                        setrio_bizcal_admin_js($physician_picture_id);
                    ?>
                </div>
                <?php else : ?>
                <div class="options">
                    <input type="hidden" name="setrio-bizcal-physicians" value="1" />
                    <p>
                        <label>
                        <input type="checkbox" class="regular-text" name="setrio-bizcal-show-physicians-details" value="1"
                               <?php echo ($showPhysicianDetails?"checked=\"1\"":"")?> />
                        Se afișează fotografiile și descrierea medicilor</label>
                    </p>
                    <?php
                        $physiciansGrid = new SetrioBizCalAdminPhysiciansGrid();
                        $physiciansGrid->prepare_items();
                        $physiciansGrid->display();
                    ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
			<?php if ($active_tab == 'log') : ?>
            <div id="setrio-bizcal-log">
                <h2>Log</h2>
				<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/default.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/json.min.js"></script>
                <div class="options">
					<style>
						table.fixed.logs{
							table-layout: auto;
						}
					</style>
                    <?php
                        $logGrid = new SetrioBizCalAdminLogGrid();
                        $logGrid->prepare_items();
                        $logGrid->display();
                    ?>
					<script>
						document.querySelectorAll('code').forEach((v) => hljs.highlightElement(v));
						function fallbackCopyTextToClipboard(text) {
  var textArea = document.createElement("textarea");
  textArea.value = text;
  
  // Avoid scrolling to bottom
  textArea.style.top = "0";
  textArea.style.left = "0";
  textArea.style.position = "fixed";

  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();

  try {
    var successful = document.execCommand('copy');
    var msg = successful ? 'successful' : 'unsuccessful';
    console.log('Fallback: Copying text command was ' + msg);
  } catch (err) {
    console.error('Fallback: Oops, unable to copy', err);
  }

  document.body.removeChild(textArea);
}
function copyTextToClipboard(text) {
  if (!navigator.clipboard) {
    fallbackCopyTextToClipboard(text);
    return;
  }
  navigator.clipboard.writeText(text).then(function() {
    console.log('Async: Copying to clipboard was successful!');
  }, function(err) {
    console.error('Async: Could not copy text: ', err);
  });
}

						function copyText(el){
							copyTextToClipboard(el.innerText.trim());
						}
					</script>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($active_tab == 'clinica') : ?>
            <div id="setrio-bizcal-admin-clinica">
                <h2>Date de contact unitate medicală</h2>
     
                <div class="options">
                    <p>
                        <label>Telefon</label>
                        <br />
                        <input type="text" class="regular-text" name="setrio-bizcal-phone" value="<?php echo esc_attr($clinicPhone); ?>" />
                    </p>
                    <p>
                        <label>Adresă de e-mail</label>
                        <br />
                        <input type="text" class="regular-text" name="setrio-bizcal-email" value="<?php echo esc_attr($clinicEmail); ?>" />
                    </p>
                    <p>
                        <label>Trimite email in caz de eroare si pe adresa de suport</label>
                        <br />
                        <input type="text" class="regular-text" name="setrio-bizcal-err-email" value="<?php echo esc_attr($clinicErrEmail); ?>" />
                    </p>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($active_tab == 'mobilpay') : ?>
            <div id="setrio-bizcal-admin-mobilpay">
                <h2>Date Mobilpay</h2>
				<?php
				$paymentMobilPaySignature_suggestions = [];
				foreach(glob(WP_CONTENT_DIR .'/Mobilpay/Certificates/{live,sandbox}*{.public.cer,private.key}', GLOB_BRACE) as $file){
					$bfile = basename($file);
					if(!preg_match('/^(sandbox|live).((?:[A-Z0-9]{4}-){4}[A-Z0-9]{4})(?:\.(public)\.cer|(private)\.key)$/', $bfile, $fm)) continue;
					$paymentMobilPaySignature_suggestions[$fm[2]][$fm[1]][$fm[3] ? $fm[3] : $fm[4]] = 1;
				}
				?>
                <div style="display:flex;flex-direction:row;gap:15px;margin-top:15px;flex-wrap:wrap;">
                <div class="options">
					Semnaturi gasite in directorul <b>/wp-content/Mobilpay/Certificates/</b>
					<ul>
					<?php foreach($paymentMobilPaySignature_suggestions as $signature_suggestion_key => $signature_suggestion_data){ ?>
						<li><b style="padding-top: 5px;display: inline-block;"><?php echo wp_kses_post($signature_suggestion_key); ?></b>
							<ul style="vertical-align:top; display:inline-block;margin-bottom:0;">
							<?php foreach($signature_suggestion_data as $signature_suggestion_type => $signature_suggestion_type_data){ ?>
								<li> &rarr; <b><?php echo wp_kses_post($signature_suggestion_type); ?></b>
								<?php if(empty($signature_suggestion_type_data['private'])){ ?>
								<font color="red">Lipseste <b>cheia privata</b> (fisierul <b><?php echo wp_kses_post($signature_suggestion_type); ?>.<?php echo wp_kses_post($signature_suggestion_key); ?>private.key</b>)</font>
								<?php } ?>
								<?php if(empty($signature_suggestion_type_data['public'])){ ?>
								<font color="red">Lipseste <b>certificatul public</b> (fisierul <b><?php echo wp_kses_post($signature_suggestion_type); ?>.<?php echo wp_kses_post($signature_suggestion_key); ?>.public.cer</b>)</font>
								<?php } ?>
								<?php if(!empty($signature_suggestion_type_data['public']) && !empty($signature_suggestion_type_data['private'])){ ?>
								<button type="button" class="button" color="green" style="vertical-align:middle" onclick="jQuery('select[name=setrio-bizcal-payment-mobilpay-test]').val('<?php echo esc_attr($signature_suggestion_type); ?>'); jQuery('input[name=setrio-bizcal-payment-mobilpay-signature]').val('<?php echo esc_attr($signature_suggestion_key); ?>'); ">Foloseste</button>
								<?php } ?>
								</li>
							<?php } ?>
							</ul>
						</li>
					<?php } ?>
					</ul>
					<p>
						Plasati intai certificatele sandbox si/sau live in /wp-content/Mobilpay/Certificates/ apoi optiunile vor aparea in acest ecran
						<ul>
							<li>live.XXXX-XXXX-XXXX-XXXX-XXXX.public.cer</li>
							<li>live.XXXX-XXXX-XXXX-XXXX-XXXXprivate.key</li>
							<li>sandbox.XXXX-XXXX-XXXX-XXXX-XXXX.public.cer</li>
							<li>sandbox.XXXX-XXXX-XXXX-XXXX-XXXXprivate.key</li>
						</ul>
					</p>
					<input type="hidden" name="setrio-bizcal-payment-mobilpay-status" value="0" />
                    <p>
                        <label>
                        <input type="checkbox" class="regular-text" name="setrio-bizcal-payment-mobilpay-status" value="1"
                               <?php echo ($paymentMobilPayStatus?"checked=\"1\"":"")?> />
                        Activeaza plata mobilpay</label>
                    </p>
                    <p>
                        <label>
                        <input type="checkbox" class="regular-text" name="setrio-bizcal-payment-mobilpay-general" value="1"
                               <?php echo ($paymentMobilPayGeneral?"checked=\"1\"":"")?> />
                        Toate serviciile se platesc? (Debifat inseamna ca doar anumite servicii cu un anumit parametru in API necesita plata)</label>
                    </p>
					<p>
                        <label>
                        <input type="checkbox" class="regular-text" name="setrio-bizcal-payment-mobilpay-free" value="1"
                               <?php echo ($paymentMobilPayFree?"checked=\"1\"":"")?> />
                        Se doreste validare card la servicii gratuite?</label>
                    </p>
					<p>
                        <label>
                        <input type="checkbox" class="regular-text" name="setrio-bizcal-payment-mobilpay-free-cnas" value="1"
                               <?php echo ($paymentMobilPayFreeCNAS?"checked=\"1\"":"")?> />
                        Se doreste validare card la servicii gratuite? + CNAS?</label>
                    </p>
                    <p>
                        <label>
                        <input type="checkbox" class="regular-text" name="setrio-bizcal-payment-mobilpay-admin" value="1"
                               <?php echo ($paymentMobilPayAdmin?"checked=\"1\"":"")?> />
                        Doar conectat in admin</label>
                    </p>
                    <p>
                        <label>Semnatura</label>
                        <br />
                        <input type="text" class="regular-text" name="setrio-bizcal-payment-mobilpay-signature" placeholder="XXXX-XXXX-XXXX-XXXX-XXXX" value="<?php echo esc_attr($paymentMobilPaySignature)?>" />
                    </p>
                    <p>
                        <label>Mod</label>
                        <br />
						<select name="setrio-bizcal-payment-mobilpay-test">
							<option value="sandbox" <?php echo $paymentMobilPayTest == 'sandbox' ? ' selected="selected"' : '' ?>>Sandbox</option>
							<option value="live" <?php echo $paymentMobilPayTest == 'live' ? ' selected="selected"' : '' ?>>Live</option>
						</select>
                    </p>
					<p>Urmatoarele 2 sunt pentru creditarea automata a tranzactiilor</p>
                    <p>
                        <label>Username</label>
                        <br />
                        <input type="text" class="regular-text" name="setrio-bizcal-payment-mobilpay-username" placeholder="" value="<?php echo esc_attr($paymentMobilPayUsername)?>" />
                    </p>
                    <p>
                        <label>Password</label>
                        <br />
                        <input type="password" class="regular-text" name="setrio-bizcal-payment-mobilpay-password" placeholder="<?php echo esc_attr($paymentMobilPayPassword ? '**********' : ''); ?>" value="" />
                    </p>
                </div>
				<div style="flex:50%">
				Loguri:
				<?php $logGrid = new SetrioBizCalAdminMobilPayLogGrid();
					$logGrid->prepare_items();
					$logGrid->display(); ?>
				</div>
				<?php if ($logGrid::$logcontent){
			
			if (!class_exists('WP_Filesystem_Direct')){
				require_once(ABSPATH.'wp-admin/includes/class-wp-filesystem-base.php');
				require_once(ABSPATH.'wp-admin/includes/class-wp-filesystem-direct.php');
			}
			
			 ?>
				<div style="flex:100%">
					<h3><?php echo wp_kses_post($logGrid::$logcontent->getFileName()) ?> (<?php echo wp_kses_post(gmdate('Y-m-d H:i:s', $logGrid::$logcontent->creation_date)) ?>, <?php echo wp_kses_post(gmdate('Y-m-d H:i:s', $logGrid::$logcontent->getMTime())) ?>)</h3>
					<textarea id="textarea-mobilpay-log" class="textarea-codemirror-json" style="width:100%;min-height:300px;white-space:pre" readonly disabled><?php echo esc_textarea($filesystem->get_contents($logGrid::$logcontent->getRealpath())) ?></textarea>
				</div>
				<?php } ?>
				</div>
            </div>
            <?php endif; ?>
            <?php if ($active_tab == 'aspect') : ?>
            <div id="setrio-bizcal-admin-aspect">
                <h2>Aspect modul</h2>
  <style>   
  #specialities_ordering_select{
	  counter-reset: specialities-counter;
  }
  #specialities_ordering_select > option:before {
	counter-increment: specialities-counter;
	content: "" counter(specialities-counter) ". ";
  }
  </style>   
                <input type="hidden" name="setrio-bizcal-display-settings" value="1" />
		<div style="display:flex;flex-wrap: wrap;justify-content: space-between;gap: 10px;">
			<div style="display:flex;flex-direction:column;width: calc(100% - 390px);min-width: 400px;flex: auto;">
				<table class="wp-list-table widefat fixed">
					<tbody>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-force-vue" type="checkbox" class="regular-text" name="setrio-bizcal-force-vue" value="1" <?php echo ($forceVue?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-force-vue">
									<strong>Utilizeaza doar Vue</strong> <em></em>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-force-adminajax" type="checkbox" class="regular-text" name="setrio-bizcal-force-adminajax" value="1" <?php echo ($forceAdminAjax?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-force-adminajax">
									<strong>Utilizeaza doar AdminAjax</strong> <em></em>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-allcaps" type="checkbox" class="regular-text" name="setrio-bizcal-allcaps" value="1" <?php echo ($allCaps?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-allcaps">
									<strong>Denumirile serviciilor sunt afisate cu litere mari</strong> <em></em>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-autosel-speciality" type="checkbox" class="regular-text" name="setrio-bizcal-autosel-speciality" value="1" <?php echo ($autoSelectMedicalSpeciality?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-autosel-speciality">
									<strong>Selectează automat prima specialitate medicală disponibilă</strong> <em></em>
							</td>
						</tr>
						<?php if ($enableMultipleLocations) : ?>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-autosel-location" type="checkbox" class="regular-text" name="setrio-bizcal-autosel-location" value="1" <?php echo ($autoSelectLocation?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-autosel-location">
									<strong>Selectează automat prima locație disponibilă</strong> <em></em>
								</label>
							</td>
						</tr>
						<?php endif; ?>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-autosel-payment-type" type="checkbox" class="regular-text" name="setrio-bizcal-autosel-payment-type" value="1" <?php echo ($autoSelectPaymentType?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-autosel-payment-type">
									<strong>Selectează automat primul tip de plată disponibil</strong> <em></em>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-autosel-service" type="checkbox" class="regular-text" name="setrio-bizcal-autosel-service" value="1" <?php echo ($autoSelectMedicalService?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-autosel-service">
									<strong>Selectează automat primul serviciu medical disponibil</strong> <em></em>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-autosel-physician" type="checkbox" class="regular-text" name="setrio-bizcal-autosel-physician" value="1" <?php echo ($autoSelectPhysician?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-autosel-physician">
									<strong>Selectează automat primul medic disponibil</strong> <em></em>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-allow-search-physician" type="checkbox" class="regular-text" name="setrio-bizcal-allow-search-physician" value="1" <?php echo ($allowSearchForPhysician?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-allow-search-physician">
									<strong>Permite căutarea intervalelor orare pentru un anumit medic</strong> <em></em>
								</label>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="2">
								<p style="display:flex;">
									<input type="number" min="0" step="1" class="regular-text" name="setrio-bizcal-max-availabilities" id="setrio-bizcal-max-availabilities" value="<?php echo esc_attr($maxAvailabilities); ?>" style="max-width:120px;vertical-align:top;align-self: center;"/>
									<label for="setrio-bizcal-max-availabilities" style="padding-left: 10px;">
										<strong>Număr intervale disponibile pentru programare selectabile de către pacient</strong> <em> (0 = nelimitat - nerecomandat)</em>
									</label>
								</p>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="2">
								<p style="display:flex;">
									<input type="number" min="0" step="1" class="regular-text" name="setrio-bizcal-min-days-to-appointment" id="setrio-bizcal-min-days-to-appointment" value="<?php echo esc_attr($minDaysToAppointment); ?>" style="max-width:120px;vertical-align:top;align-self: center;" />
									<label for="setrio-bizcal-min-days-to-appointment" style="padding-left: 10px;">
										<strong>Număr de zile până la prima zi de programare disponibilă</strong> 
									</label>
								</p>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="2">
								<p style="display:flex;">
									<input type="number" min="0" step="1" class="regular-text" name="setrio-bizcal-max_register_per_ip" id="setrio-bizcal-max_register_per_ip" value="<?php echo esc_attr($max_register_per_ip); ?>" style="max-width:120px;vertical-align:top;align-self: center;" />
									<label for="setrio-bizcal-max_register_per_ip" style="padding-left: 10px;">
										<strong>Număr maxim de inregistrari per ip pe zi</strong> <em> (0 = nelimitat - nerecomandat)</em>
									</label>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				
                <h2>Funcționalitate modul</h2>
				<table class="wp-list-table widefat fixed">
					<thead>
						<th class="check-column"></th>
						<th><strong style="margin-left:-30px;">Ordine selectare parametri programare</strong></th>
					</thead>
					<tbody>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-appointment-param-order-0" type="radio" class="regular-text" name="setrio-bizcal-appointment-param-order" value="0" <?php echo ($appointmentParamOrder==0?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-appointment-param-order-0">
									<strong>Specialitate, serviciu, medic preferat</strong> <em></em>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-appointment-param-order-1" type="radio" class="regular-text" name="setrio-bizcal-appointment-param-order" value="1" <?php echo ($appointmentParamOrder==1?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-appointment-param-order-1">
									<strong>Specialitate, medic preferat, serviciu</strong> <em></em>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="specialities_ordering_table" style="display:flex;width: 380px;flex: auto; align-items:<?php echo ($specialityOrder==1?"stretch":"baseline"); ?>">
			<?php
			$client = new SetrioBizCal_BizMedicaServiceClient();
			$specialitiesResponse = $client->getMedicalSpecialities();
			$specialities = array();
			if (setrio_bizcal_is_valid_json($specialitiesResponse)){
				$specialitiesData = json_decode($specialitiesResponse);

				if (($specialitiesData->ErrorCode == 0) && ($specialitiesData->ErrorMessage == ""))
				{
					$specialities = $specialitiesData->Specialities;
				}
			}
			?>
				<table class="wp-list-table widefat fixed">
					<thead>
						<th class="check-column"></th>
						<th>
							<strong style="margin-left:-30px; vertical-align:middle;">Ordonare specialitati</strong> 
							
						</th>
					</thead>
					<tbody>
						<tr style="height:1px;">
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-speciality-order-0" type="radio" class="regular-text" name="setrio-bizcal-speciality-order" value="0" <?php echo ($specialityOrder==0?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-speciality-order-0">
									<strong>Alfabetic</strong> <em></em>
							</td>
						</tr>
						<tr style="height:1px;">
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-speciality-order-1" type="radio" class="regular-text" name="setrio-bizcal-speciality-order" value="1" <?php echo ($specialityOrder==1?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-speciality-order-1">
									<strong>Personalizat</strong> <em></em>
							</td>
						</tr>
						<tr style="height:1px;" class="speciality-order-tr <?php echo ($specialityOrder==1?"":" hidden"); ?>">
							<th valign="top" class="check-column" rowspan="2">
								<div style="">
									<button type="button" class="button move-speciality" data-order="up" title="Muta selectia in sus (prioritizeaza)">&uarr;</button> <button type="button" class="button move-speciality" data-order="down" title="Muta selectia in jos (scade prioritatea)">&darr;</button>
								</div>
							</th>
						</tr>
						<tr class="speciality-order-tr <?php echo ($specialityOrder==1?"":" hidden"); ?>" style="">
							<td style="height:300px;position:relative;">
								<input type="hidden" name="setrio-bizcal-speciality-order-items">
								<div style="position:absolute;top:0;left:0;right:0;bottom:0;padding:5px;">
								<?php 
								$speciality_items = array();
								foreach($specialities as $speciality){
									$speciality_items[$speciality->Code] = $speciality->Name;
								}
								$speciality_items_ordered = $speciality_items;
								$specialityOrderItemsDecoded = array();
								if($specialityOrderItems){
									$specialityOrderItemsDecoded = json_decode($specialityOrderItems, true);
									if($specialityOrderItemsDecoded && is_array($specialityOrderItemsDecoded)){
										$speciality_items_ordered = setrio_bizcal_sortArrayByArray($speciality_items, $specialityOrderItemsDecoded);
									}
 								}
								?>
									<select id="specialities_ordering_select" multiple class="regular-text" style="height:100%;width:100%;max-width: 100%;">
										<?php foreach($speciality_items_ordered as $speciality_code => $speciality_name){ ?>
										<option value="<?php echo esc_attr($speciality_code); ?>"><?php echo esc_attr($speciality_name); ?></option>
										<?php } ?>
									</select>
								</div>
							</td>
						</tr>
						<tr class="speciality-order-tr <?php echo ($specialityOrder==1?"":" hidden"); ?>" style="height:1px;">
							<td colspan="2">
								<p>Selectati specialitatile apoi dati clic pe sageata dorita pentru a le ordona. Puteti sa utilizati tasta Ctrl sau Shift in combinatie cu clic, sau tineti click apasat si glisati pentru selectie multipla.</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
<?php
$capsEnabled = get_option('setrio_bizcal_caps', '');
			?>
<div id="setrio-bizcal-admin-caps">
	<h2>Transformare titlu nomenclatoare</h2>
	<div style="display:flex;flex-direction:row;gap:15px;margin-top:15px;flex-wrap:wrap;">
		<div style="display:flex;flex-wrap: wrap;justify-content: space-between;gap: 10px;">
			<div style="display:flex;flex-direction:column;width: calc(100% - 390px);min-width: 400px;flex: auto;">
				<table class="wp-list-table widefat fixed">
					<thead>
						<th class="check-column"></th>
						<th><strong style="margin-left:-30px;">Transformare titlu GENERAL</strong></th>
					</thead>
					<tbody>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-no" type="radio" class="regular-text" name="setrio-bizcal-caps" value="" <?php echo (!$capsEnabled?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-no">
									<strong>Fara modificare</strong> <em>Informatiile preluate prin api vor fi afisate precum sunt preluate din Api.</em>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-ucase" type="radio" class="regular-text" name="setrio-bizcal-caps" value="ucase" <?php echo ($capsEnabled == 'ucase' ?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-ucase">
									<strong>Majuscule toate</strong>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-lcase" type="radio" class="regular-text" name="setrio-bizcal-caps" value="lcase" <?php echo ($capsEnabled == 'lcase' ?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-lcase">
									<strong>Minuscule toate</strong>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-ucwords" type="radio" class="regular-text" name="setrio-bizcal-caps" value="ucwords" <?php echo ($capsEnabled == 'ucwords' ?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-ucwords">
									<strong>Prima litera din fiecare cuvant majuscula</strong>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-lcaseucwords" type="radio" class="regular-text" name="setrio-bizcal-caps" value="lcaseucwords" <?php echo ($capsEnabled == 'lcaseucwords' ?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-lcaseucwords">
									<strong>Prima litera din fiecare cuvant majuscula, restul minuscule</strong>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-ucfirst" type="radio" class="regular-text" name="setrio-bizcal-caps" value="ucfirst" <?php echo ($capsEnabled == 'ucfirst' ?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-ucfirst">
									<strong>Prima litera majuscula</strong>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-lcaseucfirst" type="radio" class="regular-text" name="setrio-bizcal-caps" value="lcaseucfirst" <?php echo ($capsEnabled == 'lcaseucfirst' ?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-lcaseucfirst">
									<strong>Prima litera majuscula, restul minuscule</strong>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<?php 
		$default_caps = array(
			'speciality' => 'lcaseucfirst',
			'location' => 'lcaseucwords',
			'payment_type' => '',
			'service' => 'lcaseucfirst',
			'physician' => 'lcaseucwords',
			// 'availability' => 'Disponibilitate',
		);
		foreach(array(
			'speciality' => 'Specialitati',
			'location' => 'Locatii',
			'payment_type' => 'Metode de plata',
			'service' => 'Servicii',
			'physician' => 'Medici',
			// 'availability' => 'Disponibilitate',
		) as $caps_key => $caps_name){ 
		
		$capsKeyValue = get_option('setrio_bizcal_caps_' . $caps_key, $default_caps[$caps_key]);
		?>
		<div style="display:flex;flex-wrap: wrap;justify-content: space-between;gap: 10px;flex-basis: min-content;">
			<div style="width:50%;min-width: 400px;flex: auto;">
				<table class="wp-list-table widefat fixed">
					<thead>
						<th class="check-column"></th>
						<th><strong style="margin-left:-30px;">Transformare titlu <?php echo wp_kses_post($caps_name); ?></strong></th>
					</thead>
					<tbody>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-no" type="radio" class="regular-text" name="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>" value="" <?php echo (!$capsKeyValue?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-no">
									<strong>Implicit</strong> <em>Precum stabilit in GENERAL</em>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-none" type="radio" class="regular-text" name="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>" value="none" <?php echo ($capsKeyValue == 'none' ?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-none">
									<strong>Fara modificare</strong> <em>Informatiile preluate prin api vor fi afisate precum sunt preluate din Api.</em>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-ucase" type="radio" class="regular-text" name="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>" value="ucase" <?php echo ($capsKeyValue == 'ucase' ?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-ucase">
									<strong>Majuscule toate</strong>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-lcase" type="radio" class="regular-text" name="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>" value="lcase" <?php echo ($capsKeyValue == 'lcase' ?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-lcase">
									<strong>Minuscule toate</strong>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-ucwords" type="radio" class="regular-text" name="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>" value="ucwords" <?php echo ($capsKeyValue == 'ucwords' ?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-ucwords">
									<strong>Prima litera din fiecare cuvant majuscula</strong>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-lcaseucwords" type="radio" class="regular-text" name="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>" value="lcaseucwords" <?php echo ($capsKeyValue == 'lcaseucwords' ?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-lcaseucwords">
									<strong>Prima litera din fiecare cuvant majuscula, restul minuscule</strong>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-ucfirst" type="radio" class="regular-text" name="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>" value="ucfirst" <?php echo ($capsKeyValue == 'ucfirst' ?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-ucfirst">
									<strong>Prima litera majuscula</strong>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-lcaseucfirst" type="radio" class="regular-text" name="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>" value="lcaseucfirst" <?php echo ($capsKeyValue == 'lcaseucfirst' ?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caps-<?php echo esc_attr($caps_key); ?>-lcaseucfirst">
									<strong>Prima litera majuscula, restul minuscule</strong>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php } ?>
	</div>
</div>

		</div>
            </div>
			
			<script>
		(function($){
			$('body').on('click','input[name="setrio-bizcal-speciality-order"]', function(){
				var value = $('input[name="setrio-bizcal-speciality-order"]:checked').val();
				switch(value){
					case '0':
						$('.speciality-order-tr').addClass('hidden');
						$('#specialities_ordering_table').css('align-items', 'baseline');
					break;
					case '1':
						$('.speciality-order-tr').removeClass('hidden');
						$('#specialities_ordering_table').css('align-items', 'stretch');
					break;
				}
			});
			$('body').on('click','.move-speciality', function(){
				var type = $(this).data('order');
				var $specialities_ordering_select = $('#specialities_ordering_select');
				var any_selected = $('>option:selected:first', $specialities_ordering_select).length;
				if(!any_selected){
					alert("Alegeti cel putin o specialitate intai, apoi actionati acest buton.");
					return;
				}
				switch(type){
					case 'up':
						var $first_option = $('>option:selected:first', $specialities_ordering_select);
						if($first_option.prev().length){
							$('>option:selected', $specialities_ordering_select).insertBefore($first_option.prev());
						} else {
							$('>option:selected', $specialities_ordering_select).insertAfter($first_option);
						}
					break;
					case 'down':
						var $last_option = $('>option:selected:last', $specialities_ordering_select);
						if($last_option.next().length){
							$('>option:selected', $specialities_ordering_select).insertAfter($last_option.next());
						} else {
							$('>option:selected', $specialities_ordering_select).insertBefore($last_option);
						}
					break;
				}
				$('input[name="setrio-bizcal-speciality-order-items"]').val(JSON.stringify($.map($('>option', $specialities_ordering_select), function(item,index){return item.value})));
			});
		})(jQuery);
			</script>
            <?php endif; ?>
            <?php if ($active_tab == 'caching') : 
			$cachingEnabled = (bool)get_option('setrio_bizcal_caching', false);
			$cachingOnFailEnabled = (bool)get_option('setrio_bizcal_caching_on_fail', true);
			$cachingTime = get_option('setrio_bizcal_caching_time', '');
			?>
<div id="setrio-bizcal-admin-caching">
	<div style="display:flex;flex-direction:column;gap:15px;margin-top:15px;">
		<div style="display:flex;flex-wrap: wrap;justify-content: space-between;gap: 10px;">
			<div style="display:flex;flex-direction:column;width: calc(100% - 390px);min-width: 400px;flex: auto;">
				<table class="wp-list-table widefat fixed">
					<thead>
						<th class="check-column"></th>
						<th><strong style="margin-left:-30px;">Caching GENERAL</strong></th>
					</thead>
					<tbody>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caching" type="checkbox" class="regular-text" name="setrio-bizcal-caching" value="1" <?php echo ($cachingEnabled?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caching">
									<strong>Activeaza caching</strong> <em>Informatiile preluate prin api vor fi stocate in baza de date pentru un anumit timp.</em>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caching-on-fail" type="checkbox" class="regular-text" name="setrio-bizcal-caching-on-fail" value="1" <?php echo ($cachingOnFailEnabled?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caching-on-fail">
									<strong>Doar in caz de esec</strong> <em>Informatiile din cache se folosesc doar in caz de esec la preluarea din api.</em>
								</label>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="2">
								<p style="display:flex;">
									<input type="number" min="0" step="1" placeholder="<?php echo 86400 * 30; ?>" class="regular-text" name="setrio-bizcal-caching-time" id="setrio-bizcal-caching-time" value="<?php echo esc_attr($cachingTime); ?>" style="max-width:120px;vertical-align:top;align-self: center;"/>
									<label for="setrio-bizcal-caching-time" style="padding-left: 10px;">
										<strong>Timp general de stocare cache</strong> <em> (secunde, 0 = nelimitat - nerecomandat)</em>
									</label>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<?php foreach(array(
			'speciality' => 'Specialitati',
			'location' => 'Locatii',
			'payment_type' => 'Metode de plata',
			'service' => 'Servicii',
			'physician' => 'Medici',
			'availability' => 'Disponibilitate',
		) as $cache_key => $cache_name){ 
		
		$cacheKeyCaching = get_option('setrio_bizcal_cache_type_' . $cache_key, '');
		$cacheKeyTime = get_option('setrio_bizcal_cache_time_' . $cache_key, '');
		?>
		<div style="display:flex;flex-wrap: wrap;justify-content: space-between;gap: 10px;">
			<div style="display:flex;flex-direction:column;width: calc(100% - 390px);min-width: 400px;flex: auto;">
				<table class="wp-list-table widefat fixed">
					<thead>
						<th class="check-column"></th>
						<th><strong style="margin-left:-30px;">Caching <?php echo wp_kses_post($cache_name); ?></strong></th>
					</thead>
					<tbody>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>-default" type="radio" class="regular-text" name="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>" value="" <?php echo ('' === $cacheKeyCaching?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>-default">
									<strong>Implicit</strong> <em>Precum stabilit in GENERAL</em>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>-no" type="radio" class="regular-text" name="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>" value="0" <?php echo (('0' === '' . $cacheKeyCaching)?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>-no">
									<strong>Fara</strong> <em>Informatiile preluate direct prin api</em>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>-timed" type="radio" class="regular-text" name="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>" value="1" <?php echo (('1' === '' . $cacheKeyCaching)?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>-timed">
									<strong>Temporal</strong> <em>Informatiile vor fi preluate din baza de date daca acestea exista si nu au expirat.</em>
								</label>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>-on-fail" type="radio" class="regular-text" name="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>" value="2" <?php echo (('2' === '' . $cacheKeyCaching)?"checked=\"checked\"":""); ?>></label>
							</th>
							<td>
								<label for="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>-on-fail">
									<strong>Doar in caz de esec</strong> <em>Informatiile din cache se folosesc doar in caz de esec la preluarea din api.</em>
								</label>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="2">
								<p style="display:flex;">
									<input type="number" placeholder="- general -" min="0" step="1" class="regular-text" name="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>-time" id="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>-time" value="<?php echo esc_attr($cacheKeyTime); ?>" style="max-width:120px;vertical-align:top;align-self: center;"/>
									<label for="setrio-bizcal-caching-<?php echo esc_attr($cache_key); ?>-time" style="padding-left: 10px;">
										<strong>Timp general de stocare cache</strong> <em> (secunde, 0 = nelimitat, nimic=general)</em>
									</label>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
		
            <?php endif; ?>
            <?php if ($active_tab == 'aspectjqueryui') : ?>
            <?php $wp_upload_dir = wp_upload_dir($jQueryUIUploadsPath, false); ?>
            <?php 
			if (!class_exists('WP_Filesystem_Direct')){
				require_once(ABSPATH.'wp-admin/includes/class-wp-filesystem-base.php');
				require_once(ABSPATH.'wp-admin/includes/class-wp-filesystem-direct.php');
			}
			$filesystem = new WP_Filesystem_Direct( true );
			$css_dir_is_writable = false;
			if(is_dir($wp_upload_dir['path'] . '/setrio-bizcalendar')){
				$css_dir_is_writable = $filesystem->is_writable($wp_upload_dir['path']  . '/setrio-bizcalendar');
			} elseif(is_dir($wp_upload_dir['path'])){
				$css_dir_is_writable = $filesystem->is_writable($wp_upload_dir['path']);
			} else {
				$css_dir_is_writable = $filesystem->is_writable($wp_upload_dir['basedir']);
			}
			?>
            <?php $custom_css_file_exists = $filesystem->is_file($wp_upload_dir['path'] . '/setrio-bizcalendar/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css'); ?>
			<?php if (!$css_dir_is_writable) { ?>
			<p style="color:red">Din pacate permisiunile la nivel de director in modul nu permit stocarea de fisiere pentru descarcarea temei noi.</p>
			<?php } ?>
			<?php if ($css_dir_is_writable || $custom_css_file_exists) { ?>
			<table class="wp-list-table widefat fixed">
					<tbody>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-enable-custom-jquery-ui" type="checkbox" class="regular-text" name="setrio-bizcal-enable-custom-jquery-ui" value="1" <?php echo ($enableCustomJQueryUI?"checked=\"checked\"":"")?> /></label>
							</th>
							<td>
								<label for="setrio-bizcal-enable-custom-jquery-ui">
									<strong>Activeaza tema jQuery UI personalizata</em>
								</label>
							</td>
						</tr>
					</tbody>
			</table>
			<?php } ?>
			<input type="hidden" name="setrio-bizcal-jquery-ui-params" value="<?php echo esc_attr($jQueryUIParams); ?>" />
			<?php if ($css_dir_is_writable) { ?>
			<iframe id="custom-jquery-ui-iframe" src="<?php echo esc_attr(plugins_url() . '/' . dirname( plugin_basename( __FILE__ ) )); ?>/jqueryuithemeroller.php?<?php echo esc_attr($jQueryUIParams); ?>"width="100%" style="height:60vh;"></iframe>
			<p>Modificati setarile temei personalizate apoi dati click pe butonul galben <b>Salveaza tema</b></p>
			<table id="setrio-bizcal-jquery-ui-download-link-table" class="wp-list-table widefat fixed" style="display:none;">
					<tbody>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-jquery-ui-download-link" type="checkbox" class="regular-text" name="setrio-bizcal-jquery-ui-download-link" value="" disabled="disabled" /></label>
							</th>
							<td>
								<label for="setrio-bizcal-enable-custom-jquery-ui">
									<strong>La salvare se va descarca tema noua</em>
								</label>
							</td>
						</tr>
					</tbody>
			</table>
            <?php } ?>
            <?php endif; ?>
            <?php if ($active_tab == 'aspectvue') : 
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
				
				$templates = array();
				foreach($file_paths as $file_path){
					foreach(glob($file_path . 'vue/*.php') as $file){
						if(!in_array(basename($file,'.php'),$templates)){
							$templates[] = basename($file,'.php');
						}
					}
				}
			?>
			<table class="wp-list-table widefat fixed">
					<tbody>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-enable-custom-vue" type="checkbox" class="regular-text" name="setrio-bizcal-enable-custom-vue" value="1" <?php echo ($enableCustomVue?"checked=\"checked\"":"")?> /></label>
							</th>
							<td>
								<label for="setrio-bizcal-enable-custom-vue">
									<strong>Activeaza tema VUE personalizata</em>
								</label>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="2">
								<p style="display:flex;">
									<select name="setrio-bizcal-vue-inline-template" id="setrio-bizcal-vue-inline-template">
										<?php
										foreach($templates as $template){ ?>
										<option value="<?php echo esc_attr($template); ?>" <?php echo $VueInlineTemplate == $template ? ' selected="selected"' : '' ?>><?php echo wp_kses_post($template); ?></option>
										<?php
										}
										?>
									</select>
									<label for="setrio-bizcal-vue-inline-template" style="padding-left: 10px;">
										<strong>Templata implicita</strong>
									</label>
								</p>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="2">
								<p style="display:flex;">
									<select name="setrio-bizcal-vue-popup-template" id="setrio-bizcal-vue-popup-template">
										<?php
										foreach($templates as $template){ ?>
										<option value="<?php echo esc_attr($template); ?>" <?php echo $VuePopupTemplate == $template ? ' selected="selected"' : '' ?>><?php echo wp_kses_post($template); ?></option>
										<?php
										}
										?>
									</select>
									<label for="setrio-bizcal-vue-popup-template" style="padding-left: 10px;">
										<strong>Templata implicita popup</strong>
									</label>
								</p>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="2">
								<p style="display:flex;">
									<input type="text" class="regular-text" name="setrio-bizcal-vue-button-class" id="setrio-bizcal-vue-button-class" value="<?php echo esc_attr($VueButtonClass); ?>" />
									<label for="setrio-bizcal-vue-button-class" style="padding-left: 10px;">
										<strong>Clasa buton popup</strong>
									</label>
								</p>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="2">
								<p style="display:flex;">
									<input type="text" class="regular-text" name="setrio-bizcal-vue-button-style" id="setrio-bizcal-vue-button-style" value="<?php echo esc_attr($VueButtonStyle); ?>" />
									<label for="setrio-bizcal-vue-button-style" style="padding-left: 10px;">
										<strong>Stil Inline-CSS buton popup</strong>
									</label>
								</p>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="2">
								<p style="display:flex;">
									<select name="setrio-bizcal-vue-button-type" id="setrio-bizcal-vue-button-type">
										<option value="input" <?php echo $VueButtonType == 'inline' ? ' selected="selected"' : '' ?>>input</option>
										<option value="a" <?php echo $VueButtonType == 'a' ? ' selected="selected"' : '' ?>>a</option>
										<option value="button" <?php echo $VueButtonType == 'button' ? ' selected="selected"' : '' ?>>button</option>
										<option value="vue" <?php echo $VueButtonType == 'vue' ? ' selected="selected"' : '' ?>>vue</option>
									</select>
									<label for="setrio-bizcal-vue-button-type" style="padding-left: 10px;">
										<strong>Tip button</strong>
									</label>
								</p>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="2">
								<p style="display:flex;">
									<select name="setrio-bizcal-vue-calendar-type" id="setrio-bizcal-vue-calendar-type">
										<option value="inline" <?php echo $VueCalendarType == 'inline' ? ' selected="selected"' : '' ?>>inline</option>
										<option value="menu" <?php echo $VueCalendarType == 'menu' ? ' selected="selected"' : '' ?>>menu</option>
									</select>
									<label for="setrio-bizcal-vue-calendar-type" style="padding-left: 10px;">
										<strong>Tip calendar</strong>
									</label>
								</p>
							</td>
						</tr>
					</tbody>
			</table>
			<input type="hidden" name="setrio-bizcal-vue-params" />
			<div>
				<a class="button" target="custom-vue-iframe" href="<?php echo esc_attr(plugins_url() . '/' . dirname( plugin_basename( __FILE__ ) )); ?>/vuethemeroller.php?config_file=<?php echo esc_attr(urlencode($VUEUploadsUrl)); ?>" onclick="if(!confirm('Sigur doriti reincarcarea temei personalizate conform ultimei salvari?')){event.preventDefault();return false;}">Reincarca</a>
				<a class="button" target="custom-vue-iframe" href="<?php echo esc_attr(plugins_url() . '/' . dirname( plugin_basename( __FILE__ ) )); ?>/vuethemeroller.php?config_file=<?php echo esc_attr(urlencode(plugins_url('/js/vue-params.jsonp', dirname(__FILE__)))); ?>" onclick="if(!confirm('Sigur doriti incarcarea temei implicite?')){event.preventDefault();return false;}">Implicit</a>
				<a class="button" target="custom-vue-iframe" href="<?php echo esc_attr(plugins_url() . '/' . dirname( plugin_basename( __FILE__ ) )); ?>/vuethemeroller.php" onclick="if(!confirm('Sigur doriti incarcarea temei personalizate fara setari?')){event.preventDefault();return false;}">Gol</a>
			</div>
			<iframe id="custom-vue-iframe" name="custom-vue-iframe" src="<?php echo esc_attr(plugins_url() . '/' . dirname( plugin_basename( __FILE__ ) )); ?>/vuethemeroller.php?config_file=<?php echo esc_attr(urlencode($VUEUploadsUrl)); ?>"width="100%" style="height:90vh;"></iframe>
			<script>
			function saveTheme(params){
				console.log(params);
				jQuery('[name="setrio-bizcal-vue-params"]').val(JSON.stringify(params));
			}
			</script>
			<p>Modificati setarile temei personalizate apoi dati click pe butonul <b>Salveaza tema</b></p>
            <?php endif; ?>
            <?php if ($active_tab == 'acorduri') : ?>
            <div id="setrio-bizcal-admin-acorduri">
                <h2>Afiseaza in site</h2>
				<table class="wp-list-table widefat fixed">
					<tbody>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-enable-terms" type="checkbox" class="regular-text" name="setrio-bizcal-enable-terms" value="1" <?php echo ($enableTerms?"checked=\"checked\"":"")?> /></label>
							</th>
							<td>
								<label for="setrio-bizcal-enable-terms">
									<strong>Termeni si conditii de utilizare.</strong> <em>Nota. Obligatoriu pentru finalizare programare</em>
								</label>
								<?php $mesaj = 'lblTerms'; ?>
								<p><textarea rows="2" class="regular-text" placeholder="<?php echo esc_attr(setrio_bizcal_message($mesaj, true)); ?>" style="width: 100%;"
										name="setrio-bizcal-msg-<?php echo esc_attr($mesaj); ?>"><?php echo esc_textarea(setrio_bizcal_message($mesaj))?></textarea></p>
							</td>
							<td>
								<p>
								<label for="setrio-bizcal-terms-link">
									<strong>Link catre Termeni si conditii:</strong>
								</label>
								<label style="float:right;">
									Pagina:
									<?php 
									wp_dropdown_pages(
										array(
											'name'              => 'setrio-bizcal-terms-post-id',
											'show_option_none'  => wp_kses_post(__( '&mdash; Select &mdash;' )),
											'option_none_value' => '0',
											'selected'          => wp_kses_post($termsPostId),
											'post_status'       => array( 'publish' ),
										)
									);
									?>
								</label>
								</p>
								<div style="display: flex;width: 100%;flex-direction: row;justify-content: space-between;flex-wrap: wrap;">
								<input class="regular-text" style="width: 100%;min-width: 250px;display: inline-flex;flex: 1;align-self: baseline;" type="text" id="setrio-bizcal-terms-link" name="setrio-bizcal-terms-link" placeholder="Introduceti link-ul sau lasati liber daca ati ales pagina" value="<?php echo esc_attr($termsLink) ?>"/>
								<?php $mesaj = 'lblTermsText'; ?>
								<textarea rows="1" style="width: 100%;min-width: 180px;display: inline-flex;flex: 1;min-height:30px;max-height:100px;" class="regular-text" placeholder="Text buton" style="width: 100%;"
								name="setrio-bizcal-msg-<?php echo esc_attr($mesaj); ?>"><?php echo esc_textarea(setrio_bizcal_message($mesaj))?></textarea>
								</div>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-enable-data-policy" type="checkbox" class="regular-text" name="setrio-bizcal-enable-data-policy" value="1" <?php echo ($enableDataPolicy?"checked=\"checked\"":"")?> /></label>
							</th>
							<td>
								<label for="setrio-bizcal-enable-data-policy">
									<strong>GDPR - Protectia datelor cu caracter personal.</strong> <em>Nota. Obligatoriu pentru finalizare programare</em>
								</label>
								<?php $mesaj = 'lblDataPolicy'; ?>
								<p><textarea rows="2" class="regular-text" placeholder="<?php echo esc_attr(setrio_bizcal_message($mesaj,true)); ?>" style="width: 100%;"
										name="setrio-bizcal-msg-<?php echo esc_attr($mesaj); ?>"><?php echo esc_textarea(setrio_bizcal_message($mesaj))?></textarea></p>
							</td>
							<td>
								<p>
								<label for="setrio-bizcal-data-policy-link">
									<strong>Link catre Protectia datelor cu caracter personal:</strong>
								</label>
								<label style="float:right;">
									Pagina:
									<?php 
									wp_dropdown_pages(
										array(
											'name'              => 'setrio-bizcal-data-policy-post-id',
											'show_option_none'  => wp_kses_post(__( '&mdash; Select &mdash;' )),
											'option_none_value' => '0',
											'selected'          => wp_kses_post($dataPolicyPostId),
											'post_status'       => array( 'publish' ),
										)
									);
									?>
								</label>
								</p>
								<div style="display: flex;width: 100%;flex-direction: row;justify-content: space-between;flex-wrap: wrap;">
								<input class="regular-text" style="width: 100%;min-width: 250px;display: inline-flex;flex: 1;align-self: baseline;" type="text" id="setrio-bizcal-data-policy-link" name="setrio-bizcal-data-policy-link" placeholder="Introduceti link-ul sau lasati liber daca ati ales pagina" value="<?php echo esc_attr($dataPolicyLink) ?>"/>
								<?php $mesaj = 'lblDataPolicyText'; ?>
								<textarea rows="1" style="width: 100%;min-width: 180px;display: inline-flex;flex: 1;min-height:30px;max-height:100px;" class="regular-text" placeholder="Text buton" style="width: 100%;"
								name="setrio-bizcal-msg-<?php echo esc_attr($mesaj); ?>"><?php echo esc_textarea(setrio_bizcal_message($mesaj))?></textarea>
								</div>
							</td>
						</tr>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-enable-newsletter" type="checkbox" class="regular-text" name="setrio-bizcal-enable-newsletter" value="1" <?php echo ($enableNewsletter?"checked=\"checked\"":"")?> /></label>
							</th>
							<td>
								<label for="setrio-bizcal-enable-newsletter">
									<strong>Marketing - Inscriere la Newsletter</strong> <em>Nota. Optional. Nu este obligatoriu pentru finalizare programare</em>
								</label>
								<?php $mesaj = 'lblNewsletter'; ?>
								<p><textarea rows="2" class="regular-text" placeholder="<?php echo esc_attr(setrio_bizcal_message($mesaj,true)); ?>" style="width: 100%;"
										name="setrio-bizcal-msg-<?php echo esc_attr($mesaj); ?>"><?php echo esc_textarea(setrio_bizcal_message($mesaj))?></textarea></p>
							</td>
						</tr>
					</tbody>
				</table>
            </div>
            <?php endif; ?>
            <?php if ($active_tab == 'link-tracking') : ?>
            <div id="setrio-bizcal-admin-link-tracking">
                <h2>Link Tracking</h2>
				<table class="wp-list-table widefat fixed">
					<tbody>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-enable-success-redirect" type="checkbox" class="regular-text" name="setrio-bizcal-enable-success-redirect" value="1" <?php echo ($enableSuccessRedirect?"checked=\"checked\"":"")?> /></label>
							</th>
							<td>
								<label for="setrio-bizcal-enable-success-redirect">
									<strong>Pagina de succes</strong> <em>Nota. Optional. Dupa programare cu succes utilizatorul va fi redirectionat catre pagina de succes aleasa</em>
								</label>
								<p>
									In acea pagina (doar interne WordPress) puteti introduce shortcode-ul <code>[bizcal_detalii_programare]</code> care contine urmatoarele informatii:
								</p>
								<?php $mesaj = 'txtShortcodeDetaliiProgramare'; ?>
								<p><textarea rows="2" class="regular-text" placeholder="<?php echo esc_attr(setrio_bizcal_message($mesaj,true)); ?>" style="width: 100%;"
										name="setrio-bizcal-msg-<?php echo esc_attr($mesaj); ?>"><?php echo esc_textarea(setrio_bizcal_message($mesaj))?></textarea></p>
								<p>Pentru afisarea unei anumite informatii, puteti utiliza shortcode-ul <code>[bizcal_detalii_programare key="<strong>nume</strong>"]</code> unde in loc de <code>nume</code> puteti utiliza si oricare din: <code>telefon</code>, <code>email</code>, <code>specialitatea</code>, <code>locatia</code>, <code>serviciu</code>, <code>plata</code>, <code>medic</code>, <code>data</code>, <code>pret</code>, <code>observatii</code>, <code>acord_termeni</code>, <code>acord_gdpr</code>, <code>acord_newsletter</code>, <code>ics_href</code>, <code>vcs_href</code>, <code>cal_img_src</code></p>
							</td>
							<td>
								<p>
									<label for="setrio-bizcal-success-redirect-link">
										<strong>Link catre Pagina de succes:</strong>
									</label>
									<label style="float:right;">
										Pagina:
										<?php 
										wp_dropdown_pages(
											array(
												'name'              => 'setrio-bizcal-success-redirect-post-id',
												'show_option_none'  => wp_kses_post(__( '&mdash; Select &mdash;' )),
												'option_none_value' => '0',
												'selected'          => wp_kses_post($successRedirectPostId),
												'post_status'       => array( 'publish' ),
											)
										);
										?>
									</label>
								</p>
								<input class="regular-text" style="width: 100%;min-width: 250px;display: inline-flex;flex: 1;align-self: baseline;" type="text" id="setrio-bizcal-success-redirect-link" name="setrio-bizcal-success-redirect-link" placeholder="Introduceti link-ul sau lasati liber daca ati ales pagina" value="<?php echo esc_attr($successRedirectLink) ?>"/>
								<p>In urma programarii cu succes, se genereaza un cod unic de acces in pagina de succes. Shortcode-ul <code>[bizcal_detalii_programare]</code> afiseaza informatiile dorite si invalideaza codul de acces.</p>
								<p>Pagina de succes poate fi accesata <b>o singura data per cod de acces</b>. Reaccesarile ulterioare redirectioneaza automat catre pagina principala. <b>Nu alegeti pagina ce contine formularul de programare.</b></p>
							</td>
						</tr>
					</tbody>
				<table>
            </div>
            <?php endif; ?>
            <?php if ($active_tab == 'custom-css') : ?>
            <div id="setrio-bizcal-admin-custom-css">
                <h2>Custom CSS</h2>
				<table class="wp-list-table widefat fixed">
					<tbody>
						<tr>
							<th valign="top" class="check-column">
								<label><input id="setrio-bizcal-enable-custom-css" type="checkbox" class="regular-text" name="setrio-bizcal-enable-custom-css" value="1" <?php echo ($enableCustomCss?"checked=\"checked\"":"")?> /></label>
							</th>
							<td>
								<label for="setrio-bizcal-enable-custom-css">
									<strong>Activare</strong> <em>Nota. Optional. Afisare personalizata. Codul CSS va fi inserat in pagina cu shortcode-ul formularului.</em>
								</label>
								<?php $mesaj = 'txtShortcodeDetaliiProgramare'; ?>
								<p><textarea rows="2" class="regular-text" id="setrio-bizcal-custom-css" style="width: 100%;" name="setrio-bizcal-custom-css"><?php echo esc_textarea($customCss); ?></textarea></p>
							</td>
						</tr>
					</tbody>
				<table>
            </div>
            <?php endif; ?>
            <?php if ($active_tab == 'email') : ?>
            <div id="setrio-bizcal-admin-email">
                <h2>Setări e-mail de confirmare</h2>
     
                <div class="options">
                    <p>
                        <label>Subiect</label>
                        <br />
                        <input type="text" class="regular-text" name="setrio-bizcal-appointment-email-subject" value="<?php echo esc_attr($appointmentEmailSubject); ?>" />
                    </p>
                    <p>
                        <label>Adresă destinatar</label>
                        <br />
                        <input type="text" class="regular-text" name="setrio-bizcal-appointment-email-to" value="<?php echo esc_attr($appointmentEmailTo); ?>" />
                    </p>
                    <p>
                        <label>
                        <input type="checkbox" class="regular-text" name="setrio-bizcal-add-service-to-obs" value="1" <?php echo esc_attr(($addServiceNameInObservations?"checked=\"1\"":"")); ?> />
                        Adaugă automat denumirea serviciului medical în câmpul de observații</label>
                    </p>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($active_tab == 'mesaje') : ?>
			<style>
			#setrio-bizcal-mesaje p>label{
				white-space:pre-wrap;
			}
			</style>
            <div id="setrio-bizcal-mesaje">
                <h2>Personalizare mesaje</h2>
                <style>
				textarea {
					color: red;
				}
				textarea::-webkit-input-placeholder { /* Edge */
				  color: green;
				}

				textarea:-ms-input-placeholder { /* Internet Explorer 10-11 */
				  color: green;
				}

				textarea::placeholder {
				  color: green;
				}
				</style>
                <div class="options">
                <?php foreach (setrio_bizcal_language_items() as $categorie => $mesaje) : ?>
                    <h3><?php echo wp_kses_post($categorie); ?></h3>
                    <?php foreach ($mesaje as $mesaj) : ?>
                        <p>
                            <label style="width: 100%;"><code><?php echo esc_textarea(setrio_bizcal_message($mesaj, true))?></code><button type="button" onclick="jQuery(this).parent().nextAll('textarea').html(jQuery(this).prev().html()).select();">&darr;</button></label>
                            <br />
                            <textarea rows="2" class="regular-text" style="width: 100%;" placeholder="<?php echo esc_attr(esc_attr(setrio_bizcal_message($mesaj,true))); ?>"
                                name="setrio-bizcal-msg-<?php echo esc_attr($mesaj); ?>"><?php echo setrio_bizcal_message($mesaj) == setrio_bizcal_message($mesaj, true) ? '' : esc_textarea(setrio_bizcal_message($mesaj)); ?></textarea>
                        </p>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
			<style>
			.check-column label > input[type=checkbox],
			.check-column label > input[type=radio]{
				margin-top: 10px;
			}
			</style>
        <?php
            wp_nonce_field('setrio-bizcal-settings-save', 'setrio-bizcal-settings');
            if (($active_tab == "medici") && ($action == "physician_edit"))
            {
                submit_button("Salvează", "primary", "save", false);
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                submit_button("Renunță", "secondary", "cancel", false);
            }
            else
            {
                submit_button();    
            }
			
			/* if (in_array($active_tab, array('link-tracking','acorduri','mesaje')))
            { ?>
			<button type="button" onclick="jQuery('#setrio-bizcal-wscontainer textarea').val('');">Reinitializeaza textele</button>
            <?php
            } */
        ?>
        </form>
    </div>
    <?php
}

function setrio_bizcal_admin_js($physician_picture_id)
{
    ?>
    <script type='text/javascript'>
        jQuery( document ).ready( function( $ ) {
            // Uploading files
            var file_frame;
            var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
            var set_to_post_id = <?php echo (int)$physician_picture_id; ?>; // Set this
            jQuery('#upload_image_button').on('click', function( event ){
                event.preventDefault();
                // If the media frame already exists, reopen it.
                if ( file_frame ) {
                    // Set the post ID to what we want
                    file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                    // Open frame
                    file_frame.open();
                    return;
                } else {
                    // Set the wp.media post id so the uploader grabs the ID we want when initialised
                    wp.media.model.settings.post.id = set_to_post_id;
                }
                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select a image to upload',
                    button: {
                        text: 'Use this image',
                    },
                    multiple: false    // Set to true to allow multiple files to be selected
                });
                // When an image is selected, run a callback.
                file_frame.on( 'select', function() {
                    // We set multiple to false so only get one image from the uploader
                    attachment = file_frame.state().get('selection').first().toJSON();
                    // Do something with attachment.id and/or attachment.url here
                    $( '#setrio-bizcal-picture-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                    $( '#setrio-bizcal-physician-picture-id' ).val( attachment.id );
                    // Restore the main post ID
                    wp.media.model.settings.post.id = wp_media_post_id;
                });
                    // Finally, open the modal
                    file_frame.open();
            });
            // Restore the main ID when the add media button is pressed
            jQuery( 'a.add_media' ).on( 'click', function() {
                wp.media.model.settings.post.id = wp_media_post_id;
            });
        });
    </script><?php
}

class SetrioBizCalAdmin
{
    public function init()
    {
        add_action( 'admin_post', array( $this, 'save' ) );
    }
 
    public function save()
    {
        global $wpdb;
        
        if ($this->has_valid_nonce() && current_user_can('manage_options'))
        {
            $active_tab = "";
            if (isset($_POST['setrio-bizcal-admin-tab']))
                $active_tab = sanitize_text_field($_POST['setrio-bizcal-admin-tab']);
                
            $enableMultipleLocations = get_option('setrio_bizcal_enable_multiple_locations', false);
            
            if ($active_tab == "serviciu")
            {
                $wsAddress = sanitize_text_field($_POST['setrio-bizcal-wsaddress']);
                $wsUser = sanitize_text_field($_POST['setrio-bizcal-wsuser']);
                $wsPass = sanitize_text_field($_POST['setrio-bizcal-wspassword']);
                $wsFakeRegister = sanitize_text_field($_POST['setrio-bizcal-fake-register']);
                $reCaptchaSiteKey = sanitize_text_field($_POST['setrio-bizcal-g-site-key']);
                $reCaptchaSecretKey = sanitize_text_field($_POST['setrio-bizcal-g-secret-key']);
                $enableMultipleLocations = (isset($_POST['setrio-bizcal-enable-multiple-locations'])
                    && ($_POST['setrio-bizcal-enable-multiple-locations'] == 1)) ? 1 : 0;
                update_option('setrio_bizcal_wsaddr', $wsAddress);
                update_option('setrio_bizcal_fake_register', $wsFakeRegister);
                update_option('setrio_bizcal_wsuser', $wsUser);
                update_option('setrio_bizcal_wspass', $wsPass);
                update_option('setrio_bizcal_g_site_key', $reCaptchaSiteKey);
                update_option('setrio_bizcal_g_secret_key', $reCaptchaSecretKey);
                update_option('setrio_bizcal_enable_multiple_locations', $enableMultipleLocations);
            }
            elseif ($active_tab == "clinica")
            {
                $clinicPhone = sanitize_text_field($_POST['setrio-bizcal-phone']);
                $clinicEmail = sanitize_text_field($_POST['setrio-bizcal-email']);
                $clinicErrEmail = sanitize_text_field($_POST['setrio-bizcal-err-email']);
                update_option('setrio_bizcal_phone', $clinicPhone);
                update_option('setrio_bizcal_email', $clinicEmail);
                update_option('setrio_bizcal_err_email', $clinicErrEmail);
            }
            elseif ($active_tab == "mobilpay")
            {
				$paymentMobilPayStatus = sanitize_text_field($_POST['setrio-bizcal-payment-mobilpay-status']);
				$paymentMobilPayTest = sanitize_text_field($_POST['setrio-bizcal-payment-mobilpay-test']);
				$paymentMobilPayAdmin = sanitize_text_field($_POST['setrio-bizcal-payment-mobilpay-admin']);
				$paymentMobilPaySignature = sanitize_text_field($_POST['setrio-bizcal-payment-mobilpay-signature']);
				$paymentMobilPayUsername = sanitize_text_field($_POST['setrio-bizcal-payment-mobilpay-username']);
				$paymentMobilPayPassword = sanitize_text_field($_POST['setrio-bizcal-payment-mobilpay-password']);
				$paymentMobilPayFree = sanitize_text_field($_POST['setrio-bizcal-payment-mobilpay-free']);
				$paymentMobilPayFreeCNAS = sanitize_text_field($_POST['setrio-bizcal-payment-mobilpay-free-cnas']);
				$paymentMobilPayGeneral = sanitize_text_field($_POST['setrio-bizcal-payment-mobilpay-general']);
				// if($paymentMobilPayStatus){
					// if(!setrio_bizcal_online_valid()){
						// $paymentMobilPayStatus = 0;
					// }
				// }
                update_option('setrio_bizcal_payment_mobilpay_admin', $paymentMobilPayAdmin);
                update_option('setrio_bizcal_payment_mobilpay_status', $paymentMobilPayStatus);
                update_option('setrio_bizcal_payment_mobilpay_general', $paymentMobilPayGeneral);
                update_option('setrio_bizcal_payment_mobilpay_test', $paymentMobilPayTest);
                update_option('setrio_bizcal_payment_mobilpay_signature', $paymentMobilPaySignature);
                update_option('setrio_bizcal_payment_mobilpay_username', $paymentMobilPayUsername);
                update_option('setrio_bizcal_payment_mobilpay_free', $paymentMobilPayFree);
                update_option('setrio_bizcal_payment_mobilpay_free_cnas', $paymentMobilPayFreeCNAS);
				if('' !== $paymentMobilPayPassword){
					update_option('setrio_bizcal_payment_mobilpay_password', $paymentMobilPayPassword);
				}
            }
            elseif (isset($_POST['setrio-bizcal-physician-uid']))
            {
                if (!isset($_POST['cancel']))
                {
                    $physician_uid = sanitize_text_field($_POST['setrio-bizcal-physician-uid']);
                    $physician_description = sanitize_text_field($_POST['setrio-bizcal-physician-description']);
                    if (isset($_POST['setrio-bizcal-physician-picture-id']))
                        $physician_picture_id = (int)$_POST['setrio-bizcal-physician-picture-id'];
                    else
                        $physician_picture_id = null;
                    if ($physician_picture_id === 0)
                        $physician_picture_id = null;
                    $wpdb->update($wpdb->prefix.'bizcal_physicians_description',
                        [
                            'description' => wp_kses_post($physician_description),
                            'physician_picture_id' => (int)$physician_picture_id
                        ],
                        [
                            'physician_uid' => wp_kses_post($physician_uid),
                        ]);
                }
                
                if (isset($_POST['_wp_http_referer']))
                {
                    $url = $_POST['_wp_http_referer'];
                    $_POST['_wp_http_referer'] = remove_query_arg(['action', 'physician_uid'], $url);
                }
            }
            elseif (isset($_POST['setrio-bizcal-physicians']))
            {
                $showPhysiciansDetails = (isset($_POST['setrio-bizcal-show-physicians-details']) && ($_POST['setrio-bizcal-show-physicians-details'] == 1)) ? 1 : 0;
                update_option('setrio_bizcal_show_physician_details', $showPhysiciansDetails);
            }
            elseif ($active_tab == "caching")
            {
				$cachingEnabled = (isset($_POST['setrio-bizcal-caching']) && ($_POST['setrio-bizcal-caching'] == 1)) ? 1 : 0;
				$cachingOnFailEnabled = (isset($_POST['setrio-bizcal-caching-on-fail']) && ($_POST['setrio-bizcal-caching-on-fail'] == 1)) ? 1 : 0;
				$cachingTime = (isset($_POST['setrio-bizcal-caching-time']) && ($_POST['setrio-bizcal-caching-time'])) ? $_POST['setrio-bizcal-caching-time'] : '';
				
				update_option('setrio_bizcal_caching', $cachingEnabled);
				update_option('setrio_bizcal_caching_on_fail', $cachingOnFailEnabled);
				update_option('setrio_bizcal_caching_time', $cachingTime);
				
				foreach(array(
					'speciality',
					'location',
					'payment_type',
					'service',
					'physician',
					'availability',
				) as $cache_key){
					$cacheKeyCaching = (isset($_POST['setrio-bizcal-caching-' . $cache_key]) && '' !== $_POST['setrio-bizcal-caching-' . $cache_key] && ((int)$_POST['setrio-bizcal-caching-' . $cache_key]>=0) && ((int)$_POST['setrio-bizcal-caching-' . $cache_key]<=2)) ? $_POST['setrio-bizcal-caching-' . $cache_key] : '';
					$cacheKeyTime = (isset($_POST['setrio-bizcal-caching-' . $cache_key . '-time']) && '' !== $_POST['setrio-bizcal-caching-' . $cache_key . '-time'] && $_POST['setrio-bizcal-caching-' . $cache_key . '-time'] >= 0) ? (int)trim($_POST['setrio-bizcal-caching-' . $cache_key . '-time']) : '';
					
					 update_option('setrio_bizcal_cache_type_' . $cache_key, $cacheKeyCaching);
					 update_option('setrio_bizcal_cache_time_' . $cache_key, $cacheKeyTime);
				}
			}
            elseif ($active_tab == "aspect")
            {
                $allCaps = (isset($_POST['setrio-bizcal-allcaps']) && ($_POST['setrio-bizcal-allcaps'] == 1)) ? 1 : 0;
                $forceVue = (isset($_POST['setrio-bizcal-force-vue']) && ($_POST['setrio-bizcal-force-vue'] == 1)) ? 1 : 0;
                $forceAdminAjax = (isset($_POST['setrio-bizcal-force-adminajax']) && ($_POST['setrio-bizcal-force-adminajax'] == 1)) ? 1 : 0;
                $autoSelectMedicalSpeciality = (isset($_POST['setrio-bizcal-autosel-speciality']) && ($_POST['setrio-bizcal-autosel-speciality'] == 1)) ? 1 : 0;
                $autoSelectLocation = (isset($_POST['setrio-bizcal-autosel-location']) && ($_POST['setrio-bizcal-autosel-location'] == 1)) ? 1 : 0;
                $autoSelectPaymentType = (isset($_POST['setrio-bizcal-autosel-payment-type']) && ($_POST['setrio-bizcal-autosel-payment-type'] == 1)) ? 1 : 0;
                $autoSelectMedicalService = (isset($_POST['setrio-bizcal-autosel-service']) && ($_POST['setrio-bizcal-autosel-service'] == 1)) ? 1 : 0;
                $autoSelectPhysician = (isset($_POST['setrio-bizcal-autosel-physician']) && ($_POST['setrio-bizcal-autosel-physician'] == 1)) ? 1 : 0;
                $allowSearchForPhysician = (isset($_POST['setrio-bizcal-allow-search-physician']) && ($_POST['setrio-bizcal-allow-search-physician'] == 1)) ? 1 : 0;
                $maxAvailabilities = (int)sanitize_text_field($_POST['setrio-bizcal-max-availabilities']);
                $minDaysToAppointment = (int)sanitize_text_field($_POST['setrio-bizcal-min-days-to-appointment']);
                $max_register_per_ip = (int)sanitize_text_field($_POST['setrio-bizcal-max_register_per_ip']);
                $appointmentParamOrder = (int)sanitize_text_field($_POST['setrio-bizcal-appointment-param-order']);
                $specialityOrder = (int)sanitize_text_field($_POST['setrio-bizcal-speciality-order']);
                $specialityOrderItems = wp_unslash(wp_kses_post($_POST['setrio-bizcal-speciality-order-items']));
                update_option('setrio_bizcal_force_adminajax', $forceAdminAjax);
                update_option('setrio_bizcal_force_vue', $forceVue);
                update_option('setrio_bizcal_all_caps', $allCaps);
                update_option('setrio_bizcal_autosel_speciality', $autoSelectMedicalSpeciality);
                if ($enableMultipleLocations)
                    update_option('setrio_bizcal_autosel_location', $autoSelectLocation);
                update_option('setrio_bizcal_autosel_payment_type', $autoSelectPaymentType);
                update_option('setrio_bizcal_autosel_service', $autoSelectMedicalService);
                update_option('setrio_bizcal_autosel_physician', $autoSelectPhysician);
                update_option('setrio_bizcal_allow_search_physician', $allowSearchForPhysician);
                update_option('setrio_bizcal_max_availabilities', $maxAvailabilities);
                update_option('setrio_bizcal_min_days_to_appointment', $minDaysToAppointment);
                update_option('setrio_bizcal_max_register_per_ip', $max_register_per_ip);
                update_option('setrio_bizcal_appointment_param_order', $appointmentParamOrder);
                update_option('setrio_bizcal_speciality_order', $specialityOrder);
				if($specialityOrder && $specialityOrderItems){
					update_option('setrio_bizcal_speciality_order_items', $specialityOrderItems);
				}
				
				$capsEnabled = (isset($_POST['setrio-bizcal-caps']) && $_POST['setrio-bizcal-caps']) ? sanitize_text_field($_POST['setrio-bizcal-caps']) : '';
				
				update_option('setrio_bizcal_caps', $capsEnabled);
				
				foreach(array(
					'speciality',
					'location',
					'payment_type',
					'service',
					'physician',
					'availability',
				) as $caps_key){
					$capsValue = (isset($_POST['setrio-bizcal-caps-' . $caps_key]) && '' !== $_POST['setrio-bizcal-caps-' . $caps_key] && ((int)$_POST['setrio-bizcal-caps-' . $caps_key]>=0) && ((int)$_POST['setrio-bizcal-caps-' . $caps_key]<=2)) ? $_POST['setrio-bizcal-caps-' . $caps_key] : '';
					
					 update_option('setrio_bizcal_caps_' . $caps_key, $capsValue);
				}
				
            }
            elseif ($active_tab == "aspectjqueryui")
            {
                $enableCustomJQueryUI = (!empty($_POST['setrio-bizcal-enable-custom-jquery-ui'])) ? 1 : 0;
				update_option('setrio_bizcal_enable_custom_jquery_ui', $enableCustomJQueryUI);
				
                $jQueryUIParams = (isset($_POST['setrio-bizcal-jquery-ui-params'])) ? (string)$_POST['setrio-bizcal-jquery-ui-params'] : '';
				update_option('setrio_bizcal_jquery_ui_params', $jQueryUIParams);
				
                $jQueryUIDownloadLink = (isset($_POST['setrio-bizcal-jquery-ui-download-link'])) ? (string)$_POST['setrio-bizcal-jquery-ui-download-link'] : '';
				$errors = array();
				if('' !== $jQueryUIDownloadLink){
					$wp_upload_dir = wp_upload_dir();
					if($wp_upload_dir['error']){
						$errors[] = $wp_upload_dir['error'];
					}
					
					if ( ! wp_mkdir_p( $wp_upload_dir['path'] . '/setrio-bizcalendar' ) ) {
						$errors[] = "Target directory is not writable.";
					} elseif(preg_match('/&zThemeParams=(.*?)(&|#|$)/', $jQueryUIDownloadLink, $matches)){
						
						$response = wp_remote_post('https://download.jqueryui.com/download', array(
							'method'      => 'POST',
							'timeout'     => 45,
							'redirection' => 5,
							'httpversion' => '1.0',
							'blocking'    => true,
							'headers'     => array(),
							'body'        => array(
								'version' => '1.12.1',
								'theme-folder-name' => 'setrio-bizcal-related',
								'scope' => '.setrio-bizcal-related',
								'theme' => 'version=1.12.1&' . $jQueryUIParams,
							),
						));
						if('application/zip' === $response['headers']['content-type']){
							if('attachment; filename=jquery-ui-1.12.1.custom.zip' === $response['headers']['content-disposition']){
								$zip_file_path =  $wp_upload_dir['path'] . '/setrio-bizcalendar/jquery-ui-css-'.gmdate("YmdHis").'.zip';
								
								if (!class_exists('WP_Filesystem')){
									require_once(ABSPATH.'wp-admin/includes/class-wp-filesystem-base.php');
								}
								WP_Filesystem();
								global $wp_filesystem;
								
								if($wp_filesystem->put_contents($zip_file_path, $response['body'])){
									$target_dir = $wp_upload_dir['path'] . '/setrio-bizcalendar';
									if (unzip_file($zip_file_path, $target_dir)) {
										update_option('setrio_bizcal_jquery_ui_uploads_path', trim($wp_upload_dir['subdir'],'/'));
										// Now that the zip file has been used, destroy it
										unlink($zip_file_path);
									} else {
										$errors[] = "Could not unzip " . $zip_file_path . '. Please check folder permissions';
									}
								} else {
									$errors[] = "Could not write to file " . $zip_file_path . ' to ' . $target_dir . '. Please check folder permissions';
								}
								
							} else {
								$errors[] = "Invalid response. Expected file name is jquery-ui-1.12.1.custom.zip";
							}
						} else {
							$errors[] = "Invalid response. Expected Response is not zip";
						}
						
						// var_dump($response); die;
					} else {
						$errors[] = "Invalid theme download url";
					}
				}
				if($errors){
					echo '<pre>'; 
					echo wp_kses_post(htmlspecialchars(print_r(array(
						'errors'=> $errors
					),true)));
					die;
				}
            }
            elseif ($active_tab == "aspectvue")
            {
                $enableCustomVue = (!empty($_POST['setrio-bizcal-enable-custom-vue'])) ? 1 : 0;
				update_option('setrio_bizcal_enable_custom_vue', $enableCustomVue);
				
				
				$VueInlineTemplate = wp_unslash(wp_kses_post($_POST['setrio-bizcal-vue-inline-template']));
				update_option('setrio_bizcal_vue_inline_template', $VueInlineTemplate);
				$VuePopupTemplate = wp_unslash(wp_kses_post($_POST['setrio-bizcal-vue-popup-template']));
				update_option('setrio_bizcal_vue_popup_template', $VuePopupTemplate);
				$VueCalendarType = wp_unslash(wp_kses_post($_POST['setrio-bizcal-vue-calendar-type']));
				update_option('setrio_bizcal_vue_calendar_type', $VueCalendarType);
				$VueButtonClass = wp_unslash(wp_kses_post($_POST['setrio-bizcal-vue-button-class']));
				update_option('setrio_bizcal_vue_button_class', $VueButtonClass);
				$VueButtonStyle = wp_unslash(wp_kses_post($_POST['setrio-bizcal-vue-button-style']));
				update_option('setrio_bizcal_vue_button_style', $VueButtonStyle);
				$VueButtonType = wp_unslash(wp_kses_post($_POST['setrio-bizcal-vue-button-type']));
				update_option('setrio_bizcal_vue_button_type', $VueButtonType);
				
                $VUEParams = (isset($_POST['setrio-bizcal-vue-params'])) ? wp_unslash(wp_kses_post($_POST['setrio-bizcal-vue-params'])) : '';
				
				if(!empty($VUEParams)){
					$pattern = "/\"[^\"]+\":\{\}/";
					while(preg_match($pattern, $VUEParams)){
						$VUEParams = preg_replace("/,?\"[^\"]+\":(\{\}|\[\]|null|0|\"\")/",'', $VUEParams);
						$VUEParams = preg_replace("/\{,/",'{', $VUEParams);
					}
					
					// update_option('setrio_bizcal_vue_params', json_decode($VUEParams, true));
					
					$wp_upload_dir = wp_upload_dir();
					$errors = array();
					if($wp_upload_dir['error']){
						$errors[] = $wp_upload_dir['error'];
					}
					if ( !$errors && ! wp_mkdir_p( $wp_upload_dir['path'] . '/setrio-bizcalendar' ) ) {
						$errors[] = "Target directory is not writable.";
					}
					if(!$errors){
						$vue_params_file = 'setrio-bizcalendar/vue-params-'.gmdate("YmdHis").'.jsonp';
						$zip_file_path =  $wp_upload_dir['path'] . '/' . $vue_params_file;
						$filesystem->put_contents($zip_file_path, json_encode(json_decode($VUEParams), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
						
						update_option('setrio_bizcal_vue_uploads_path', trim($wp_upload_dir['subdir'],'/'));
						update_option('setrio_bizcal_vue_params_file', $vue_params_file);
					}
				}
            }
            elseif ($active_tab == "acorduri")
            {
                $enableNewsletter = (isset($_POST['setrio-bizcal-enable-newsletter']) && ($_POST['setrio-bizcal-enable-newsletter'] == 1)) ? 1 : 0;
                update_option('setrio_bizcal_enable_newsletter', $enableNewsletter);
                $enableTerms = (isset($_POST['setrio-bizcal-enable-terms']) && ($_POST['setrio-bizcal-enable-terms'] == 1)) ? 1 : 0;
                update_option('setrio_bizcal_enable_terms', $enableTerms);
                $enableDataPolicy = (isset($_POST['setrio-bizcal-enable-data-policy']) && ($_POST['setrio-bizcal-enable-data-policy'] == 1)) ? 1 : 0;
                update_option('setrio_bizcal_enable_data_policy', $enableDataPolicy);
                $dataPolicyPostId = isset($_POST['setrio-bizcal-data-policy-post-id']) ? (int)$_POST['setrio-bizcal-data-policy-post-id'] : 0;
                update_option('setrio_bizcal_data_policy_post_id', $dataPolicyPostId);
                $termsPostId = isset($_POST['setrio-bizcal-terms-post-id']) ? (int)$_POST['setrio-bizcal-terms-post-id'] : 0;
                update_option('setrio_bizcal_terms_post_id', $termsPostId);
                $termsLink = isset($_POST['setrio-bizcal-terms-link']) ? sanitize_text_field($_POST['setrio-bizcal-terms-link']) : '';
                update_option('setrio_bizcal_terms_link', $termsLink);
                $dataPolicyLink = isset($_POST['setrio-bizcal-data-policy-link']) ? sanitize_text_field($_POST['setrio-bizcal-data-policy-link']) : '';
                update_option('setrio_bizcal_data_policy_link', $dataPolicyLink);
				
				foreach (setrio_bizcal_language_items() as $categorie => $mesaje)
                {
					if('Acorduri' !== $categorie) continue;
                    foreach ($mesaje as $mesaj)
                    {
                        if (isset($_POST['setrio-bizcal-msg-'.$mesaj]))
                        {
                            $mesajPersonalizat = wp_unslash(wp_kses_post($_POST['setrio-bizcal-msg-'.$mesaj]));
                            if ($mesajPersonalizat != setrio_bizcal_message($mesaj, true))
                                update_option('setrio_bizcal_msg_'.$mesaj, $mesajPersonalizat);
                            else
                                update_option('setrio_bizcal_msg_'.$mesaj, '');
                        }
                    }
                }
            }
            elseif ($active_tab == "link-tracking")
            {
				$enableSuccessRedirect = (isset($_POST['setrio-bizcal-enable-success-redirect']) && ($_POST['setrio-bizcal-enable-success-redirect'] == 1)) ? 1 : 0;
                update_option('setrio_bizcal_enable_success_redirect', $enableSuccessRedirect);
				$successRedirectPostId = isset($_POST['setrio-bizcal-success-redirect-post-id']) ? (int)$_POST['setrio-bizcal-success-redirect-post-id'] : 0;
                update_option('setrio_bizcal_success_redirect_post_id', $successRedirectPostId);
                $successRedirectLink = isset($_POST['setrio-bizcal-success-redirect-link']) ? sanitize_text_field($_POST['setrio-bizcal-success-redirect-link']) : '';
                update_option('setrio_bizcal_success_redirect_link', $successRedirectLink);
				
				foreach (setrio_bizcal_language_items() as $categorie => $mesaje)
                {
					if('Link tracking' !== $categorie) continue;
                    foreach ($mesaje as $mesaj)
                    {
                        if (isset($_POST['setrio-bizcal-msg-'.$mesaj]))
                        {
                            $mesajPersonalizat = wp_unslash(wp_kses_post($_POST['setrio-bizcal-msg-'.$mesaj]));
                            if ($mesajPersonalizat != setrio_bizcal_message($mesaj, true))
                                update_option('setrio_bizcal_msg_'.$mesaj, $mesajPersonalizat);
                            else
                                update_option('setrio_bizcal_msg_'.$mesaj, '');
                        }
                    }
                }
            }
            elseif ($active_tab == "custom-css")
            {
				$enableCustomCss = (isset($_POST['setrio-bizcal-enable-custom-css']) && ($_POST['setrio-bizcal-enable-custom-css'] == 1)) ? 1 : 0;
                update_option('setrio_bizcal_enable_custom_css', $enableCustomCss);
				
				if (isset($_POST['setrio-bizcal-custom-css']))
				{
					$mesajPersonalizat = wp_unslash(wp_kses_post($_POST['setrio-bizcal-custom-css']));
					update_option('setrio_bizcal_custom_css', $mesajPersonalizat);
				}
            }
            elseif ($active_tab == "email")
            {
                $appointmentEmailTo = sanitize_text_field($_POST['setrio-bizcal-appointment-email-to']);
                $appointmentEmailSubject = sanitize_text_field($_POST['setrio-bizcal-appointment-email-subject']);
                $addServiceNameInObservations = (isset($_POST['setrio-bizcal-add-service-to-obs']) && ($_POST['setrio-bizcal-add-service-to-obs'] == 1)) ? 1 : 0;
                update_option('setrio_bizcal_appointment_email_to', $appointmentEmailTo);
                update_option('setrio_bizcal_appointment_email_subject', $appointmentEmailSubject);
                update_option('setrio_bizcal_add_service_to_obs', $addServiceNameInObservations);
            }
            elseif ($active_tab == "mesaje")
            {
                foreach (setrio_bizcal_language_items() as $categorie => $mesaje)
                {
                    foreach ($mesaje as $mesaj)
                    {
                        if (isset($_POST['setrio-bizcal-msg-'.$mesaj]))
                        {
                            $mesajPersonalizat = wp_unslash(wp_kses_post($_POST['setrio-bizcal-msg-'.$mesaj]));
                            if ($mesajPersonalizat != setrio_bizcal_message($mesaj, true))
                                update_option('setrio_bizcal_msg_'.$mesaj, $mesajPersonalizat);
                            else
                                update_option('setrio_bizcal_msg_'.$mesaj, '');
                        }
                    }
                }
            }
            
            $this->redirect_to_admin_page();
        }
        else
        {
            echo "Nu aveți dreptul să modificați aceste opțiuni!";
        }
    }
    
    private function has_valid_nonce()
    {
        // If the field isn't even in the $_POST, then it's invalid.
        if (!isset( $_POST['setrio-bizcal-settings']))
        {
            return false;
        }
 
        $field = wp_unslash($_POST['setrio-bizcal-settings']);
        $action = 'setrio-bizcal-settings-save';
 
        return wp_verify_nonce($field, $action);
    }
    
    private function redirect_to_admin_page()
    {
        // To make the Coding Standards happy, we have to initialize this.
        if (!isset($_POST['_wp_http_referer']))
        {
            $_POST['_wp_http_referer'] = wp_login_url();
        }
 
        // Sanitize the value of the $_POST collection for the Coding Standards.
        $url = sanitize_text_field(wp_unslash($_POST['_wp_http_referer']));
 
        // Finally, redirect back to the admin page.
        wp_safe_redirect(urldecode($url));
        exit;
    }
}
class SetrioBizCalExampleSortedIterator extends SplHeap
{
	public function __construct(Iterator $iterator, $sort, $order)
	{
		$this->sort = $sort;
		$this->order = $order;
		foreach ($iterator as $item) {
			$this->insert($item);
		}
	}
	public function cmp($a,$b){
		switch($this->sort){
			case 'date_modified':
				return $a->getMTime() == $b->getMTime() ? 0 : ($a->getMTime() > $b->getMTime() ? -1 : 1);
			case 'date_created':
				return $a->creation_date == $b->creation_date ? 0 : ($a->creation_date > $b->creation_date ? -1 : 1);
			default:
				return strnatcasecmp($b->getRealpath(), $a->getRealpath());
		}
	}
	public function compare(mixed $a, mixed $b):int
	{
		switch($this->order){
			case 0:
			case 'desc':
			case 'DESC':
				return -$this->cmp($a, $b);
		}
		return $this->cmp($a, $b);
	}
}
class SetrioBizCalAdminMobilPayLogGrid extends WP_List_Table {
	static $logcontent = '';
    /** Class constructor */
    public function __construct()
    {
        parent::__construct([
            'singular' => __( 'Log', 'sp' ), //singular name of the listed records
            'plural'   => __( 'Loguri', 'sp' ), //plural name of the listed records
            'ajax'     => true //should this table support ajax?
        ]);
    }
    
    static $ppt = 5;
    public static function getLogs($per_page = 5, $page_number = 1)
    {
		$directory = WP_CONTENT_DIR .'/Mobilpay/Logs/';
		$total = 0;
		$filter = function ($file, $key, $iterator) use (&$total){
			$f = $file->isFile();
			if($f){
				$fd = $file->openFile('r');
				$line = $fd->fread(19);
				$file->creation_date = strtotime(preg_replace('/\..*/', '', $line));
				$total ++;
			}
			return $f;
		};
		$innerIterator = new RecursiveDirectoryIterator(
			$directory,
			RecursiveDirectoryIterator::SKIP_DOTS
		);
		
		
		$iterator = new RecursiveIteratorIterator(
			new RecursiveCallbackFilterIterator($innerIterator, $filter)
		);
		
		$iterator->setMaxDepth(1);
		
		if (empty($_REQUEST['orderby']))
            $orderby = "date_modified";
        else
            $orderby = $_REQUEST['orderby'];
            
        if (empty($_REQUEST['order']))
            $order = "DESC";
        else
            $order = $_REQUEST['order'];
		
		$sit = new SetrioBizCalExampleSortedIterator($iterator, $orderby, $order);
		
		$results = [];
		$skip = $per_page * ($page_number - 1);
		$skip_index = 0;
		$logname = empty($_REQUEST['logname']) ? '' : trim($_REQUEST['logname']);
		foreach ($sit as $file) {
			$skip_index ++;
			if($skip_index <= $skip) continue;
			if($file->getFileName() == $logname){
				static::$logcontent = $file;
			}
			$results[] = [
				'name' => $file->getFileName(),
				'date_created' => $file->creation_date,
				'date_modified' => $file->getMTime(),
			];
			if($skip_index >= $per_page + $skip) break;
		}
		static::$ppt = $total;
		
		
		// print_r($results); die;
		return $results;
    }
    
    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count()
    {
		return static::$ppt;
    }

    /** Text displayed when no customer data is available */
    public function no_items()
    {
        echo wp_kses_post(__('Nu există niciun log mobilpay.'));
    }
    /**
     * Render a column when no column specific method exists.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name)
        {
            case 'log':
				return '<a href="' . esc_attr(add_query_arg('logname',$item['name'])) . '" class="incarca-log">Incarca Log</a>';
            case 'date_modified':
            case 'date_created':
                return gmdate('Y-m-d H:i:s', $item[$column_name]);
            case 'name':
                return $item[$column_name];
            default:
                return $item[$column_name];
        }
    }

    /**
     * Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'name' => __( 'Nume' ),
            'date_created'  => __( 'Data creare'),
            'date_modified'  => __( 'Data modificare'),
            'log'  => __( 'Continut'),
        );
        
        return $columns;
    }

    /**
    * Columns to make sortable.
    *
    * @return array
    */
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', true),
            'date_created' => array('date_created', true),
            'date_modified' => array('date_modified', 'DESC'),
        );

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = [];
        return $actions;
    }
    
    
    /**
    * Handles data query and filter, sorting, and pagination.
    */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $per_page     = $this->get_items_per_page( 'customers_per_page', 5);
        $current_page = $this->get_pagenum();
		$items = self::getLogs($per_page, $current_page);
        $total_items  = self::record_count();

        $this->set_pagination_args( [
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ]);

        $this->items = $items;
    }
}
class SetrioBizCalAdminPhysiciansGrid extends WP_List_Table
{
    /** Class constructor */
    public function __construct()
    {
        parent::__construct([
            'singular' => __( 'Medic', 'sp' ), //singular name of the listed records
            'plural'   => __( 'Medici', 'sp' ), //plural name of the listed records
            'ajax'     => false //should this table support ajax?
        ]);
    }
    
    private static function getPhysiciansFromWebService()
    {
        $physicians = array();
        
        $client = new SetrioBizCal_BizMedicaServiceClient();
        $specialitiesResponse = $client->getMedicalSpecialities();
  
        if (!setrio_bizcal_is_valid_json($specialitiesResponse))
            throw new Exception(wp_kses_post(setrio_bizcal_parse_service_exception($specialitiesResponse)));
   
        $specialitiesData = json_decode($specialitiesResponse);

        if (($specialitiesData->ErrorCode == 0) && ($specialitiesData->ErrorMessage == ""))
        {
            foreach ($specialitiesData->Specialities as $speciality)
            {
                $physiciansParams = json_encode(["SpecialityCode" => $speciality->Code]);
                $physiciansReponse = $client->getPhysicians($physiciansParams);
                if (!setrio_bizcal_is_valid_json($physiciansReponse))
                    throw new Exception(wp_kses_post(setrio_bizcal_parse_service_exception($physiciansReponse)));
                
                $physiciansReponse = json_decode($physiciansReponse);
                if ($physiciansReponse->ErrorMessage != "")
                    throw new Exception(wp_kses_post($physiciansReponse->ErrorMessage));
                if ($physiciansReponse->ErrorCode != 0)
                    throw new Exception(wp_kses_post("Eroare preluare medici cu codul ".$physiciansReponse->ErrorCode));
        
                foreach ($physiciansReponse->Physicians as $physician)
                {
                    $found = false;
                    foreach ($physicians as $physicianUID => $physicianDetails)
                    {
                        if ($physician->UID == $physicianUID)
                        {
                            $found = true;
                            break;
                        }
                    }
                    
                    if (!$found)
                    {
                        $physicians[$physician->UID]["name"] = $physician->Name;
                    }
                }
            }
        }
        else
            throw new Exception(wp_kses_post($specialitiesData->ErrorMessage));

        global $wpdb;
       
        foreach ($physicians as $physicianUID => $physician)
        {
            $physicianRow = $wpdb->get_row($wpdb->prepare("SELECT physician_name FROM {$wpdb->prefix}bizcal_physicians_description WHERE physician_uid = %s", [$physicianUID]));
            if (!$physicianRow)
            {
                $wpdb->insert($wpdb->prefix.'bizcal_physicians_description',
                              [
                                'physician_uid' => $physicianUID,
                                'physician_picture_id' => null,
                                'description' => '',
                                'physician_name' => $physician["name"]
                              ]);
            }
            else
            {
                if ($physicianRow->physician_name != $physician["name"])
                    $wpdb->update($wpdb->prefix.'bizcal_physicians_description',
                                  [
                                    'physician_name' => $physician["name"]
                                  ],
                                  [
                                    'physician_uid' => $physicianUID,
                                    
                                  ]);
            }
        }
    }
    
    public static function getPhysicians($per_page = 5, $page_number = 1)
    {
        self::getPhysiciansFromWebService();
        
        global $wpdb;
		
		$wpdb->setrio_orderby = "physician_name";
		if (!empty($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], ['physician_uid', 'physician_name', 'physician_picture_id', 'description']))
            $wpdb->setrio_orderby = esc_sql($_REQUEST['orderby']);
		
		$wpdb->setrio_order = "ASC";
		if (!empty($_REQUEST['order']) && in_array(strtoupper($_REQUEST['order']), ['ASC','DESC'])){
            $wpdb->setrio_order = esc_sql($_REQUEST['order']);
		}
		$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}bizcal_physicians_description ORDER BY {$wpdb->setrio_orderby} {$wpdb->setrio_order} LIMIT %d OFFSET %d", [
			(int)$per_page,
			intval(($page_number - 1 ) * $per_page),
		]), 'ARRAY_A');
		
        return $result;
    }
    
    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count()
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}bizcal_physicians_description";

        return $wpdb->get_var($wpdb->prepare($sql, []));
    }

    /** Text displayed when no customer data is available */
    public function no_items()
    {
        wp_kses_post(__('Nu există niciun medic definit.'));
    }

    /**
     * Method for physician_name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_physician_name($item)
    {
        // create a nonce
        $edit_nonce = wp_create_nonce('bizcal_edit_physician_description');
        $title = '<strong>'.$item['physician_name'].'</strong>';
        $actions = [
            'edit' => sprintf('<a href="?page=%s&action=%s&tab=medici&physician_uid=%s&_wpnonce=%s">Editează</a>',
                              esc_attr($_REQUEST['page']), 'physician_edit', $item['physician_uid'], $edit_nonce)
        ];

        return $title.$this->row_actions($actions);
    }
    
    function column_physician_picture_id($item)
    {
        return "<img id='setrio-bizcal-picture-preview' src='".wp_get_attachment_url($item['physician_picture_id'])
            ."' width='100' height='100' style='max-height: 100px; width: auto;'>";
    }
    /**
     * Render a column when no column specific method exists.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name)
        {
            case 'physician_name':
            case 'physician_uid':
            case 'description':
            case 'physician_picture_id':
                return $item[$column_name];
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'physician_name' => __( 'Medic' ),
            'description'    => __( 'Descriere'),
            'physician_picture_id'  => __( 'Poza'),
            'physician_uid'  => __( 'Identificator'),
        );
        
        return $columns;
    }

    /**
    * Columns to make sortable.
    *
    * @return array
    */
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'physician_name' => array('physician_name', true)
        );

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = [];
        return $actions;
    }
    
    /*function display_rows() {

   //Get the records registered in the prepare_items method
   $records = $this->items;

   //Get the columns registered in the get_columns and get_sortable_columns methods
   list( $columns, $hidden ) = $this->get_column_info();

   //Loop for each record
   if(!empty($records)){foreach($records as $rec){

      //Open the line
        echo '<tr id="record_'.$rec->physician_uid.'">';
      foreach ( $columns as $column_name => $column_display_name ) {

         //Style attributes for each col
         $class = "class='$column_name column-$column_name'";
         $style = "";
         if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
         $attributes = $class . $style;

         //edit link
         $editlink  = '/wp-admin/link.php?action=edit&link_id='.(int)$rec->physician_uid;

         //Display the cell
         switch ( $column_name ) {
            case "physician_uid":  echo '<td '.$attributes.'>'.stripslashes($rec->physician_uid).'</td>';   break;
            case "physician_name": echo '<td '.$attributes.'>'.stripslashes($rec->physician_name).'</td>'; break;
            case "description": echo '<td '.$attributes.'>'.stripslashes($rec->description).'</td>'; break;
            case "physician_picture_id": echo '<td '.$attributes.'>'.$rec->physician_picture_id.'</td>'; break;
         }
      }

      //Close the line
      echo'</tr>';
   }}
}  */
    
    /**
    * Handles data query and filter, sorting, and pagination.
    */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $per_page     = $this->get_items_per_page( 'customers_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( [
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ]);

        $this->items = self::getPhysicians($per_page, $current_page);
    }
}

class SetrioBizCalAdminLogGrid extends WP_List_Table
{
    /** Class constructor */
    public function __construct()
    {
        parent::__construct([
            'singular' => __( 'Log', 'sp' ), //singular name of the listed records
            'plural'   => __( 'Logs', 'sp' ), //plural name of the listed records
            'ajax'     => false //should this table support ajax?
        ]);
    }
    
    public static function getWheres(){
		$sql = '';
		if (isset($_REQUEST['date_min']) && '' !== $_REQUEST['date_min']){
			$sql .= " AND TRIM(request_send_date) >= '" . preg_replace('/[^0-9 .:-]/', '', $_REQUEST['date_min']) . "'";
		}
        if (isset($_REQUEST['date_max']) && '' !== $_REQUEST['date_max']){
			$sql .= " AND TRIM(request_send_date) <= '" . preg_replace('/[^0-9 .:-]/', '', $_REQUEST['date_max']) . "'";
		}
        if (isset($_REQUEST['functions']) && !empty($_REQUEST['functions'])){
			$functions = (array)$_REQUEST['functions'];
			$functions = array_map('strtolower', $functions);
			$functions = array_intersect($functions, array_map('strtolower', ['GetAllowedPaymentTypes','GetAppointmentAvailabilities','GetDateAvailabilities','GetLocationsForSpeciality','GetMedicalServices','GetMedicalServicesPriceList','GetMedicalSpecialities','GetPaymentTypes','GetPhysicians','RegisterAppointment']));
			$sql .= " AND request_type IN ('" .implode("','", $functions) . "')";
		}
		if (isset($_REQUEST['response']) && '' !== $_REQUEST['response']){
			$sql .= " AND response LIKE '%" . esc_sql($_REQUEST['response']) . "%'";
		}
		if (isset($_REQUEST['ip']) && '' !== $_REQUEST['ip']){
			$sql .= " AND ip LIKE '%" . esc_sql($_REQUEST['ip']) . "%'";
		}
		if (isset($_REQUEST['param']) && '' !== $_REQUEST['param']){
			$sql .= " AND message LIKE '%" . esc_sql($_REQUEST['param']) . "%'";
		}
		return $sql;
	}
    public static function getLogs($per_page = 5, $page_number = 1)
    {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}bizcal_request_log";
        $wheres = self::getWheres();
		if('' !== $wheres){
			$sql .= " WHERE " . substr(trim($wheres), 3);
		}
        
        if (empty($_REQUEST['orderby']))
            $orderby = "id_request_log";
        else
            $orderby = esc_sql($_REQUEST['orderby']);
            
        if (empty($_REQUEST['order']))
            $order = "DESC";
        else
            $order = esc_sql($_REQUEST['order']);
        
        $sql .= ' ORDER BY ' . $orderby . ' ' . $order;
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

        $result = $wpdb->get_results($wpdb->prepare($sql, []), 'ARRAY_A');
        
        return $result;
    }
    
    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count()
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}bizcal_request_log";
		
        $wheres = self::getWheres();
		if('' !== $wheres){
			$sql .= " WHERE " . substr(trim($wheres), 3);
		}

        return $wpdb->get_var($wpdb->prepare($sql, []));
    }

    /** Text displayed when no customer data is available */
    public function no_items()
    {
        wp_kses_post(__('Nu există niciun log.'));
    }

    function column_dates($item)
    {
        return $item['request_send_date']
            ."</br>"
			. $item['request_response_date'];
    }
    /**
     * Render a column when no column specific method exists.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name)
        {
			case 'response':
			case 'message':
				$response = $item[$column_name];
				if('' === '' . $response) return '- Gol -';
				
				$obj = json_decode($item[$column_name]);
				if($obj){
					$response = htmlspecialchars(json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
				}
				return '<div style="width:100%;position:relative;"><div style="width:100%;max-height:300px;overflow:auto;"><pre style="margin:0"><code class="json">'
					. $response
					.'</code></pre></div><button class="button-secondary" type="button" title="Copiere" onclick="copyText(this.previousSibling)" style="position:absolute;right:-2px;margin-top:-20px">&#x2398;</button></div>';
            default:
				return $item[$column_name] ?? '';
        }
    }
	
	protected function extra_tablenav($which){
         if($which == "top"){ ?>
			
			<?php foreach($_GET as $k=>$v) if(!in_array($k, ['functions', 'date_min', 'date_max', 'param', 'response', 'ip'])){  ?>
			<?php if(is_array($v)){ ?>
				<?php foreach($v as $a=>$b){ ?>
				<input type="hidden" form="getForm" name="<?php echo esc_attr($k) ?>[<?php echo esc_attr($a) ?>]" value="<?php echo esc_attr($b); ?>">
				<?php } ?>
			<?php } else { ?>
			<input type="hidden" form="getForm" name="<?php echo esc_attr($k) ?>" value="<?php echo esc_attr($v); ?>">
			<?php } ?>
			<?php } ?>
             <div class="alignleft actions bulkactions">
                 <select form="getForm" name="functions[]" multiple id="functions" >
                     <option value="">- Functie -</option>
					 <?php foreach(['GetAllowedPaymentTypes','GetAppointmentAvailabilities','GetDateAvailabilities','GetLocationsForSpeciality','GetMedicalServices','GetMedicalServicesPriceList','GetMedicalSpecialities','GetPaymentTypes','GetPhysicians','RegisterAppointment'] as $k){ ?>
                     <option value="<?php echo esc_attr($k); ?>" <?php echo in_array($k, (isset($_REQUEST['functions']) ? (array)$_REQUEST['functions'] : [])) ? 'selected="selected"' : ''; ?>><?php echo wp_kses_post($k); ?></option>
					 <?php } ?>
                 </select>
				 <div style="display:inline-block;vertical-align:top">
					<label style="display:block;">Data min</label>
					<input form="getForm" type="text" placeholder="AAAA-LL-ZZ OO:MM:SS" name="date_min" pattern="^[0-9.\-: ]{1,19}$" value="<?php echo esc_attr((isset($_REQUEST['date_min']) ? (string) $_REQUEST['date_min'] : '')); ?>">
				 </div>
				 <div style="display:inline-block;vertical-align:top">
					<label style="display:block;">Data max</label>
					<input form="getForm" type="text" placeholder="AAAA-LL-ZZ OO:MM:SS" name="date_max" pattern="^[0-9.\-: ]{1,19}$" value="<?php echo esc_attr((isset($_REQUEST['date_max']) ? (string) $_REQUEST['date_max'] : '')); ?>">
				 </div>
				 <div style="display:inline-block;vertical-align:top">
					<label style="display:block;">Parametri</label>
					<input form="getForm" type="text" placeholder="Orice text" name="param" value="<?php echo esc_attr((isset($_REQUEST['param']) ? (string) $_REQUEST['param'] : '')); ?>">
				 </div>
				 <div style="display:inline-block;vertical-align:top">
					<label style="display:block;">Raspuns</label>
					<input form="getForm" type="text" placeholder="Orice text" name="response" value="<?php echo esc_attr((isset($_REQUEST['response']) ? (string) $_REQUEST['response'] : '')); ?>">
				 </div>
				 <div style="display:inline-block;vertical-align:top">
					<label style="display:block;">IP</label>
					<input form="getForm" type="text" placeholder="Orice text" name="ip" value="<?php echo esc_attr((isset($_REQUEST['ip']) ? (string) $_REQUEST['ip'] : '')); ?>">
				 </div>
				 <div style="display:inline-block;vertical-align:top">
					<br />
				 <button form="getForm" type="submit" name="paged" value="1" formaction="?page=setrio_bizcal_admin&tab=log">Filtreaza</button>
				 </div>
             </div>
             <?php
         }
         if($which == "bottom"){
             //The elements / filters after the table would be here
             ?><div style="display:none;"></div><?php
         }
     }

    /**
     * Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'id_request_log' => __( 'ID' ),
            'request_type' => __( 'Functie' ),
            'ip' => __( 'IP' ),
			'dates'    => __( 'Data cerere/raspuns'),
            // 'request_send_date'    => __( 'Data cerere/raspuns'),
            // 'request_response_date'  => __( 'Data raspuns'),
            'message'  => __( 'Parametri'),
            'response'  => __( 'Raspuns'),
        );
        
        return $columns;
    }

    /**
    * Columns to make sortable.
    *
    * @return array
    */
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'id_request_log' => array('id_request_log', true),
            'request_type' => array('request_type', true),
            'ip' => array('ip', true),
        );

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = [];
        return $actions;
    }
    
    /**
    * Handles data query and filter, sorting, and pagination.
    */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $per_page     = $this->get_items_per_page( 'customers_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( [
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ]);

        $this->items = self::getLogs($per_page, $current_page);
    }
}

class SetrioBizCalAdminPostSelectInstance
{
    private $field_id = null;
    private $meta_key = '';
    private $form_field_name = '';
    private $form_field_label = '';
    private $post_post_type = 'post';
    private $item_post_type = 'post';
    private $additional_query_params = array();

    function __construct($field_id, $meta_key, $form_field_name, $form_field_label, $post_post_type='post', $item_post_type='post', $additional_query_params=array()) {
        $this->field_id = $field_id;
        $this->meta_key = $meta_key;
        $this->form_field_name = $form_field_name;
        $this->form_field_label = $form_field_label;
        $this->post_post_type = $post_post_type;
        $this->item_post_type = $item_post_type;
        $this->additional_query_params = $additional_query_params;
    }

    function get_addition_query_params() {
        return $this->additional_query_params;
    }

    /*
     * Note that we're using Select2 which, for AJAX-powered selects uses a hidden field as starting point
     * and that the value should be a comma-separated list
     */
    function display() {
        global $post;
        $current_item_ids = get_post_meta( $post->ID, $this->meta_key, false );

        // Some entries may be arrays themselves!
        $processed_item_ids = array();
        foreach ($current_item_ids as $this_id) {
            if (is_array($this_id)) {
                $processed_item_ids = array_merge( $processed_item_ids, $this_id );
            } else {
                $processed_item_ids[] = $this_id;
            }
        }

        if (is_array($processed_item_ids) && !empty($processed_item_ids)) {
            $processed_item_ids = implode(',', $processed_item_ids);
        } else {
            $processed_item_ids = '';
        }
    ?>
        <p>
            <label for="<?php echo esc_attr($this->form_field_name); ?>"><?php echo wp_kses_post($this->form_field_label); ?></label>
            <input style="width: 400px;" type="hidden" name="<?php echo esc_attr($this->form_field_name); ?>" class="setrio-bizcal-post-selector" data-post-type="<?php echo esc_attr($this->item_post_type); ?>" data-setrio-bizcal-post-select-field-id="<?php echo esc_attr($this->field_id); ?>" value="<?php echo esc_attr($processed_item_ids); ?>" />
        </p>
    <?php
    }

    function save() {
        global $post;
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( isset( $_POST['post_type'] ) && $this->post_post_type == $_POST['post_type'] ) {

            // Check the user's permissions.
            if ( ! current_user_can( 'edit_post', $post->ID ) ) {
                return;
            }

            /* OK, its safe for us to save the data now. */
            
            // Make sure that it is set.
            if ( ! isset( $_POST[$this->form_field_name] ) ) {
                return;
            }

            // If it's set but empty, the lists may have been deleted, so we need to delete existing meta values
            if ( empty( $_POST[$this->form_field_name] ) ) {
                delete_post_meta($post->ID, $this->meta_key);
                return;                
            }

            // The Select2 with multiple option submits a comma-separated list of vaules
            // but we want to store each ID as a separate meta item (for compatibility with existing
            // options and queries - note that this is compatible with how the meta-box
            // plugin handles multiple selects)
            if (strpos($_POST[$this->form_field_name], ',') === false) {
                // No comma, must be single value - still needs to be in an array for now
                $post_ids = array( $_POST[$this->form_field_name] );
            } else {
                // There is a comma so it's explodable
                $post_ids = explode(',', $_POST[$this->form_field_name]);
            }
            // Delete all existing entries
            delete_post_meta($post->ID, $this->meta_key);
            // Add new entries
            if (is_array($post_ids) && !empty($post_ids)) {
                foreach($post_ids as $this_id) {
                    add_post_meta($post->ID, $this->meta_key, $this_id, false );
                }
            }
        }
    }
}

class SetrioBizCalAdminPostSelect
{
    private static $instances = array();
    
    public static function init() {
        add_action('wp_ajax_setrio_bizcal_post_select_lookup', 'SetrioBizCalAdminPostSelectInstance::post_lookup');
        add_action('wp_ajax_setrio_bizcal_get_post_titles', 'SetrioBizCalAdminPostSelectInstance::get_post_titles');
        add_action('save_post', 'SetrioBizCalAdminPostSelectInstance::do_saves');
    }

    public static function enqueue_scripts_and_styles() {
        wp_enqueue_script('setrio-bizcal-select2-script', plugins_url( '/select2/js/select2.full.js', __FILE__ ), array('jquery'),"1.0.0.0");
        wp_enqueue_script('setrio-bizcal-select2-script-jui', plugins_url( '/select2/js/select2-jquery-ui.js', __FILE__ ), array('jquery'),"1.0.0.0");

        wp_enqueue_style('setrio-bizcal-select2-style', plugins_url('/select2/css/select2.min.css', __FILE__),[],"1.0.0.0");
       
        wp_enqueue_script('setrio-bizcal-admin', plugins_url( '/admin/js/bizcalendar-admin.js', __FILE__ ), array('jquery', 'setrio-bizcal-select2-script'), "1.0.0.0", true);
    }

    /*
        This function is the AJAX call that does the search and echoes a JSON array of the results in format:
        array(
                array(
                    'id' => <post_id>,
                    'title' => <post_title>,
                )
            )

        Originally I did this as array( post_id => post_title ), but it turns out that browsers sort
        AJAX results like this by the numeric ID. So I've fixed the index of each item so that it gives
        items in the correct order in the select2 drop-down.
     */
    public static function post_lookup() {
        global $wpdb;

        $result = array();

        $search = like_escape($_REQUEST['q']);

        $post_type = $_REQUEST['post_type'];

        $field_id = $_REQUEST['s2ps_post_select_field_id'];

        // Don't forget that the callback here is a closure that needs to use the $search from the current scope
        add_filter('posts_where', function( $where ) use ($search) {
                                    $where .= (" AND post_title LIKE '%" . $search . "%'");
                                    return $where;
                                });
        $default_query = array(
                            'posts_per_page' => -1,
                            'post_status' => array('publish', 'draft', 'pending', 'future', 'private'),
                            'post_type' => $post_type,
                            'order' => 'ASC',
                            'orderby' => 'title',
                            'suppress_filters' => false,
                        );

        $custom_query = self::$instances[$field_id]->get_addition_query_params();

        $merged_query = array_merge( $default_query, $custom_query );
        $posts = get_posts( $merged_query );

        // We'll return a JSON-encoded result. 
        foreach ($posts as $this_post) {
            $post_title = $this_post->post_title;
            $id = $this_post->ID;

            $result[] = array(
                            'id' => $id,
                            'title' => $post_title,
                            );
        }

        echo json_encode($result);

        die();
    }

    public static function get_post_titles() {
        $result = array();

        if (isset($_REQUEST['post_ids'])) {
            $post_ids = $_REQUEST['post_ids'];
            if (strpos($post_ids, ',') === false) {
                // There is no comma, so we can't explode, but we still want an array
                $post_ids = array( $post_ids );
            } else {
                // There is a comma, so it must be explodable
                $post_ids = explode(',', $post_ids);
            }
        } else {
            $post_ids = array();
        }

        if (is_array($post_ids) && ! empty($post_ids)) {

            $posts = get_posts(array(
                                    'posts_per_page' => -1,
                                    'post_status' => array('publish', 'draft', 'pending', 'future', 'private'),
                                    'post__in' => $post_ids,
                                    'post_type' => 'any'
                                    ));
            foreach ($posts as $this_post) {
                $result[] = array(
                        'id' => $this_post->ID,
                        'title' => $this_post->post_title,
                    );
            }
        }

        echo json_encode($result);

        die;
    }

    /*
     * This creates a new instance, stores it, and prints the form field. It returns the instance ID.
     *
     * Parameters:
     *   $field_id - this is the 'name' of the field - used to identify it for printing or saving - it must be unique!
     *   $meta_key - the meta_key fo fetch/save data to/from
     *   $form_field_name - the name attribute of the form field to be created
     *   $form_field_label - the label text for the form field
     *   $post_post_type - the post type of the post we're creating the field for
     *   $item_post_type - the post type of the things to appear in the list
     *   $additional_query_params - any additional query params for generating the list
     *
     * Returns the id of the created instance as passed in
     */
    public static function create( $field_id, $meta_key, $form_field_name, $form_field_label, $post_post_type='post', $item_post_type='post', $additional_query_params=array() ) {
        $new_instance = new S2PS_Post_Select_Instance($field_id, $meta_key, $form_field_name, $form_field_label, $post_post_type, $item_post_type, $additional_query_params);
        self::$instances[$field_id] = $new_instance;

        return $field_id;
    }

    public static function display( $field_id ) {
        self::$instances[$field_id]->display();
    }

    public static function do_saves( ) {
        foreach (self::$instances as $this_instance) {
            $this_instance->save();
        }
    }
}

?>