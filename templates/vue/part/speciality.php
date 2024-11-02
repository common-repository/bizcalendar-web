<?php
$key = basename(__FILE__, '.php');
$args['key'] = $key;
$args['wargs'] = array_replace((isset($args['wargs']) ? $args['wargs'] : array()), array(
	'label' => setrio_bizcal_get_arr_val($args,'rargs.txt_speciality',setrio_bizcal_message('lblMedicalSpeciality')),
	'placeholder' => setrio_bizcal_get_arr_val($args,'rargs.txt_speciality_placeholder',setrio_bizcal_message('lblMedicalSpecialityPlaceholder')),
	'text_no_items' => setrio_bizcal_get_arr_val($args,'rargs.txt_no_specialities',setrio_bizcal_message('txtNoSpecialities')),
	'texts' => array(
		'required' => setrio_bizcal_get_arr_val($args,'rargs.txt_speciality_placeholder',setrio_bizcal_message('lblMedicalSpecialityPlaceholder')),
	),
	'rules' => "[v => (!!\$data['notify_only'] || !!v) || objVal(\$data,'texts." . $key . ".required') || '']",
));
setrio_bizcal_get_template_part('vue/part/list', $args);