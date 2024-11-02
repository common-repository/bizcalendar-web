<?php
$enableDataPolicy = get_option('setrio_bizcal_enable_data_policy', 1);
$key = basename(__FILE__, '.php');
$args['key'] = $key;

if($enableDataPolicy){
	$dataPolicyPostId = get_option('setrio_bizcal_data_policy_post_id');
	$link = get_option('setrio_bizcal_data_policy_link');
	if ($dataPolicyPostId && ('' === '' . $link)) {
		$link = get_the_permalink($dataPolicyPostId);
	}
	$title = setrio_bizcal_message('lblDataPolicyText');
	if (trim($title) === '' && $dataPolicyPostId) {
		$title = get_the_title($dataPolicyPostId);
	}
	$lblDataPolicy = setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('lblDataPolicy'), array(
		'link' => $link,
		'titlu' => $title,
	));

	if (setrio_bizcal_message('lblDataPolicy') === $lblDataPolicy) {
		if ('' !== trim($link)) {
			$lblDataPolicy .= ' <a target="_BLANK" href="' . esc_attr($link) . '">';
			$lblDataPolicy .= $title;
			$lblDataPolicy .= '</a>';
		}
	}
	$lblDataPolicy = preg_replace("/(<a)(\s)/",'\1 v-on:click.stop="1" \2', $lblDataPolicy);
	
	$args['wargs'] = array_replace((isset($args['wargs']) ? $args['wargs'] : array()), array(
		'label' => $lblDataPolicy,
		'texts' => array(
			'required' => setrio_bizcal_message('lblDataPolicyNotAgreed'),
		),
		'rules' => "[v => (!!v) || objVal(\$data,'texts." . $key . ".required') || '']",
	));
	setrio_bizcal_get_template_part('vue/part/checkbox', $args);
} else {
	setrio_bizcal_get_template_part('vue/part/input', $args);
}