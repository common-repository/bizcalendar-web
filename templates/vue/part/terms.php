<?php
$enableTerms = get_option('setrio_bizcal_enable_terms', 1);
$key = basename(__FILE__, '.php');
$args['key'] = $key;

if($enableTerms){
	$termsPostId = get_option('setrio_bizcal_terms_post_id', get_option('wp_page_for_privacy_policy'));
	$link = get_option('setrio_bizcal_terms_link');
	if ($termsPostId && ('' === '' . $link)) {
		$link = get_the_permalink($termsPostId);
	}
	$title = setrio_bizcal_message('lblTermsText');
	if (trim($title) === '' && $termsPostId) {
		$title = get_the_title($termsPostId);
	}
	$lblTerms = setrio_bizcal_replace_tags_sprintf(setrio_bizcal_message('lblTerms'), array(
		'link' => $link,
		'titlu' => $title,
	));

	if (setrio_bizcal_message('lblTerms') === $lblTerms) {

		if ('' !== trim($link)) {
			$lblTerms .= ' <a target="_BLANK" href="' . esc_attr($link) . '">';
			$lblTerms .= $title;
			$lblTerms .= '</a>';
		}
	}
	$lblTerms = preg_replace("/(<a)(\s)/",'\1 v-on:click.stop="1" \2', $lblTerms);

	$args['wargs'] = array_replace((isset($args['wargs']) ? $args['wargs'] : array()), array(
		'label' => $lblTerms,
		'texts' => array(
			'required' => setrio_bizcal_message('lblTermsNotAgreed'),
		),
		'rules' => "[v => (!!v) || objVal(\$data,'texts." . $key . ".required') || '']",
	));
	setrio_bizcal_get_template_part('vue/part/checkbox', $args);
} else {
	setrio_bizcal_get_template_part('vue/part/input', $args);
}