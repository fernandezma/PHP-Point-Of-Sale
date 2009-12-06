<?php
function to_currency($number)
{
	$CI =& get_instance();
	setlocale(LC_MONETARY, $CI->config->item('currency_locale'));
	return money_format('%n', $number);
}
?>