<?php
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");

$fid = isset($_GET['fid']) ? safesql($_GET['fid'], 'int') : 0;

if($fid !== 0) {
	$form = $data->select_fetch_one_row('forms', 'WHERE forms.id='.$fid, 'forms.*, static_content.content', array(
		'left' => array(
			'static_content' => 'forms.static_content_id = static_content.id'
		)
	));

	$formFields = getFields('place=3 AND eventid = ' . $fid);

	if($_POST['Submit'] == 'Submit form') {
		if($config['registerimage'] == 0 || confirmCaptcha()) {
			$post = $_POST;

			$dataPosted = serializeCustomFields($formFields);

			$time = time();
			$data->insert_query('form_data', "'', $fid, $dataPosted, $time");

			if(trim($form['email_address'] !== '')) {
				$dataPosted = customFields('place=3 AND eventid = ' . $fid);
				$formdata = array();
				foreach($formFields as $field) {
					$formdata[] = $field['query'] . ': ' . $dataPosted[$field['name']];
				}
                $email = $data->select_fetch_one_row("emails", "WHERE type='form_submitted'");
                $cmscoutTags = array("!#formname#!", "!#website#!", "!#formdata#!");
                $replacements   = array($form['name'], $config['troopname'], implode("\r\n", $formdata));

                $emailContent = str_replace($cmscoutTags, $replacements, $email['email']);

				sendMail($form['email_address'], '', $config['emailPrefix'] . $email['subject'], $emailContent);
			}

			show_message('Your form has been submitted. Thank you', 'index.php?page=form&fid='.$fid);
		}
	}
	$tpl->assign('form', $form);
	$tpl->assign('numfields', $numfields);
	$tpl->assign('fields', $formFields);
}
else {
	
}

$dbpage = true;
$pagename = "form";