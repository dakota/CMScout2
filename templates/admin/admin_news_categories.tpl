<h2>News Categories Manager</h2>
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
{if $addallowed}<div class="toplinks"><a href="{$pagename}&amp;action=new" title="Add Category"><img src="{$tempdir}admin/images/add.png" alt="Add Category" border="0" /></a>
</div>{/if}
{if $numcategories > 0}
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="table rowstyle-alt paginate-15" id="sortTable">
<thead>
	  <tr>
		<th width="6%" class="smallhead"></th>
		<th class="smallhead">Name of Category</th>
	  </tr> 
      </thead><tbody>
	 {section name=albumloop loop=$numcategories}
		 <tr class="text">
			<td class="text">
				<div align="center">
					<a href="{$pagename}&amp;action=edit&amp;id={$categories[albumloop].id}">
						<img src="{$tempdir}admin/images/edit.gif" border="0" alt="Edit {$categories[albumloop].name}" title="Edit {$categories[albumloop].name}" />
					</a>
					{if $deleteallowed}
					<a href="javascript:confirmDelete({$categories[albumloop].id})">
						<img src="{$tempdir}admin/images/delete.gif" border="0" alt="Delete {$categories[albumloop].name}" title="Delete {$categories[albumloop].name}" />
					</a>
					{else}
					<img src="{$tempdir}admin/images/delete_grey.gif" border="0" alt="Deleting Disabled" title="Deleting Disabled" />
					{/if}
				</div>
			</td>
			<td class="text">{$categories[albumloop].name}</td>
	  </tr>
	  {/section}
      </tbody>
	</table>
    {else}
<div align="center">No categories</div>
{/if}
{else}
<script type="text/javascript">
{include file="../scripts/validator.tpl"}
</script>
<div align="center">
<form action="{$editFormAction}" method="post" name="photos" onsubmit="return checkForm([['album_name','text',true,0,0,'']]);">
<fieldset class="formlist">
<legend>{if $action == 'new'}New{else}Edit{/if} category</legend>
<div class="field">
      <label for="fname" class="label">Category Name<span class="hintanchor" title="Name of the category."><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
      <div class="inputboxwrapper"><input value="{$category.name}" name="name" type="text" id="name" class="inputbox" onblur="checkElement('name', 'text', true, 0, 0, '');" /><br /><span class="fieldError" id="nameError">Required</span></div><br />
</div>
<textarea id="editor" name="editor" style="width:100%; height:500px" class="inputbox">{$category.description}</textarea>
<div class="submitWrapper">
<input type="submit" name="submit" value="{if $action == 'new'}Add{else}Save{/if} Category" class="button" />
      <input type="button" name="Submit2" value="Cancel" onclick="window.location='admin.php?page=news_categories'" class="button" /></div>
</form>
</div>
{/if}
