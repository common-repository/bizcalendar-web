<?php 
global $setrio_bizcal_reCaptcha;
if(!$setrio_bizcal_reCaptcha){
	return;
}
$key = basename(__FILE__, '.php');
$a = shortcode_atts(array_replace_recursive(array(
	'label' => '',
	'atts' => 'filled',
	'sitekey' => get_option('setrio_bizcal_g_site_key', ''),
	'class' => '',
	'type' => 'recaptcha',
	'hide-details' =>"'auto'",
	'texts' => array(
		'invalid' => setrio_bizcal_message('lblReCaptchaFieldNotValid'),
	),
	'rules' => "[v => !v || objVal(\$data,'texts." . $key . ".invalid') || '']",
), (isset($args['wargs']) ? $args['wargs'] : array())), (isset($args['args']) ? $args['args'] : array()), (isset($args['tag']) ? $args['tag'] : ''));
?>
<v-recaptcha
	ref="<?php echo esc_attr($key); ?>" 
	<?php echo esc_attr($a['atts']); ?>
	v-model="<?php echo esc_attr($key); ?>"
	sitekey="<?php echo esc_attr($a['sitekey']); ?>"
	class="<?php echo esc_attr($a['class']); ?>"
	label="<?php echo esc_attr($a['label']); ?>"
	v-on:verify="recaptchaVerified"
	v-on:expired="recaptchaExpired"
	v-on:render="recaptchaRender"
	v-on:error="recaptchaError"
	v-bind="props('recaptcha')"
></v-recaptcha>
<v-input
	<?php if ($a['rules']) { ?>:rules="<?php echo esc_attr($a['rules']); ?>"<?php } ?>
	ref="<?php echo esc_attr($key); ?>_error" 
	v-model="<?php echo esc_attr($key); ?>_error"
	:hide-details="<?php echo esc_attr($a['hide-details']); ?>"
></v-input>
<component is="script">SetrioBizCalendarVueApps[{{ app_index }}].setData('texts_<?php echo esc_attr($key); ?>',<?php BizCalendar\wp_kses_post(htmlspecialchars(json_encode(array('texts' => array($key => $a['texts']))), ENT_QUOTES, 'UTF-8')); ?>)</component>