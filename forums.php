<?php
/**************************************************************************
    FILENAME        :   forums.php
    PURPOSE OF FILE :   Manages the forums
    LAST UPDATED    :   18 July 2005
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
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");

if (isset($_GET['action'])) $action = $_GET['action'];
$pagenum = 1;
$userauths = array();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING']))
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$f = isset($_GET['f']) && safesql($_GET['f'], "int");
$t = isset($_GET['t']) && safesql($_GET['t'], "int");
if (!empty($_GET['t']))
{
    $sql = $data->select_query("forumtopics", "WHERE id = $t");
    $temp = $data->fetch_array($sql);
    $f = $temp['forum'];
}
if (!empty($_GET['f']) || !empty($temp['forum']))
{
    $sql = $data->select_query("forumauths", "WHERE forum_id=$f");
    $auth = $data->fetch_array($sql);
}

if(!empty($_GET['f']) || !empty($_GET['t']))
{    
	$forumAuths = array(
		'new' => unserialize($auth['new_topic']),
		'reply' => unserialize($auth['reply_topic']),
		'edit' => unserialize($auth['edit_post']),
		'delete' => unserialize($auth['delete_post']),
		'view' => unserialize($auth['view_forum']),
		'read' => unserialize($auth['read_topics']),
		'sticky' => unserialize($auth['sticky']),
		'announce' => unserialize($auth['announce']),
		'poll' => unserialize($auth['poll']),
	);

	$userauths = array(
		'new' => 0,
		'reply' => 0,
		'edit' => 0,
		'delete' => 0,
		'view' => 0,
		'read' => 0,
		'sticky' => 0,
		'announce' => 0,
		'poll' => 0,
	);

    if ($check['id'] != "-1")
    {
        $usergroups = user_groups_id_array($check['id']);
    }
    else
    {
        $usergroups = array(0 => "-1");
    }
    
    $userauths['mod'] = $data->num_rows($data->select_query("forummods", "WHERE fid=$f AND mid={$check['id']} AND type=0")) ? 1 : 0;

    foreach($usergroups as $userGroup)
    {
		foreach($userauths as $key => $auth) {
			$forumAuth = isset($forumAuths[$key][$userGroup]) ? $forumAuths[$key][$userGroup] : 0;
			$userauths[$key] = $auth || $forumAuth;
		}
        
        if ($check['id'] != "-1")
        {
            $tempsql = $data->select_query("groups", "WHERE id = '{$userGroup}'", "id");
            $tempsql = $data->fetch_array($tempsql);
            $gid = $tempsql['id'];
            $temp = $data->num_rows($data->select_query("forummods", "WHERE fid=$f AND mid=$gid AND type=1")) ? 1 : 0;
            $userauths['mod'] = $userauths['mod'] || $temp;
        }
    }
    if ($userauths['view'] == 0 && !empty($f)) 
    {
        if ($check['id'] != "-1")
        {
            show_message("You do not have permisions to view that forum", "index.php?page=forums&menuid=$menuid");
        }
        else
        {
            $query = $_SERVER['QUERY_STRING'];
            $redirectpage = str_replace("page=", "", $query);
            header("Location: index.php?page=logon&redirect=$redirectpage&menuid=$menuid");
        }
    }
    elseif ($userauths['read'] == 0 && !empty($t))
    {
        if ($check['id'] != "-1")
        {
            show_message("You do not have permisions to read topics in that forum", "index.php?page=forums&menuid=$menuid");
        }
        else
        {
            $query = $_SERVER['QUERY_STRING'];
            $redirectpage = str_replace("page=", "", $query);
            header("Location: index.php?page=logon&redirect=$redirectpage&menuid=$menuid");
        }
    }
}
switch($action)
{
    case "post": 
        $edit = false;
        $new=false;
        include("forums/post.php");
        break;
    case "edit": 
        $edit=true;
        $new=false;
        include("forums/post.php");
        break;
    case "new": 
        $new = true;
        $edit = false;
        include("forums/post.php");
        break;
    case "topic": include("forums/view_topic.php");
        break;
    case "modf":
        include("forums/mod_forum.php");
        break;
    case "delete":
        if ($userauths['delete'] == 1)
        {
            $pid = safesql($_GET['p'], 'int');
            $tid = safesql($_GET['t'], 'int');
            if ($_POST['submit'] == "Yes")
            {
                $sql = $data->select_query("forumposts", "WHERE topic=$tid");
                $postinfo = $data->fetch_array($sql);
                if($data->num_rows($sql) <= 1)
                {
                    echo "<script>window.location='index.php?page=forums&action=topic&t=$tid&menuid=$menuid';</script>";
                    exit;
                }
                $sql = $data->delete_query("forumposts", "id=$pid", "Forums", "Post Deleted");
                if ($sql)
                {
                    $sql = $data->select_query("forumtopics", "WHERE id=$tid");
                    $topic = $data->fetch_array($sql);
                    
                    $sql = $data->select_query("forumposts", "WHERE topic=$tid ORDER BY dateposted DESC");
                    $latest = $data->fetch_array($sql);
                    $data->update_query("forumtopics", "lastpost='{$latest['userposted']}', lastdate={$latest['dateposted']}", "id=$tid", "", "", false);
                    
                    $sql = $data->select_query("forumtopics", "WHERE forum={$topic['forum']} ORDER BY lastdate DESC");
                    $newtopic = $data->fetch_array($sql);
                    $data->update_query("forums", "lasttopic='{$newtopic['id']}', lastpost='{$newtopic['lastpost']}', lastdate={$newtopic['lastdate']}", "id={$topic['forum']}", "", "", false);
                    
                    echo "<script>window.location='index.php?page=forums&action=topic&t=$tid&menuid=$menuid';</script>";
                    exit;
                }
            }
            $pagenum=7;
            $tpl->assign("tid", $tid);
        }
        else
        {
                echo "<script>alert('You don\\'t have the required permisions to delete a post''); window.location='index.php?page=forums&action=topic&t=$tid';</script>";
                exit;
        }
        break;
    case "stopwatching":
        $tid = safesql($_GET['tid'], "int");
        $user = safesql($_GET['u'], "int");
        $data->update_query("forumstopicwatch", "notify=0", "uid = $user AND topic_id=$tid", "", "", false);
        show_message("You are no longer watching the topic");
        include("forums/view_forum.php");
        break;
    case "allread" :
        $sql = $data->delete_query("forumnew", "uid={$check['id']}");
        include("forums/view_forum.php");
        break;
    case "forumread" :
        $sql = $data->delete_query("forumnew", "uid={$check['id']} AND forum=$f");
    default: include("forums/view_forum.php");
}

$tpl->assign("username", $check['uname']);
$tpl->assign("userauths", $userauths);
$tpl->assign('editFormAction', $editFormAction);  
$dbpage = true;
$pagename = "forums";
?>