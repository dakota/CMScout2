<?php
function smarty_function_show_recaptcha($params, &$smarty)
{
	global $config;

	require_once dirname(__FILE__) . '/../recaptchalib.php';
	$publickey = $config['recaptcha_public_key']; // you got this from the signup page
	return recaptcha_get_html($publickey);
}
