<?php
$key = basename(__FILE__, '.php');
$args['key'] = $key;
$args['wargs'] = array_replace((isset($args['wargs']) ? $args['wargs'] : array()), array(
	'label' => setrio_bizcal_message('lblPatientEmail'),
	'texts' => array(
		'invalid' => setrio_bizcal_message('lblEmailFieldNotValid'),
	),
	'rules' => "[
		v => !v || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*\.[a-zA-Z]{2,}$/.test(v) || objVal(\$data,'texts." . $key . ".invalid') || '',
	]",
));
setrio_bizcal_get_template_part('vue/part/text', $args);