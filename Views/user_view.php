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

require "Includes/forms.php";
?>

<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">

  <h2><?php echo _("User: "); ?><?php echo $user['username']; ?></h2>
  <?php SelectLanguageForm($user['lang']); ?>
    
  <div class="well">

    <h3><?php echo _("API keys"); ?></h3>
    <table>
      <tr>
        <td><b><?php echo _("Read only access: "); ?></b><?php echo $user['apikey_read']; ?></td>
        <td>
        <form action="newapiread" method="post">
          <input type="submit" class="btn btn-warning" value="<?php echo _("new"); ?>" class="button05">
        </form></td>
      </tr>

      <tr>
        <td><b><?php echo _("Write only access: "); ?></b><?php echo $user['apikey_write']; ?></td>
        <td>
        <form action="newapiwrite" method="post">
          <input type="submit" class="btn btn-warning" value="<?php echo _("new"); ?>" class="button05">
        </form></td>
      </tr>
    </table>

  </div>

  <?php
  $testjson = $GLOBALS['path']."api/post?apikey=".$user['apikey_write']."&json={power:252.4,temperature:15.4}"
  ?>
  <div class="well">
    <p>
      <b>API url: </b><?php echo $GLOBALS['path']; ?>a
      pi/post
    </p>
    <p>
      <b><?php echo _("Example: Copy this to your web browser or send from a nanode: "); ?></b>
      <br/>
      <?php echo $testjson; ?> <a href="<?php echo $testjson; ?>"><?php echo _("try me"); ?></a>
    </p>
  </div>

    
    <form class="well" action="changepass" method="post">
    	<h3><?php echo _("Change password"); ?></h3>
      
		<label><?php echo _("Old password:"); ?></label>
		<input type="password" name="oldpass" />
        
        <label><?php echo _("New password:"); ?></label>
		<input type="password" name="newpass"/>
      	<br>
		<input type="submit" class="btn btn-danger" value="<?php echo _("Change"); ?>" />
	</form>
  
	<div class="well">
		<h3><?php echo _("Account Statistics"); ?></h3>
		<table>
			<tr>
				<td><?php echo _("Disk space use:"); ?></td><td><?php echo number_format($stats['memory'] / 1024.0, 1); ?> KiB</td>
      </tr>
      <tr>
        <td><?php echo _("Up hits:"); ?></td><td><?php echo $stats['uphits']; ?></td>
      </tr>
      <tr>
        <td><?php echo _("Down hits:"); ?></td><td><?php echo $stats['dnhits']; ?></td>
      </tr>
    </table>
  </div>
</div>

