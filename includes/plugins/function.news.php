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
function smarty_function_news($params, &$smarty){
	global $data;
	
	$date = getdate();
	$dayEnd = mktime(23,59,59,$date['mon'],$date['mday'],$date['year']);
	
	$categoryName = safesql($params['cat'], 'text');
	$result = $data->select_query('newscontent', 
		'WHERE allowed = 1 AND news_categories.name = ' . $categoryName . ' AND (date_end >= ' . $dayEnd . ' OR date_end = 0) ORDER BY event DESC',
		'newscontent.*, news_categories.name as categoryName',
		array(
			'left' => array(
				'news_categories' => 'news_categories.id = newscontent.category_id'
			)
		)
	);
	
	$smarty->assign('numnews', $data->num_rows($result));
	$announcements = array();
	while ($temp = $data->fetch_array($result)) {
		$attachmentTemp = explode('.', $temp['attachment']);
        $attachId = safesql($attachmentTemp[0], "int");
        $attachment = array();
        switch($attachmentTemp[1])
        {
            case 'article':
                $temp2 = $data->select_fetch_one_row("patrol_articles", "WHERE ID=$attachId", "ID, title");
                $attachment['name'] = $temp2['title'];
                $attachment['link'] = "index.php?page=patrolarticle&amp;id={$attachId}&amp;menuid={$menuid}&amp;action=view";
                $attachment['type'] = "Article";
                break;
            case 'album':
                $temp2 = $data->select_fetch_one_row("album_track", "WHERE ID=$attachId", "ID, album_name");
                $attachment['name'] = $temp2['album_name'];
                $attachment['link'] = "index.php?page=photos&amp;album={$attachId}&amp;menuid={$menuid}";
                $attachment['type'] = "Photo Album";
                break;
            case 'event':
                $temp2 = $data->select_fetch_one_row("calendar_items", "WHERE id=$attachId", "id, summary");
                $attachment['name'] = $temp2['summary'];
                $attachment['link'] = "index.php?page=calender&amp;item={$attachId}&amp;menuid={$menuid}";
                $attachment['type'] = "Event";
                break;
            case 'download':
                $temp2 = $data->select_fetch_one_row("downloads", "WHERE id=$attachId", "id, name, cat");
                $attachment['name'] = $temp2['name'];
                $attachment['link'] = "index.php?page=downloads&amp;id={$attachId}&amp;action=down&amp;catid={$temp2['cat']}&amp;menuid={$menuid}";
                $attachment['type'] = "Download";
                break;
            case 'news':
                $temp2 = $data->select_fetch_one_row("newscontent", "WHERE id=$attachId", "id, title");
                $attachment['name'] = $temp2['title'];
                $attachment['link'] = "index.php?page=news&amp;id={$attachId}&amp;menuid={$menuid}";
                $attachment['type'] = "News";
                break;
        }
        $temp['attachment'] = $attachment;
		
		$announcements[] = $temp;
	}

	$page = $data->select_fetch_one_row('pagecontent', 'WHERE pagetracking.pagename = \'oldnews\'', '*', array(
		'left' => array(
			'pagetracking' => 'pagecontent.pageid = pagetracking.id'
		)
	));
	
	$smarty->assign('arcnews', $announcements);
	
	require_once('function.eval.php');
	
	return smarty_function_eval(array('var' => $page['content']), $smarty);
}

/* vim: set expandtab: */

?>
