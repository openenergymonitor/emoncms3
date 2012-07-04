<?php
/*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
*/


?>


<?php if ($_SESSION['editmode'] == TRUE) { ?>
	<li><a href='<?php echo $GLOBALS['path']; ?>input/list'><?php echo _("Inputs"); ?></a></li>
	<li><a href='<?php echo $GLOBALS['path']; ?>feed/list'><?php echo _("Feeds"); ?></a></li>
	<li><a href='<?php echo $GLOBALS['path']; ?>vis/list'><?php echo _("Vis"); ?></a></li>
	<li><a href='<?php echo $GLOBALS['path']; ?>user/view'><?php echo _("Account"); ?></a></li>
	<!--<li><a href='<?php echo $GLOBALS['path']; ?>dashboard/view'><?php echo _("Dashboard");?></a></li>-->
	<li><a href='<?php echo $GLOBALS['path']; ?>dashboards/view'><?php echo _("Dashboards"); ?></a></li>
<?php } ?>



