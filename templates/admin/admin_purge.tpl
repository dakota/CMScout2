<h2>Purge Users</h2>
 <div align="center">
 <form action="" method="post" enctype="multipart/form-data" name="form1">
  <fieldset class="formlist">
  <legend>Purge Users</legend>
  <div class="field">
  <label for="file" class="label">Purge<span class="hintanchor" title="Purge all users who have not logged in since"><img src="{$tempdir}admin/images/help.png" alt="[?]"/></span></label>
    <div class="inputboxwrapper">
    <input type="text" id="purge" name="purge" class="inputbox format-y-m-d highlight-days-67" />
    </div><br />
  </div>
        <div class="submitWrapper">
    <input type="submit" name="Submit" value="Submit" class="button" />
		<input name="Cancel" type="button" id="Cancel" value="Cancel" onclick="history.go(-1);" class="button" />
   </div>
  </fieldset>
  </form>
  </div>
