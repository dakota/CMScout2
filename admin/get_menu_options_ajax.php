<?php
/**************************************************************************
    FILENAME        :   activate.php
    PURPOSE OF FILE :   Activates accounts
    LAST UPDATED    :   09 May 2006
    COPYRIGHT       :   � 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
if (!isset($included)) {
	$bit = "../";
	require_once ("{$bit}includes/error_handling.php");
	set_error_handler('ErrorHandler');
	error_reporting(E_ERROR|E_PARSE);
	$upgrader = false;
	$limitedStartup = true;
	require_once("{$bit}common.php");

	foreach ($_GET as $value => $throwAway)
	{
	  break;
	}
	$item = explode("_", $value);
	$item[0] = safesql($item[0], "int");
	echo getOptions($item[0], $item[1]);
}

function getOptions($itemId, $itemType, $selectedOption = 0) {
	global $data;

	$output = '';
	switch ($itemType)
	{
		case "dynamic":
		case "dyn":
		case "box":
		  $itemDetails = $data->select_fetch_one_row("functions", "WHERE id={$itemId}");
		  $itemOptions = explode(",", $itemDetails['options']);
		  if ($itemOptions[0] != "")
		  {
			$itemSectionSql = $data->select_query($itemOptions[0], "ORDER BY {$itemOptions[2]}", "{$itemOptions[1]},{$itemOptions[2]}");
			$output .= "<label for=\"options\" class=\"label\">{$itemOptions[4]}</label>
			   <div class=\"inputboxwrapper\">
			   <select name=\"options\" id=\"options\" class=\"inputbox\">
			   <option value=\"0\" ".($selectedOption==0?'selected="selected"':'').">{$itemOptions[3]}</option>";
			   while ($temp = $data->fetch_array($itemSectionSql))
			   {
				 $output .= "<option value=\"{$temp[$itemOptions[1]]}\" ".($selectedOption==$temp[$itemOptions[1]]?'selected="selected"':'').">{$temp[$itemOptions[2]]}</option>";
			   }
			$output .= "</select>
			</div><br />";
		  }
		  break;
		case "sub":
		  $itemSectionSql = $data->select_query("static_content", "WHERE type=2 AND pid = {$itemId} ORDER BY friendly", "id,friendly");
		  $output .= "<label for=\"options\" class=\"label\">Page</label>
			 <div class=\"inputboxwrapper\">
			 <select name=\"options\" id=\"options\" class=\"inputbox\">
			 <option value=\"0\" ".($selectedOption==0?'selected="selected"':'').">Site home page</option>";
			 while ($temp = $data->fetch_array($itemSectionSql))
			 {
			   $output .= "<option value=\"{$temp['id']}\" ".($selectedOption==$temp['id']?'selected="selected"':'').">{$temp['friendly']}</option>";
			 }
		  $output .= "</select>
		  </div><br />";
		  break;
		case "group":
		  $itemSectionSql = $data->select_query("static_content", "WHERE type=1 AND pid = {$itemId} ORDER BY friendly", "id,friendly");
		  $output .= "<label for=\"options\" class=\"label\">Page</label>
			 <div class=\"inputboxwrapper\">
			 <select name=\"options\" id=\"options\" class=\"inputbox\">
			 <option value=\"0\" ".($selectedOption==0?'selected="selected"':'').">Site home page</option>";
			 while ($temp = $data->fetch_array($itemSectionSql))
			 {
			   $output .= "<option value=\"{$temp['id']}\" ".($selectedOption==$temp['id']?'selected="selected"':'').">{$temp['friendly']}</option>";
			 }
		  $output .= "</select>
		  </div><br />";
		  break;
		case "art":
		case "static":
		case "stat":
		  break;
	}

	return $output;
}

?>