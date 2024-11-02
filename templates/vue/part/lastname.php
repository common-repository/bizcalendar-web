<?php
$key = basename(__FILE__, '.php');
$args['key'] = $key;
$args['wargs'] = array_replace((isset($args['wargs']) ? $args['wargs'] : array()), array(
	'label' => setrio_bizcal_message('lblPatientLastName'),
	'texts' => array(
		'required' => setrio_bizcal_message('lblFieldMissing') . ' ' . setrio_bizcal_message('lblLastNameField'),
	),
	'rules' => "[v => (!!v && !!v.trim().length) || objVal(\$data,'texts." . $key . ".required') || '']",
));
setrio_bizcal_get_template_part('vue/part/text', $args);