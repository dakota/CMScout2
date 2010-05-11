<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {eval} function plugin
 *
 * Type:     function<br>
 * Name:     eval<br>
 * Purpose:  evaluate a template variable as a template<br>
 * @link http://smarty.php.net/manual/en/language.function.eval.php {eval}
 *       (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 * @param array
 * @param Smarty
 */
function smarty_function_content($params, &$smarty)
{
	$bla = '';
	
	require_once('function.eval.php');
	
	return smarty_function_eval(array('var' => get_spec_by_name($params['page'])), $smarty);
}

/* vim: set expandtab: */

?>
