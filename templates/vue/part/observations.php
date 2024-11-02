<?php 
$key = basename(__FILE__, '.php');
$a = shortcode_atts(array_replace_recursive(array(
	'label' => setrio_bizcal_message('lblPatientObservations'),
	'atts' => 'filled',
	'class' => '',
	'type' => 'textarea',
	'hide-details' => 'auto',
	'texts' => array(
	),
	'rules' => "",
), (isset($args['wargs']) ? $args['wargs'] : array())), (isset($args['args']) ? $args['args'] : array()), (isset($args['tag']) ? $args['tag'] : ''));
?>
<v-textarea
	ref="<?php echo esc_attr($key); ?>" 
	<?php echo esc_attr($a['atts']); ?>
	v-model="<?php echo esc_attr($key); ?>"
	label="<?php echo esc_attr($a['label']); ?>"
	<?php if ($a['rules']) { ?>:rules="<?php echo esc_attr($a['rules']); ?>"<?php } ?>
	hide-details="<?php echo esc_attr($a['hide-details']); ?>"
	v-bind="props('textarea')"
></v-textarea>
<component is="script">SetrioBizCalendarVueApps[{{ app_index }}].setData('texts_<?php echo esc_attr($key); ?>',<?php BizCalendar\wp_kses_post(htmlspecialchars(json_encode(array('texts' => array($key => $a['texts']))), ENT_QUOTES, 'UTF-8')); ?>)</component>