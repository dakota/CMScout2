<h2>Form Manager</h2>
{if $action == ''}
{literal}
  <script type="text/javascript">
<!--
function confirmDelete(articleId) {
if (confirm("This will delete this category. Continue?"))
  {/literal}
	document.location = "{$pagename}&action=delete&id=" + articleId;
  {literal}
}
//-->
</script>
  {/literal}
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=new" title="Add Form"><img src="{$tempdir}admin/images/add.png" alt="Add Category" border="0" /></a>
</div>{/if}
{if $numforms > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
	  <tr>
		<th width="6%" class="smallhead"></th>
		<th class="smallhead sortable">Name of Form</th>
	  </tr> 
      </thead><tbody>
	 {section name=albumloop loop=$numforms}
		 <tr class="text">
			<td class="text">
				<div align="center">
					<a href="{$pagename}&amp;action=edit&amp;id={$forms[albumloop].id}">
						<img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$forms[albumloop].name}" title="Edit {$forms[albumloop].name}" />
					</a>
					<a href="{$pagename}&amp;subpage=form_fields&amp;fid={$forms[albumloop].id}">
						<img src="{$tempdir}admin/images/mod.gif" border="0" alt="Edit {$forms[albumloop].name} fields" title="Edit {$forms[albumloop].name} fields" />
					</a>
					{if $deleteallowed}
					<a href="javascript:confirmDelete({$forms[albumloop].id})">
						<img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$forms[albumloop].name}" title="Delete {$forms[albumloop].name}" />
					</a>
					{else}
					<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />
					{/if}
				</div>
			</td>
			<td class="text">{$forms[albumloop].name}</td>
	  </tr>
	  {/section}
      </tbody>
	</table>
    {else}
<div align="center">No forms</div>
{/if}
{elseif $action == 'edit' || $action == 'new'}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
<div align="center">
<form action="{$editFormAction}" method="post" name="photos" onsubmit="return checkForm([['album_name','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if $action == 'new'}New{else}Edit{/if} form</legend>
<div class="field">
    <label for="name" class="label">Form Name<span class="hintanchor" title="Name of the form."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input value="{$form.name}" name="name" type="text" id="name" class="inputbox" onblur="checkElement('name', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="nameError">Required</span></div><br />

    <label for="email_address" class="label">Email to<span class="hintanchor" title="Email address to send form information to."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper"><input value="{$form.email_address}" name="email_address" type="text" id="email_address" class="inputbox" /></div><br />

    <label for="static_content_id" class="label">Content page<span class="hintanchor" title="You can display a static content page with the form."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">
	<select name="static_content_id" id="static_content_id" class="inputbox" onchange="itemss();">
		<option value="0" {if $form.static_content_id == 0}selected="selected"{/if}>None</option>
		
		{if $numpages > 0}
			{section name=pages loop=$numpages}
				<option value="{$page[pages].id}" {if $form.static_content_id == $page[pages].id}selected="selected"{/if}>{if $page[pages].friendly == ""}{$page[pages].name}{else}{$page[pages].friendly}{/if}</option>
			{/section}
		{/if}
		</select>
	 </div><br />
	  
</div>

<div class="submitWrapper">
	<input type="submit" name="submit" value="{if $action == 'new'}Add{else}Save{/if} Form" class="button" />
      <input type="button" name="Submit2" value="Cancel" onclick="window.location='admin.php?page=forms'" class="button" /></div>
</form>
</div>
{/if}
