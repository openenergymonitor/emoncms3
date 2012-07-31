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

?>

  <h2><?php echo _("User: "); ?><?php echo $user['username']; ?></h2>
  <?php   
  /*
  * Create combo with available languages
  */
  echo '<form class="well form-inline" action="setlang" method="get">';
  echo '<span class="help-block">'._("Select preferred language").'</span>';  
  echo '<select name="lang">';
  
  if ($handle = opendir('locale')) {
    if ($user['lang']=='')
      echo '<option selected value="">'._("Browser language").'</option>';
    else 
      echo '<option value="">'._("Browser language").'</option>';
    
      while (false !== ($entry = readdir($handle))) 
        if (is_dir('locale/'.$entry) && ($entry !='.') && ($entry!='..'))
      {
        if ($entry == $user['lang'])
          echo '<option selected value="'.$entry.'">'._($entry).'</option>';
        else
              echo '<option value="'.$entry.'">'._($entry).'</option>';
      }
               
    closedir($handle);
    echo '</select>';   
  } 
    
  echo '<input type="submit" value="'._("Save").'" class="btn">';
  echo '</form>';
  
  ?>
               
  <form class="well" action="changedetails" method="post">
    <h3><?php echo _("Change details"); ?></h3>

    <label><?php echo _("Username:"); ?></label>
    <input type="username" name="username" value="<?php echo $user['username']; ?>" />

    <label><?php echo _("Email:"); ?></label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" />
    <br>
    <input type="submit" class="btn btn-danger" value="<?php echo _("Change"); ?>" />
  </form>

  <form class="well" action="changepass" method="post">
    <h3><?php echo _("Change password"); ?></h3>
    <label><?php echo _("Current password:"); ?></label>
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

<?php
/*
 * Fake code to be detected by POedit to translate languages name
 * Do you know a better way to do that? If not here POedit will delete them in the mo file 
 * Compiler (php interpreter will ignore it)
 */
{
  _("en_EN");
  _("es_ES");
  _("nl_BE");
  _("nl_NL");     
}
?>
