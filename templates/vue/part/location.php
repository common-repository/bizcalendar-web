<?php
$enable_multiple_locations = (bool)get_option('setrio_bizcal_enable_multiple_locations', false);
if(!$enable_multiple_locations) return;

$key = basename(__FILE__, '.php');
$args['key'] = $key;
$args['wargs'] = array_replace((isset($args['wargs']) ? $args['wargs'] : array()), array(
	'label' => setrio_bizcal_get_arr_val($args,'rargs.txt_location',setrio_bizcal_message('lblLocation')),
	'placeholder' => setrio_bizcal_get_arr_val($args,'rargs.txt_location_placeholder',setrio_bizcal_message('lblLocationPlaceholder')),
	'text_no_items' => setrio_bizcal_get_arr_val($args,'rargs.txt_no_locations',setrio_bizcal_message('txtNoLocations')),
	'texts' => array(
		'required' => setrio_bizcal_get_arr_val($args,'rargs.txt_location_placeholder',setrio_bizcal_message('lblLocationPlaceholder')),
	),
	'rules' => "[v => (!!\$data['notify_only'] || null !== v) || objVal(\$data,'texts." . $key . ".required') || '']",
));
setrio_bizcal_get_template_part('vue/part/list', $args);