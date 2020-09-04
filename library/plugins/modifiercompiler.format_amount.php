<?php
/**
 * Get formated text for amount
 */
function format_amount($params, $compiler)
{
    return number_format($params[0], 2) . "元";
}
?>