<?php
class SetrioBizCalendarVueForm
{
	static function speciality($args = array(), $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
	static function location($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
	static function payment_type($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
	static function service($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
	static function physician($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
    static function datepicker($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
    static function availability($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
    static function lastname($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
    static function firstname($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
    static function phone($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
    static function email($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
    static function observations($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
    static function recaptcha($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
    static function terms($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
    static function data_policy($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
    static function newsletter($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
    static function submit($args = null, $tag = ''){ 
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
    static function logo($args = null, $tag = ''){
		setrio_bizcal_get_template_part('vue/part/' . __FUNCTION__, array('wargs' => array(), 'args' => $args, 'tag' => $tag));
	}
	static function get($name, $args = null, $tag = ''){
		ob_start();
		self::$name($args);
		return ob_get_clean();
	}
}


class SetrioBizCalendarForm
{
    function ShowSpecialitiesCombo($label, $placeholder, $return_output = true)
    {
        /*$output = "\n\t\t\t<v-select v-model=\"selectedSpeciality\" :options=\"medicalSpecialities\" "
                  ." :searchable=\"false\" track-by=\"id\" label=\"text\" placeholder=\"$placeholder\" "
                  ." allowEmpty=\"false\" resetAfter=\"true\" :show-labels=\"false\"></v-select>";*/
                  
        $output = "<q-select v-model=\"selectedSpeciality\" :options=\"specialities\" label=\"$placeholder\" :dense=\"true\" "
                 ." hinttt=\"$placeholder\" ref=\"specialityCombo\"></q-select>";
                        
        if ($return_output)
            return $output;
        else
            BizCalendar\wp_kses_post($output);
    }
    
    function ShowLocationCombo($label, $placeholder, $return_output = true)
    {
        $output = "<q-select v-model=\"selectedLocation\" :options=\"locations\" label=\"$placeholder\" :dense=\"true\" "
                 ." hinttt=\"$placeholder\" ref=\"locationCombo\" :class=\"locationComboClass\"></q-select>";
                         
        if ($return_output)
            return $output;
        else
            BizCalendar\wp_kses_post($output);
    }
    
    function ShowPaymentTypeCombo($label, $placeholder, $return_output = true)
    {
        $output = "<q-select v-model=\"selectedPaymentType\" :options=\"paymentTypes\" label=\"$placeholder\" :dense=\"true\" "
                 ." hintttt=\"$placeholder\" ref=\"paymentTypeCombo\" :class=\"paymentTypeComboClass\"></q-select>";
                         
        if ($return_output)
            return $output;
        else
            BizCalendar\wp_kses_post($output);
    }
    
    function ShowServicesCombo($label, $placeholder, $return_output = true)
    {
        $output = "\n\t\t\t<q-select v-model=\"selectedService\" :options=\"services\" label=\"$placeholder\" :dense=\"true\" "
                 ." hintttt=\"$placeholder\" ref=\"servicesCombo\" :class=\"serviceComboClass\">"
                 /*."\n\t\t\t\t<template v-slot:append>"
                 ."\n\t\t\t\t\t<q-badge v-if=\"selectedService != null\">{{ selectedService.price }}</q-bagde>"
                 ."\n\t\t\t\t</template v-slot:append>"
                 ."\n\t\t\t\t<template v-slot:option=\"scope\">"
                 ."\n\t\t\t\t\t<q-item v-bind=\"scope.itemProps\" v-on=\"scope.itemEvents\">"
                 ."\n\t\t\t\t\t\t<q-item-section>"
                 ."\n\t\t\t\t\t\t\t<q-item-label v-html=\"scope.opt.label\"></q-item-label>"
                 ."\n\t\t\t\t\t\t\t<q-item-label caption>{{ scope.opt.price }}</q-item-label>"
                 ."\n\t\t\t\t\t\t</q-item-section>"
                 ."\n\t\t\t\t\t</q-item>"
                 ."\n\t\t\t\t</template>"*/
                 ."\n\t\t\t</q-select>";
                         
        if ($return_output)
            return $output;
        else
            BizCalendar\wp_kses_post($output);
    }
    
    function ShowPreferredPhysicianCombo($label, $placeholder, $return_output = true)
    {
        $output = "<q-select v-model=\"selectedPreferredPhysician\" :options=\"preferredPhysicians\" label=\"$placeholder\" :dense=\"true\" "
                 ." hintttt=\"$placeholder\" ref=\"preferredPhysiciansCombo\" :class=\"preferredPhysicianComboClass\"></q-select>";
                         
        if ($return_output)
            return $output;
        else
            BizCalendar\wp_kses_post($output);
    }

    function ShowCalendar($return_output = true)
    {
        $output = "<q-field label=\"Lorem ipsum:\" stack-label>"
                 ."<template v-slot:control>"
                 ."<q-date v-model=\"selectedAppointmentDate\" title=\"Alegeți data programării\" first-day-of-week=\"1\" localeee=\"ro\" minimal></q-date>"
                 ."</template>"
                 ."</q-field>";
        if ($return_output)
            return $output;
        else
            BizCalendar\wp_kses_post($output);
    }

    function ShowProgressIndicator($return_output = true)
    {
        $output = "<q-circular-progress indeterminate size=\"50px\" color=\"lime\" class=\"q-ma-md\" />";
        if ($return_output)
            return $output;
        else
            BizCalendar\wp_kses_post($output);
    }

    function ShowAvailableTimeSlots($return_output = true)
    {
        ob_start();
        ?>
        <q-item-section v-for="physician in availablePhysicians" :key="physician.uid">
            <q-item-label>
                Ganglia
            </q-item-label>
            <q-item-label caption>
                <q-badge color="yellow-6" text-color="black">
                    3
                    <q-icon name="warning" size="14px" class="q-ml-xs" />
                </q-badge>
            </q-item-label>
            <q-btn-toggle v-model="model" toggle-color="primary" :options="physician.availableTimeSlots">
                <template>
                    <li v-if="setrio_bizcal_ajax.show_physician_details" class='setrio-bizcal-available-physician'>
                        <div class='setrio-bizcal-available-physician-picture-container'>
                            <img v-if="option.picture_url.length > 0" class='setrio-bizcal-available-physician-picture' src='option.picture_url.length'
                                 width='100' height='100' align='top' />
                            <img v-else class='setrio-bizcal-available-physician-picture' src='setrio_bizcal_ajax.plugins_url + "/css/images/physician-icon.png"'
                                 width='100' height='100' align='top' />
                        </div>"
                        <div class='setrio-bizcal-available-physician-name'>option.physicianName</div>
                        <div v-if="option.price != null" class='setrio-bizcal-available-physician-price'><span  class='label'>Tarif: </span>" + option.price + "</div>
                        <div v-else-if="option.paymentType == 2" class='setrio-bizcal-available-physician-price'><span class='label'>Tarif: </span>DECONTAT CNAS</div>
                        <div v-else class='setrio-bizcal-available-physician-description'>" + option.description + "</div>
                    </li>
                    <li v-else class='setrio-bizcal-available-physician'>
                        <div class='setrio-bizcal-available-physician-name'>option.text</div>
                        <div v-if="option.price != null" class='setrio-bizcal-available-physician-price'><span  class='label'>Tarif: </span>" + option.price + "</div>
                        <div v-else-if="option.paymentType == 2" class='setrio-bizcal-available-physician-price'><span class='label'>Tarif: </span>DECONTAT CNAS</div>
                        <div v-else class='setrio-bizcal-available-physician-description'>" + option.description + "</div>
                    </li>
                </template>
            </q-btn-toggle>
        </q-item-section>
        <?php
        $output = ob_get_contents();
        ob_end_clean();

        if ($return_output)
            return $output;
        else
            BizCalendar\wp_kses_post($output);
    }
}

?>