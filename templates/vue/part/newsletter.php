<?php
$enableNewsletter = get_option('setrio_bizcal_enable_newsletter', 1);
$key = basename(__FILE__, '.php');
$args['key'] = $key;

if($enableNewsletter){
	$lblNewsletter = setrio_bizcal_message('lblNewsletter');
		
	$lblNewsletter = preg_replace("/(<a)(\s)/",'\1 v-on:click.stop="1" \2', $lblNewsletter);
	
	$args['wargs'] = array_replace((isset($args['wargs']) ? $args['wargs'] : array()), array(
		'label' => $lblNewsletter,
	));
	setrio_bizcal_get_template_part('vue/part/checkbox', $args);
} else {
	setrio_bizcal_get_template_part('vue/part/input', $args);
}