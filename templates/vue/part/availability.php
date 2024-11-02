<?php setrio_bizcal_get_template_part('vue/part/availability-loader', array()); ?>
<?php 
$key = basename(__FILE__, '.php');
$args['key'] = $key;
$args['wargs'] = array_replace((isset($args['wargs']) ? $args['wargs'] : array()), array(
	'model_suffix' => '_value',
	'hide-details' => "'always'",
	'texts' => array(
		'required' => setrio_bizcal_message('msgErrAppointmentTimeMissing'),
	),
	'rules' => "[v => (!!\$data['notify_only'] || !!v && !!v.trim().length) || objVal(\$data,'texts." . $key . ".required') || '']",
)); ?>
<?php setrio_bizcal_get_template_part('vue/part/input', $args); ?>
<?php setrio_bizcal_get_template_part('vue/part/availability-alert-no-intervals-inline', $args); ?>
<?php // setrio_bizcal_get_template_part('vue/part/availability-dialog-no-intervals', array()); ?>
<?php // setrio_bizcal_get_template_part('vue/part/availability-dialog-recommended-intervals', array()); ?>
<?php setrio_bizcal_get_template_part('vue/part/availability-panels', $args); ?>