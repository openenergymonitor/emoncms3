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

?>

<form action="<?php echo $path; ?>user/logout" method="post">
  <div style="float:right;">
  <input type="hidden" name="CSRF_token" value="<?php echo $_SESSION['CSRF_token'];?>" />
  <span style="margin:10px;"><?php echo $name; ?></span><input type="submit" value="<?php echo _("Logout");?>" class="button04" />
  </div>
</form>
