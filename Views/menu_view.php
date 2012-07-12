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
  $viewl = 'dash/run';
} else {
  $logo = get_theme_path() . "/emoncms logo.png"; 
  $viewl = 'dashboards/view'; 
}
?>		

<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
</button>

<?php
  if ($_SESSION['editmode'] == TRUE) { echo "<a class='brand' href='#'>Emoncms3</a>"; }
?> 
  
<div class="nav-collapse collapse">      
  <ul class="nav">
    <li><a style="padding:5px;" href="<?php echo $GLOBALS['path'] . $viewl; ?>"><img id="emoncms-logo" src="<?php echo $logo; ?>" /></a></li>

    <?php if ($_SESSION['editmode'] == TRUE) { ?>
    <li><a href='<?php echo $GLOBALS['path']; ?>input/list'><?php echo _("Inputs"); ?></a></li>
    <li><a href='<?php echo $GLOBALS['path']; ?>feed/list'><?php echo _("Feeds"); ?></a></li>
    <li><a href='<?php echo $GLOBALS['path']; ?>dashboards/view'><?php echo _("Dashboards"); ?></a></li>
    <li><a href='<?php echo $GLOBALS['path']; ?>vis/list'><?php echo _("API"); ?></a></li>
 
  <?php } ?>
  </ul>

  <?php if ($_SESSION['editmode'] == TRUE) { ?>
  <ul class="nav pull-right">  
    <li><a href='<?php echo $GLOBALS['path']; ?>user/view'><?php echo _("Account"); ?></a></li>
    <li><a href='<?php echo $GLOBALS['path']; ?>user/logout'><?php echo _("Logout"); ?></a></li>
  </ul>  		
<?php } ?>

</div>