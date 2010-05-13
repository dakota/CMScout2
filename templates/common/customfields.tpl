{section name=fields loop=$fields}
	<label for="{$fields[fields].name}" class="label">{$fields[fields].query}<span class="hintanchor" title="{if $fields[fields].required}Required{else}Optional{/if}::{$fields[fields].hint}"><img src="{$templateinfo.imagedir}help.png" alt="[?]"/></span></label>
	{assign var="name" value=$fields[fields].name}
	<div class="inputboxwrapper">
	{if $fields[fields].type == 1}
		<input name="{$fields[fields].name}" id="{$fields[fields].name}" type="text" size="{math equation="x + y" x=$fields[fields].options y=5}" maxlength="{$fields[fields].options}" value="{$post.custom.$name}" class="inputbox" {if $fields[fields].required}onblur="checkElement('{$fields[fields].name}', 'text', true, 0, 0, '');"{/if} />{if $fields[fields].required}<br /><span class="error" id="{$fields[fields].name}Error">Required</span>{/if}
	{elseif $fields[fields].type == 2}
		<textarea name="{$fields[fields].name}" id="{$fields[fields].name}" rows="5"  class="inputbox" {if $fields[fields].required}onblur="checkElement('{$fields[fields].name}', 'text', true, 0, 0, '');"{/if}>{$details.custom.$name}</textarea>{if $fields[fields].required}<br /><span class="error" id="{$fields[fields].name}Error">Required</span>{/if}
	{elseif $fields[fields].type == 3}
		{section name=options loop=$fields[fields].options[0]+1 start=1}
		<input type="radio" name="{$fields[fields].name}" id="{$fields[fields].name}:{$smarty.section.options.iteration}" value="{$smarty.section.options.iteration}" {if $post.custom.$name == $smarty.section.options.iteration}checked="checked"{/if} /><label for="{$fields[fields].name}:{$smarty.section.options.iteration}">{$fields[fields].options[options]}</label>
		{/section}
	{elseif $fields[fields].type == 4}
		{assign var="temp" value=$post.custom.$name}
		{section name=options loop=$fields[fields].options[0]+1 start=1}
		<input type="checkbox" name="{$fields[fields].name}{$smarty.section.options.iteration}" id="{$fields[fields].name}:{$smarty.section.options.iteration}" value="1" {if $temp[options] == 1}checked="checked"{/if} /><label for="{$fields[fields].name}:{$smarty.section.options.iteration}">{$fields[fields].options[options]}</label>&nbsp;
		{/section}
	{elseif $fields[fields].type == 5}
		<select name="{$fields[fields].name}" class="inputbox">
		{section name=options loop=$fields[fields].options[0]+1 start=1}
		<option value="{$smarty.section.options.iteration}" {if $post.custom.$name == $smarty.section.options.iteration}selected="selected"{/if}>{$fields[fields].options[options]}</option>
		{/section}
		</select>  
	{elseif $fields[fields].type == 6}
		<input name="{$fields[fields].name}" id="{$fields[fields].name}" type="text" value="{$post.custom.$name}" class="inputbox dateformat-Y-ds-m-ds-d" onblur="checkElement('{$fields[fields].name}', 'date', {if $fields[fields].required}true{else}false{/if}, 0, 0, '');"/><br /><span class="error" id="{$fields[fields].name}Error">{if $fields[fields].required}Required: {/if}Must be a valid date in the format YYYY-MM-DD</span>         
	{/if}   
	</div><br />
{/section} 