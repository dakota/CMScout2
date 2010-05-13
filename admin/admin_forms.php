<?php
/**************************************************************************
    FILENAME        :   admin_forms.php
    PURPOSE OF FILE :   Manages forms
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
	$module['Content Management']['Form Manager'] = "forms";
    $moduledetails[$modulenumbers]['name'] = "Form Manager";
    $moduledetails[$modulenumbers]['details'] = "Manages forms";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the form manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a new form";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to edit a form";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete a form";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "forms";

	return;
}
else
{
	$subpage = $_GET['subpage'] != '' ? $_GET['subpage'] : '';

    if (!$subpage)
    {
		$action = $_GET['action'];
		$id = safesql($_GET['id'], 'int');

		if ($action == "new")
		{
			$numpages = 0;
			$page = $data->select_fetch_all_rows($numpages, 'static_content', 'WHERE trash=0 AND type=0 ORDER BY friendly');
		
			$tpl->assign('numpages', $numpages);
			$tpl->assign('page', $page);
			
			if ($_POST['submit'] == "Add Form")
			{
				$name = safesql($_POST['name'], "text");
				$email_address = safesql($_POST['email_address'], "text");
				$static_content_id = safesql($_POST['static_content_id'], "int");
				$id = $data->insert_query("forms", "'', $name, $static_content_id, $email_address");
		
				show_admin_message("Form added", "$pagename&subpage=form_fields&fid=".$id);
			}
		}
		elseif($action == "edit")
		{
			$Show = $data->select_query("forms", "WHERE id='$id'");
			$form = $data->fetch_array($Show);
			$tpl->assign('form', $form);

			$numpages = 0;
			$page = $data->select_fetch_all_rows($numpages, 'static_content', 'WHERE trash=0 AND type=0 ORDER BY friendly');
		
			$tpl->assign('numpages', $numpages);
			$tpl->assign('page', $page);			
			

			if ($_POST['submit'] == "Save Form")
			{
				$name = safesql($_POST['name'], "text");
				$email_address = safesql($_POST['email_address'], "text");
				$static_content_id = safesql($_POST['static_content_id'], "int");

				$Update = $data->update_query("forms", "name=$name, email_address=$email_address, static_content_id=$static_content_id", "id='$id'");
				show_admin_message("Form updated", "$pagename");
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
			$result = $data->select_query("forms", "ORDER BY name ASC");

			$forms = array();
			$numforms = $data->num_rows($result);
			while ($forms[] = $data->fetch_array($result));

			$tpl->assign('forms', $forms);
			$tpl->assign('numforms', $numforms);
		}
		$tpl->assign('action', $action);
		$filetouse = 'admin_forms.tpl';
    }
    else
    {
        $allowed = array('form_fields'=>true,
                         'form_data'=>true,);
        
        if (array_key_exists($subpage, $allowed))
        {
            include("admin/admin_$subpage.php");
        }
        else
        {
            include("admin/admin_main.php");
        }
    }		
}
?>
