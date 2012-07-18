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

require "Includes/forms.php";
?>

  <h2><?php echo _("User: "); ?><?php echo $user['username']; ?></h2>
  <?php SelectLanguageForm($user['lang']); ?>
  
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
        <td>
          <?php echo _("Disk space use:"); ?>
        </td>
        <td>
          <?php echo number_format($stats['memory'] / 1024.0, 1); ?> KiB
        </td>
     </tr>
     <tr>
        <td>
          <?php echo _("Up hits:"); ?>
        </td>
        <td>
          <?php echo $stats['uphits']; ?>
        </td>
     </tr>
     <tr>
        <td>
          <?php echo _("Down hits:"); ?>
        </td>
        <td>
          <?php echo $stats['dnhits']; ?>
        </td>
      </tr>
    </table>
  </div>

