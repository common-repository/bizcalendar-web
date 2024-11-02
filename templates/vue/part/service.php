<?php
$key = basename(__FILE__, '.php');
$args['key'] = $key;
$args['wargs'] = array_replace((isset($args['wargs']) ? $args['wargs'] : array()), array(
	'label' => setrio_bizcal_get_arr_val($args,'rargs.txt_service',setrio_bizcal_message('lblMedicalService')),
	'placeholder' => setrio_bizcal_get_arr_val($args,'rargs.txt_service_placeholder',setrio_bizcal_message('lblMedicalServicePlaceholder')),
	'text_no_items' => setrio_bizcal_get_arr_val($args,'rargs.txt_no_services',setrio_bizcal_message('txtNoServices')),
	'texts' => array(
		'required' => setrio_bizcal_get_arr_val($args,'rargs.txt_service_placeholder',setrio_bizcal_message('lblMedicalServicePlaceholder')),
	),
	'rules' => "[v => (!!\$data['notify_only'] || !!v) || payment_type_value==2 || objVal(\$data,'texts." . $key . ".required') || '']",
));
setrio_bizcal_get_template_part('vue/part/list', $args);