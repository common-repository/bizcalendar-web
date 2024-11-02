<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once(__DIR__ . '/wp-check-fix.php');

function setrio_bizcal_is_valid_json($text)
{
    if (strlen($text) == 0)
        return false;
    if ($text[0] != '{')
        return false;
    $result = json_decode($text);
    if ($result === FALSE)
        return false;
    else
        return true;
}

function setrio_bizcal_parse_service_exception($text)
{
    $start_pos = strpos($text, '<p>The server encountered an error');
    if ($start_pos !== FALSE)
    {
        $end_pos = strpos($text, '</p>', $start_pos);
        if ($end_pos !== FALSE)
        {
            return wp_kses(trim(str_replace('The exception stack trace is:', '', substr($text, $start_pos + 3, $end_pos - $start_pos + 7))));
        }
        else
            return wp_kses(substr($text, $start_pos + 3));
    }
    else
        return wp_kses($text);
    
}

function setrio_bizcal_set_email_content_type(){
    return "text/html";
}

?>