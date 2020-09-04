<?php
/**
 * Get formated text for hour
 */
function format_hour($params, $compiler)
{
    return number_format($params[0], 1) . "小时";
}
?>