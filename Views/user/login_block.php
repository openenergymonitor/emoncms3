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

<div style="margin: 0px auto; max-width:392px; padding:10px;">
	<div style="max-width:392px; margin-right:20px; padding-top:45px; padding-bottom:15px; color: #888;">
		<img style="margin:12px;" src="<?php echo get_theme_path(); ?>/emoncms_logo.png" />
	</div>

	<div class="login-container">
		<div style="text-align:left">
			<form class="well" action="" method="post">
				<p>
					<?php echo _("Username:"); ?><br/>
					<input type="text" name="name" style="width:94%"/>
				</p>
				<p>
					<?php echo _("Password:"); ?><br/>
					<input type="password" name="pass" autocomplete="off" style="width:94%"/>
				</p>

				<input type="submit" class="btn" value="<?php echo _("Login"); ?>" onclick="javascript: form.action='<?php echo $GLOBALS['path']; ?>user/login';" />
				<br/>
				<br/>
				<div style="background-color:#ddd;">
				<table style="font-size:13px">
					<tr>
						<td width="265px">
							<?php echo _("Or if you are new enter a username and password above and click register"); ?></td><td><input type="submit" class="btn btn-info" value="<?php echo _("Register"); ?>" onclick="javascript: form.action='<?php echo $GLOBALS['path']; ?>user/create';" />	
						</td>
					</tr>
				</table>
				<?php echo $error; ?>
			</form>
		</div>
	</div>
</div>

