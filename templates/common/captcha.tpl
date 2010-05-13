{if $config.registerimage}
	{if $config.captcha_type == 0}
		{literal}
		<script language="javascript">
		<!--
		function new_freecap()
		{
			// loads new freeCap image
			if(document.getElementById)
			{
				// extract image name from image source (i.e. cut off ?randomness)
				thesrc = document.getElementById("freecap").src;
				thesrc = thesrc.substring(0,thesrc.lastIndexOf(".")+4);
				// add ?(random) to prevent browser/isp caching
				document.getElementById("freecap").src = thesrc+"?"+Math.round(Math.random()*100000);
			} else {
				alert("Sorry, cannot autoreload freeCap image\nSubmit the form and a new freeCap will be loaded");
			}
		}
		//-->
		</script>
		{/literal}
		<fieldset>
		<legend>Captcha</legend>
		<div style="text-align:center">
			<a href="#" class="hintanchor" title="Captcha Image :: Click here to refresh this image." onClick="this.blur();new_freecap();return false;"><img src="captcha/freecap.php" id="freecap" border="0" alt="Click here to refresh this image"></a></div>
			<label for="captcha" class="label">Enter code above<span class="hintanchor"title="Required :: This is to protect this website from spam."><img src="{$templateinfo.imagedir}help.png" alt="[?]"/></span></label>
			<div class="inputboxwrapper"><input name="captcha" id="captcha" type="text" size="40" onblur="checkElement('captcha', 'text', true, 0, 0, '');" class="inputbox" /><br /><span class="error" id="captchaError">Required</span></div><br />
		</fieldset>
	{else}
		{show_recaptcha}
	{/if}
{/if}