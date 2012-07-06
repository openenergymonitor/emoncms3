<?php
/*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
*/
			
if ($_SESSION['editmode'] == TRUE) { 
  $logo = get_theme_path() . "/emoncms logo off.png";
  $viewl = 'dashboard/run';
} else {
  $logo = get_theme_path() . "/emoncms logo.png"; 
  $viewl = 'dashboards/view'; 
}
?>		

<ul class="nav">

<li><a style="padding:5px;" href="<?php echo $GLOBALS['path'] . $viewl; ?>"><img id="emoncms-logo" src="<?php echo $logo; ?>" /></a></li>

<?php if ($_SESSION['editmode'] == TRUE) { ?>
  <li><a href='<?php echo $GLOBALS['path']; ?>input/list'><?php echo _("Inputs"); ?></a></li>
  <li><a href='<?php echo $GLOBALS['path']; ?>feed/list'><?php echo _("Feeds"); ?></a></li>
  <li><a href='<?php echo $GLOBALS['path']; ?>vis/list'><?php echo _("Vis"); ?></a></li>
  <li><a href='<?php echo $GLOBALS['path']; ?>user/view'><?php echo _("Account"); ?></a></li>
  <li><a href='<?php echo $GLOBALS['path']; ?>dashboards/view'><?php echo _("Dashboards"); ?></a></li>
<?php } ?>

</ul>

<ul class="nav pull-right">
  <li>
    <form class="navbar-form" action="<?php echo $GLOBALS['path']; ?>user/logout" method="post">
      <input type="hidden" name="CSRF_token" value="<?php echo $_SESSION['CSRF_token'];?>" />
      <input type="submit" value="<?php echo _("Logout");?>" />  						
    </form>
  </li>
</ul>  		

