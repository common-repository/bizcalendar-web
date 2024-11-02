<?php 
$a = shortcode_atts(array_replace_recursive(array(
	'label' => 'Necunoscut',
	'atts' => 'dense',
	'class' => '',
	'type' => 'text',
	'hide-details' =>"'auto'",
	'texts' => array(
		'required' => setrio_bizcal_message('lblFieldMissing'),
	),
	'rules' => "",
), (isset($args['wargs']) ? $args['wargs'] : array())), (isset($args['args']) ? $args['args'] : array()), (isset($args['tag']) ? $args['tag'] : ''));
$key = $args['key'];
?>
<v-checkbox
	v-bind="props('checkbox')"
	ref="<?php BizCalendar\wp_kses_post($key); ?>" 
	<?php BizCalendar\wp_kses_post(esc_attr($a['atts'])); ?>
	v-model="<?php BizCalendar\wp_kses_post($key); ?>"
	class="<?php BizCalendar\wp_kses_post(esc_attr($a['class'])); ?>"
	<?php if ($a['rules']) { ?>:rules="<?php BizCalendar\wp_kses_post(esc_attr($a['rules'])); ?>"<?php } ?>
	:hide-details="<?php BizCalendar\wp_kses_post(esc_attr($a['hide-details'])); ?>"
>
	<template v-slot:label>
		<span class="subtitle-2"><?php BizCalendar\wp_kses_post($a['label']); ?></span>
	</template>
</v-checkbox>
<component is="script">SetrioBizCalendarVueApps[{{ app_index }}].setData('texts_<?php BizCalendar\wp_kses_post($key); ?>',<?php BizCalendar\wp_kses_post(htmlspecialchars(json_encode(array('texts' => array($key => $a['texts']))), ENT_QUOTES, 'UTF-8')); ?>)</component>