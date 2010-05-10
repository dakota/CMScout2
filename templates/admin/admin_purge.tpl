<h2>Purge Users</h2>
<div align="center">
	<form action="" method="post" name="form1">
		<fieldset class="formlist">
				<legend>Purge Users</legend>
				<div class="field">
					<label for="file" class="label">Purge<span class="hintanchor" title="Purge all users who have not logged in since"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
					<div class="inputboxwrapper">
						<input type="text" id="purge" name="purge" class="inputbox dateformat-Y-ds-m-ds-d highlight-days-67" value="{$purgeDate}"/>
					</div><br />
				</div>
				
				{if $users}
					<h4>Users that will be deleted:</h4>
					<ul>
					{section name=userLoop loop=$userCount}
						<li>{$users[userLoop].firstname} {$users[userLoop].lastname} ({$users[userLoop].uname})</li>
					{/section}
					</ul>
				{/if}
		  
				<div class="submitWrapper">
					<input type="submit" name="Submit" value="Show users" class="button" />
					<input type="submit" name="Submit" value="Purge users" class="button" />
				</div>
		</fieldset>
	</form>
</div>
