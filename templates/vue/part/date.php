<?php
$key = basename(__FILE__, '.php');
if(!empty($args['args']['type']) && $args['args']['type'] == 'input'){
	$args['args']['model_suffix'] = '_value';
	setrio_bizcal_get_template_part('vue/part/input', $args);
	return;
}
$a = shortcode_atts(array_replace_recursive(array(
	'label' => setrio_bizcal_message('lblAppointmentDate'),
	'atts' => 'full-width',
	'calendar' => !empty($args['rargs']) && !empty($args['rargs']['calendar']) ? $args['rargs']['calendar'] : 'inline',
	'first-day-of-week' => '1',
	'class' => 'mb-5',
	'locale' => 'ro',
	'type' => 'date',
	'hide-details' =>"'auto'",
	'texts' => array(
		'required' => setrio_bizcal_message('lblAppointmentDate'),
	),
	'rules' =>  "[v => (!!v && !!v.trim().length) || objVal(\$data,'texts." . $key . ".required') || '']",
), (isset($args['wargs']) ? $args['wargs'] : array())), (isset($args['args']) ? $args['args'] : array()), (isset($args['tag']) ? $args['tag'] : ''));
if($a['calendar'] === 'inline') {
?>
<v-date-picker 
	ref="<?php echo esc_attr($key); ?>_picker" 
	<?php echo esc_attr($a['atts']); ?>
	first-day-of-week="<?php echo esc_attr($a['first-day-of-week']); ?>" 
	locale="<?php echo esc_attr($a['locale']); ?>"
	v-model="<?php echo esc_attr($key); ?>_value" 
	class="<?php echo esc_attr($a['class']); ?>"
	:class="loading_ajax ? 'loading_ajax' : ''" 
	:events="Object.keys(datepicker_events)"
	:event-color="function(date){return datepicker_events[date].color}"
	:days="Object.keys(datepicker_days)"
	:day-color="function(date){return datepicker_days[date].color}"
	:loading="<?php echo esc_attr($key); ?>_lock"
	:min="$data['min_date']"
	v-bind="props('date-picker')"
	v-bind:color="datepicker_color"
>
</v-date-picker>
<v-input
	<?php if ($a['rules']) { ?>:rules="<?php echo esc_attr($a['rules']); ?>"<?php } ?>
	ref="<?php echo esc_attr($key); ?>" 
	v-model="<?php echo esc_attr($key); ?>_value"
	:hide-details="<?php echo esc_attr($a['hide-details']); ?>"
></v-input>
<?php } else {?>
<v-menu
	:init="$data.date_menu = false"
	v-model="$data.date_menu"
	:close-on-content-click="false"
	:nudge-right="40"
	transition="scale-transition"
	offset-y
	min-width="auto"
	v-bind="props('menu')"
>
<template v-slot:activator="{ on, attrs }">
  <v-text-field
	ref="<?php echo esc_attr($key); ?>" 
	v-model="<?php echo esc_attr($key); ?>_value"
	label="<?php echo esc_attr($a['label']); ?>"
	class="<?php echo esc_attr($a['class']); ?>"
	:class="loading_ajax ? 'loading_ajax' : ''" 
	prepend-icon="mdi-calendar"
	:hide-details="<?php echo esc_attr($a['hide-details']); ?>"
	:loading="<?php echo esc_attr($key); ?>_lock"
	readonly
	v-bind:class="datepicker_color + '--text'"
	v-bind:color="datepicker_color"
	v-bind="props('text-field')"
	v-on="on"
  ></v-text-field>
</template>
<v-date-picker
	ref="<?php echo esc_attr($key); ?>_picker" 
	v-model="<?php echo esc_attr($key); ?>_value"
	locale="<?php echo esc_attr($a['locale']); ?>"
	v-on:input="$data.date_menu = false"
	:min="$data['min_date']"
	<?php echo esc_attr($a['atts']); ?>
	first-day-of-week="<?php echo esc_attr($a['first-day-of-week']); ?>" 
	v-bind="props('date-picker')"
	v-bind:color="datepicker_color"
></v-date-picker>
</v-menu>
<?php }?>
<component is="script">SetrioBizCalendarVueApps[{{ app_index }}].setData('texts_<?php echo esc_attr($key); ?>',<?php BizCalendar\wp_kses_post(htmlspecialchars(json_encode(array('texts' => array($key => $a['texts']))), ENT_QUOTES, 'UTF-8')); ?>)</component>