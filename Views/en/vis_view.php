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

<h2>Visualisation API</h2>

<table class='catlist'>
<tr><th>Name</th><th style="text-align:right">URL</th><th>View</th></tr>

<tr><form action="realtime" method="get">
  <td>Real-time graph</td>
  <td style="text-align:right">vis/realtime?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="rawdata" method="get">
  <td>Raw data graph</td>
  <td style="text-align:right">vis/rawdata?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="bargraph" method="get">
  <td>Bar graph</td>
  <td style="text-align:right">vis/bargraph?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="../Vis/smoothie/smoothie" method="get">
  <td>Smoothie</td>
  <td style="text-align:right">Vis/smoothie/smoothie?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="histgraph" method="get">
  <td>All time histogram</td>
  <td style="text-align:right">vis/histgraph?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="../Vis/kwhd_histogram/kwhdzoomer" method="get">
  <td>Daily histogram</td>
  <td style="text-align:right">Vis/kwhd_histogram/kwhdzoomer?power= <input name="power" type='text' style='width:50px' /> &kwhd= <input name="kwhd" type='text' style='width:50px' /> &whw= <input name="whw" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="../Vis/kWhdZoomer/kwhdzoomer" method="get">
  <td>kWh/d Zoomer</td>
  <td style="text-align:right">Vis/kWhdZoomer/kwhdzoomer?power= <input name="power" type='text' style='width:50px' /> &kwhd= <input name="kwhd" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="../Vis/kwhdstacked/kwhdstacked" method="get">
  <td>kWh/d Stacked</td>
  <td style="text-align:right">Vis/kwhdstacked/kwhdstacked?kwhdA= <input name="kwhdA" type='text' style='width:50px' /> &kwhdB= <input name="kwhdB" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="../Vis/powerlevels/" method="get">
  <td>kWh/d threshold at power</td>
  <td style="text-align:right">Vis/powerlevels/?feedid= <input name="feedid" type='text' style='width:50px' /> &threshold= <input name="threshold" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="multigraph" method="get">
  <td>Multigraph</td>
  <td style="text-align:right">vis/multigraph</td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

</table>



</div>



