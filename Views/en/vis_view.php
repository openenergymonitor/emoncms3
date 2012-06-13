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

<tr><form action="smoothie" method="get">
  <td>Smoothie</td>
  <td style="text-align:right">vis/smoothie?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="histgraph" method="get">
  <td>All time histogram</td>
  <td style="text-align:right">vis/histgraph?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="dailyhistogram" method="get">
  <td>Daily histogram</td>
  <td style="text-align:right">vis/dailyhistogram?power= <input name="power" type='text' style='width:50px' /> &kwhd= <input name="kwhd" type='text' style='width:50px' /> &whw= <input name="whw" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="zoom" method="get">
  <td>kWh/d Zoomer</td>
  <td style="text-align:right">vis/zoom?power= <input name="power" type='text' style='width:50px' /> &kwhd= <input name="kwhd" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="simplezoom" method="get">
  <td>Simpler zoom</td>
  <td style="text-align:right">vis/simplezoom?power= <input name="power" type='text' style='width:50px' /> &kwhd= <input name="kwhd" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="stacked" method="get">
  <td>kWh/d Stacked</td>
  <td style="text-align:right">vis/stacked?kwhdA= <input name="kwhdA" type='text' style='width:50px' /> &kwhdB= <input name="kwhdB" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="threshold" method="get">
  <td>kWh/d threshold at power</td>
  <td style="text-align:right">vis/theshold/?feedid= <input name="feedid" type='text' style='width:50px' /> &thresholdA= <input name="thresholdA" type='text' style='width:50px' /> &thresholdB= <input name="thresholdB" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="orderbars" method="get">
  <td>Bar graph (ordered by height)</td>
  <td style="text-align:right">vis/orderbars?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="orderthreshold" method="get">
  <td>Threshold ordered by height</td>
  <td style="text-align:right">vis/orderthreshold?feedid= <input name="feedid" type='text' style='width:50px' /> &power= <input name="power" type='text' style='width:50px' /> &thresholdA= <input name="thresholdA" type='text' style='width:50px' /> &thresholdB= <input name="thresholdB" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="multigraph" method="get">
  <td>Multigraph</td>
  <td style="text-align:right">vis/multigraph</td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

<tr><form action="edit" method="get">
  <td>Datapoint Editor</td>
  <td style="text-align:right">vis/edit?feedid= <input name="feedid" type='text' style='width:50px' /></td>
  <td><input type="submit" value=">" class="button05"/></td>
</form></tr>

</table>

<h3>Other options:</h3>

<p><b>Hide menu</b><br>
Hide the top menu and footer by adding the attribute <i>&embed=1</i> to the URL.</p>

<p><b>Share</b><br>
To share a visualisation use your read apikey: add the attribute <i>&apikey=<?php echo $apikey; ?></i> to the URL.</p>

<p><b>Embed</b><br>
<?php echo htmlspecialchars('<iframe style="width:650px; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$path.'vis/rawdata?feedid=1&apikey='.$apikey.'&embed=1">
  </iframe>'); ?></p>

</div>



