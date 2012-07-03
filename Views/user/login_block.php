<?php
/*
 All Emoncms code is released under the GNU Affero General Public License.
 See COPYRIGHT.txt and LICENSE.txt.

 ---------------------------------------------------------------------
 Emoncms - open source energy visualisation
 Part of the OpenEnergyMonitor project:
 http://openenergymonitor.org
 */

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

global $path, $session;

require_once "Includes/locale.php";

// gets the accepted language browser list
$accepted_languages = lang_http_accept();

// set to the apropiated language
set_lang($accepted_languages);
?>

<div style="margin-left:20px;">

	<div style="max-width:392px; margin-right:20px; padding-top:45px; padding-bottom:15px; color: #888;">
		<img style="margin:12px;" src="<?php print $GLOBALS['path']; ?>Views/theme/common/emoncms_logo.png" />
	</div>

	<div class="login-container">

		<div style="text-align:left">

			<form action="" method="post">

				<p>
					<?php echo _("Email:"); ?><br/>
					<input class="inp01" type="text" name="name" style="width:94%"/>
				</p>
				<p>
					<?php echo _("Password:"); ?><br/>
					<input class="inp01" type="password" name="pass" autocomplete="off" style="width:94%"/>
				</p>

				<input type="submit" class="button04" value="<?php echo _("Login"); ?>" onclick="javascript: form.action='<?php echo $GLOBALS['path']; ?>user/login';" />
				<br/>
				<br/>
				<div style="background-color:#ddd;">
				<table style="font-size:13px"><tr><td width="265px">
				<?php echo _("Or if your new enter your email and a "); ?><br/>+<?php echo _("password above and click register:"); ?></td><td><input type="submit" class="button05" value="Register" onclick="javascript: form.action='<?php echo $GLOBALS['path']; ?>user/create';" /></td></tr></table>
				</table>
				<?php echo $error; ?>
			</form>
		</div>

	</div>

</div>

