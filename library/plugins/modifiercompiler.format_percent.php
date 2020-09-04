<?php
/**
 * Get formated text for percent
 */
function format_percent($params, $compiler)
{
    if ($params[0] === false) {
        return "-";
    }
    return number_format($params[0] * 100, 1) . "%";
}
?>