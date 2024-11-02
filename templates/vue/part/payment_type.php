<?php
$key = basename(__FILE__, '.php');
$args['key'] = $key;
$args['wargs'] = array_replace((isset($args['wargs']) ? $args['wargs'] : array()), array(
	'label' => setrio_bizcal_get_arr_val($args,'rargs.txt_payment_type',setrio_bizcal_message('lblPaymentType')),
	'placeholder' => setrio_bizcal_get_arr_val($args,'rargs.txt_payment_type_placeholder',setrio_bizcal_message('lblPaymentTypePlaceholder')),
	'text_no_items' => setrio_bizcal_get_arr_val($args,'rargs.txt_no_payment_types',setrio_bizcal_message('txtNoPaymentMethods')),
	'texts' => array(
		'required' => setrio_bizcal_get_arr_val($args,'rargs.txt_payment_type_placeholder',setrio_bizcal_message('lblPaymentTypePlaceholder')),
	),
	'rules' => "[v => (!!\$data['notify_only'] || !!v) || objVal(\$data,'texts." . $key . ".required') || '']",
));
setrio_bizcal_get_template_part('vue/part/list', $args);