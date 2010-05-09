<?php
/**************************************************************************
    FILENAME        :   admin_photo.php
    PURPOSE OF FILE :   Manages photos and photo albums
    LAST UPDATED    :   25 May 2006
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

if( !empty($getmodules) )
{
	$module['Content Management']['News Category Manager'] = "news_categories";
    $moduledetails[$modulenumbers]['name'] = "News Category Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages news categories";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the news category manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a new category";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit a category";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete categories";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "news_categories";

	return;
}
else
{
	$action = $_GET['action'];
	$id = safesql($_GET['id'], 'int');
	$cid = safesql($_GET['cid'], 'int');

	if ($action == "new")
	{
		$tpl->assign("editor", true);
		if ($_POST['submit'] == "Add Category")
		{
			$name = safesql($_POST['name'], "text");
			$description = safesql($_POST['editor'], "text", false);
			$data->insert_query("news_categories", "'', $name, $description");
			show_admin_message("Category added", "$pagename");
		}
	}
	elseif($action == "edit")
	{
        $Show = $data->select_query("news_categories", "WHERE id='$id'");
        $category = $data->fetch_array($Show);
        $tpl->assign('category', $category);
		$tpl->assign("editor", true);

		if ($_POST['submit'] == "Save Category")
		{
			$name = safesql($_POST['name'], "text");
			$description = safesql($_POST['editor'], "text", false);
			$Update = $data->update_query("news_categories", "name=$name, description=$description", "id='$id'");
			show_admin_message("Category updated", "$pagename");
		}
	}
	elseif ($action == 'delete')
	{
		$Delete = $data->update_query("newscontent", "category_id=0", "category_id='$id'");
		$data->delete_query('news_categories', 'id='.$id);
		show_admin_message("Category deleted", "$pagename");
	}
	elseif ($action == "")
	{
		$result = $data->select_query("news_categories", "ORDER BY name ASC");

		$categories = array();
		$numcategories = $data->num_rows($result);
		while ($categories[] = $data->fetch_array($result));

		$tpl->assign('categories', $categories);
		$tpl->assign('numcategories', $numcategories);
	}
	$tpl->assign('action', $action);
	$filetouse = 'admin_news_categories.tpl';
}
?>
