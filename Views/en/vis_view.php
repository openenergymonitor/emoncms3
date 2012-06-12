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

<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">

<h2><?php echo _("Visualisation API");?></h2>

<table class='catlist'>
<tr><th><?php echo _("Name");?></th><th style="text-align:right"><?php echo _("URL");?></th><th><?php echo _("View");?></th></tr>

<tr><form action="realtime" method="get">
  <td><?php echo _("Real-time graph");?></td>
  <td style="text-align:right">vis/realtime?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="rawdata" method="get">
  <td><?php echo _("Raw data graph");?></td>
  <td style="text-align:right">vis/rawdata?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="bargraph" method="get">
  <td><?php echo _("Bar graph");?></td>
  <td style="text-align:right">vis/bargraph?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="../Vis/smoothie/smoothie" method="get">
  <td><?php echo _("Smoothie");?></td>
  <td style="text-align:right">Vis/smoothie/smoothie?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="histgraph" method="get">
  <td><?php echo _("All time histogram");?></td>
  <td style="text-align:right">vis/histgraph?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="../Vis/kwhd_histogram/kwhdzoomer" method="get">
  <td><?php echo _("Daily histogram");?></td>
  <td style="text-align:right">Vis/kwhd_histogram/kwhdzoomer?power= <input name="power" type='text' style='width:50px' /> &kwhd= <input name="kwhd" type='text' style='width:50px' /> &whw= <input name="whw" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="../Vis/kWhdZoomer/kwhdzoomer" method="get">
  <td><?php echo _("kWh/d Zoomer");?></td>
  <td style="text-align:right">Vis/kWhdZoomer/kwhdzoomer?power= <input name="power" type='text' style='width:50px' /> &kwhd= <input name="kwhd" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="../Vis/kwhdstacked/kwhdstacked" method="get">
  <td><?php echo _("kWh/d Stacked");?></td>
  <td style="text-align:right">Vis/kwhdstacked/kwhdstacked?kwhdA= <input name="kwhdA" type='text' style='width:50px' /> &kwhdB= <input name="kwhdB" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="multigraph" method="get">
  <td><?php echo _("Multigraph");?></td>
  <td style="text-align:right">vis/multigraph</td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

</table>



</div>



