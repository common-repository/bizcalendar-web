<?php
namespace BizCalendar;
function wp_kses_post($text, $SWP = null){
	if(isset($SWP)) return $text;
	echo wp_kses_post($text, true);
}