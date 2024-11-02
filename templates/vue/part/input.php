<?php 
$a = shortcode_atts(array_replace_recursive(array(
	'class' => '',
	'atts' => '',
	'type' => 'input',
	'hide-details' =>"'auto'",
	'model_suffix' => '',
	'texts' => array(
		'required' => setrio_bizcal_message('lblFieldMissing'),
	),
	'rules' => "",
), (isset($args['wargs']) ? $args['wargs'] : array())), (isset($args['args']) ? $args['args'] : array()), (isset($args['tag']) ? $args['tag'] : ''));
$key = $args['key'];
?>
<v-input
	ref="<?php echo esc_attr($key); ?>" 
	<?php echo esc_attr($a['atts']); ?>
	v-model="<?php echo esc_attr($key); ?><?php echo esc_attr($a['model_suffix']); ?>"
	class="<?php echo esc_attr($a['class']); ?>"
	<?php if ($a['rules']) { ?>:rules="<?php echo esc_attr($a['rules']); ?>"<?php } ?>
	:hide-details="<?php echo esc_attr($a['hide-details']); ?>"
></v-input>
<component is="script">SetrioBizCalendarVueApps[{{ app_index }}].setData('texts_<?php echo esc_attr($key); ?>',<?php BizCalendar\wp_kses_post(htmlspecialchars(json_encode(array('texts' => array($key => $a['texts']))), ENT_QUOTES, 'UTF-8')); ?>)</component>