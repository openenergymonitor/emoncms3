<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->

<?php

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

global $path, $session; 

?>

<div style="margin-left:20px;">

<div style="max-width:392px; margin-right:20px; padding-top:45px; padding-bottom:15px; color: #888;">
<img style="margin:12px;" src="<?php print $GLOBALS['path']; ?>Views/user/emoncms_logo.png" />
</div>

<div class="login-container">

<div style="text-align:left">

<form action="" method="post">

<p>Email:<br/>
<input class="inp01" type="text" name="name" style="width:94%"/></p>
<p>Password:<br/>
<input class="inp01" type="password" name="pass" autocomplete="off" style="width:94%"/></p>

<input type="submit" class="button04" value="Login" onclick="javascript: form.action='<?php echo $GLOBALS['path']; ?>user/login';" /> <br/><br/>
<div style="background-color:#ddd;">
<table style="font-size:13px"><tr><td width="265px">
Or if your new enter your email and a <br/>password above and click register:</td><td><input type="submit" class="button05" value="Register" onclick="javascript: form.action='<?php echo $GLOBALS['path']; ?>user/create';" /></td></tr></table>
  </table>
  <?php echo $error; ?>
</form>
</div>

</div>

</div>

