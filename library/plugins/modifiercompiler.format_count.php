<?php
/**
 * Get formated text for count
 */
function format_count($params, $compiler)
{
    return number_format($params[0]) . "单";
}
?>