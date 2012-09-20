<?php

  global $session;
/*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
*/
if (!isset($session['read']) || (!$session['read'])) return;

if (isset($_SESSION['editmode']) && ($_SESSION['editmode'] == TRUE)) { 
  $logo = get_theme_path() . "/emoncms logo off.png";
  $viewl = $session['username'];
} else {
  $logo = get_theme_path() . "/emoncms logo.png"; 
  if ($session['write']) $viewl = 'dashboard/list'; else $viewl = '';

}

/*<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
</button>*/

  if (isset($_SESSION['editmode']) && ($_SESSION['editmode'] == TRUE)) { echo "<a class='brand' href='#'>Emoncms3</a>"; }
?>
  <ul class="nav">
    <li><a style="padding:5px;" href="<?php echo $GLOBALS['path'] . $viewl; ?>"><img id="emoncms-logo" src="<?php echo $logo; ?>" /></a></li>
    <?php if (isset($_SESSION['editmode']) && ($_SESSION['editmode'] == TRUE)) { ?>
    <li><a href='<?php echo $GLOBALS['path']; ?>input/list'><?php echo _('Inputs'); ?></a></li>
    <li><a href='<?php echo $GLOBALS['path']; ?>feed/list'><?php echo _('Feeds'); ?></a></li>
    <li><a href='<?php echo $GLOBALS['path']; ?>dashboard/list'><?php echo _('Dashboards'); ?></a></li>
    <li><a href='<?php echo $GLOBALS['path']; ?>vis/list'><?php echo _('API'); ?></a></li>

  <?php } ?>
  </ul>
  <?php if (isset($_SESSION['editmode']) && ($_SESSION['editmode'] == TRUE)) { ?>
  <ul class="nav pull-right">  
    <?php if ($session['admin']) { ?>
      <li><a href='<?php echo $GLOBALS['path']; ?>admin'><?php echo _('Admin'); ?></a></li>
    <?php } ?>
    <li><a href='<?php echo $GLOBALS['path']; ?>user/view'><?php echo _('Account'); ?></a></li>
    <li><a href='<?php echo $GLOBALS['path']; ?>user/logout'><?php echo _('Logout'); ?></a></li>
  </ul>  		
<?php } ?>

