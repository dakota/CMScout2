<?php
/**************************************************************************
    FILENAME        :   admin_frontpage.php
    PURPOSE OF FILE :   Manage frontpage items
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
	$module['User and Profile Management']['Custom Profile Manager'] = "customprofile";
    $moduledetails[$modulenumbers]['name'] = "Custom Profile Manager";
    $moduledetails[$modulenumbers]['details'] = "Management of custom profile fields";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the custom profile manager";
    $moduledetails[$modulenumbers]['add'] = "Allowed to add a field";
    $moduledetails[$modulenumbers]['edit'] = "Allowed to modify a field";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to delete a field";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";   
    $moduledetails[$modulenumbers]['id'] = "customprofile";

	return;
}
else
{
    $action = $_GET['action'];
    $id = $_GET['id'];
    $safe_id = safesql($id, "int");
    if ($_POST['Submit'] == "Submit")
    {
        $name = safesql(str_replace(" ", "", $_POST['name']), "text");
        if (check_duplicate("profilefields", "name", $name, $safe_id))
        {
            show_admin_message("A field with that name already exists");
        }
        $query = safesql($_POST['query'], "text");
        $hint = safesql($_POST['hint'], "text");
        $required = safesql($_POST['required'], "int");
        $register = safesql($_POST['register'], "int");
        $profileview = safesql($_POST['profileview'], "int");
        $type = safesql($_POST['type'], "int");
        
        switch ($_POST['type'])
        {
            case 1:
                    $options = $_POST['options'];
                    break;
            case 2:
                    $options = $_POST['options'];
                    break;
            case 3: case 4: case 5:
                    $options = array();
                    $options[0] = $_POST['numoptions'];
                    for ($i=1;$i<=$_POST['numoptions'];$i++)
                    {
                        $temp = $_POST['option' . $i];
                        if ($temp != '')
                        {
                            $options[] = $temp;
                        }
                        else
                        {
                            --$options[0];
                        }
                    }
                    break;
            case 6:
                $options = "''";
        }
        
        $pos = nextPosition('profilefields', 'pos', 'place = 0');
        $options = safesql(serialize($options), "text");
        if ($action == "new")
        {
            $data->insert_query("profilefields", "'', $name, $query, $options, $hint, $type, $required, $register, $profileview, $pos, 0, 0");
            show_admin_message("Field Added", $pagename);
        }
        elseif ($action == "edit")
        {
            $data->update_query("profilefields", "query=$query, options=$options, hint=$hint, type=$type, required=$required, register=$register, profileview=$profileview", "id=$id");
            show_admin_message("Field Updated", $pagename);
        }
    }
    
    if ($action == "")
    {
        $sql = $data->select_query("profilefields", "WHERE place=0 ORDER BY pos ASC");
        
        $numfields = $data->num_rows($sql);
        $field = array();
        while ($field[] = $data->fetch_array($sql))
        $tpl->assign("numfields", $numfields);
        $tpl->assign("field", $field);
    }
    elseif($action == "moveup" && pageauth("customprofile", "edit") == 1)
    {
		moveItem('profilefields', $id, 'up', 'place');
		show_admin_message("Item moved", "$pagename");
    }
    elseif($action == "movedown" && pageauth("customprofile", "edit") == 1)
    {
		moveItem('profilefields', $id, 'down', 'place');
		show_admin_message("Item moved", "$pagename");
    }
    elseif ($action == "edit" && pageauth("customprofile", "edit") == 1)
    {
        $item = $data->select_fetch_one_row("profilefields", "WHERE id=$safe_id");
        
        $item['options'] = unserialize($item['options']);
        
        $tpl->assign("item", $item);
    }
    elseif ($action=="delete" && pageauth("customprofile", "delete") == 1) 
    {
        $delete = $data->delete_query("profilefields", "id=$safe_id");
        if ($delete)
        {   
            show_admin_message("Field deleted", "$pagename");  
        }  
        $action = "";
    }
    $tpl->assign("action", $action);
    $filetouse = "admin_customprofile.tpl";
}
?>