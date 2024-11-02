<?php
$key = basename(__FILE__, '.php');
$args['key'] = $key;
$args['wargs'] = array_replace((isset($args['wargs']) ? $args['wargs'] : array()), array(
	'label' => setrio_bizcal_get_arr_val($args,'rargs.txt_physician',setrio_bizcal_message('lblPhysician')),
	'placeholder' => setrio_bizcal_get_arr_val($args,'rargs.txt_physician_placeholder',setrio_bizcal_message('lblPhysicianPlaceholder')),
	'text_no_items' => setrio_bizcal_get_arr_val($args,'rargs.txt_no_physicians',setrio_bizcal_message('txtNoPhysicians')),
	'texts' => array(
		'required' => setrio_bizcal_get_arr_val($args,'rargs.txt_physician_placeholder',setrio_bizcal_message('lblPhysicianPlaceholder')),
	),
	'rules' => "[v => (!!\$data['notify_only'] || null !== v) || objVal(\$data,'texts." . $key . ".required') || '']",
));
setrio_bizcal_get_template_part('vue/part/list', $args);