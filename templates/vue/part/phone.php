<?php
$key = basename(__FILE__, '.php');
$args['key'] = $key;
$args['wargs'] = array_replace((isset($args['wargs']) ? $args['wargs'] : array()), array(
	'label' => setrio_bizcal_message('lblPatientPhone'),
	'texts' => array(
		'required' => setrio_bizcal_message('lblFieldMissing') . ' ' . setrio_bizcal_message('lblPhoneField'),
		'invalid' => setrio_bizcal_message('lblPhoneFieldNotValid'),
		'min_max' => setrio_bizcal_message('lblPhoneFieldMinMax'),
	),
	'rules' => "[
		v => (!!v && !!v.trim().length) || objVal(\$data,'texts." . $key . ".required') || '',
		v => /^([0-9])+$/.test(v) || objVal(\$data,'texts." . $key . ".invalid') || '',
		v => /^([0-9]{10,15})+$/.test(v) || objVal(\$data,'texts." . $key . ".min_max') || '',
	]",
));
setrio_bizcal_get_template_part('vue/part/text', $args);