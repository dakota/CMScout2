<?php
/**************************************************************************
    FILENAME        :   admin_users.php
    PURPOSE OF FILE :   Displays users
    LAST UPDATED    :   02 October 2006
    COPYRIGHT       :   © 2005 CMScout Group
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
	$module['User and Profile Management']['Purge Users'] = "purge";
    $moduledetails[$modulenumbers]['name'] = "Purge Users";
    $moduledetails[$modulenumbers]['details'] = "Purge users who have not been active for a certain amount of time";
    $moduledetails[$modulenumbers]['access'] = "Allowed to access the user purger";
    $moduledetails[$modulenumbers]['add'] = "notused";
    $moduledetails[$modulenumbers]['edit'] = "notused";
    $moduledetails[$modulenumbers]['delete'] = "Allowed to purge users";
    $moduledetails[$modulenumbers]['publish'] = "notused";
    $moduledetails[$modulenumbers]['limit'] = "notused";
    $moduledetails[$modulenumbers]['id'] = "purge";
	return;
}
else
{
    if(isset($_POST['Submit']) && $_POST['Submit'] == "Purge users")
    {
        $date = safesql(strtotime($_POST['purge']), "int");
        
        $sql = $data->select_query("users", "WHERE lastlogin <= $date");
        
        $i=0;
        while ($temp = $data->fetch_array($sql))
        {
            $id = safesql($temp['id'], "int");
            $username = $temp['uname'];
            $data->delete_query("auth", "authname=$id AND type=1");
            $data->delete_query("forumnew", "uid=$id");
            $data->delete_query("forummods", "mid=$id AND type=0");
            $data->delete_query("owners", "owner_id=$id AND owner_type=0");
            $data->delete_query("pms", "touser=$id AND type=1");
            $data->delete_query("pms", "touser=$id AND type=3");
            $data->delete_query("pms", "fromuser=$id AND type=2");
            $data->delete_query("pms", "fromuser=$id AND type=4");
            $data->delete_query("rssfeeds", "userid=$id");
            $data->delete_query("usergroups", "userid=$id");
            $data->delete_query("users", "id=$id");
            $i++;
        }
        show_admin_message("$i users deleted", "$pagename");
    }
	elseif(isset($_POST['Submit']) && $_POST['Submit'] == "Show users") {
		$date = safesql(strtotime($_POST['purge']), "int");
        
        $sql = $data->select_query("users", "WHERE lastlogin <= $date");
		
		$users = array();
		while($users[] = $data->fetch_array($sql)){};
		
		$tpl->assign('users', $users);
		$tpl->assign('userCount', $data->num_rows($sql));
		$tpl->assign('purgeDate', $_POST['purge']);
	}
 
    $filetouse = "admin_purge.tpl";
}
?>