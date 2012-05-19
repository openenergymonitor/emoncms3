<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->

<?php

  $id = $feed[0];
  $name = $feed[1];
  $tag = $feed[2];

?>

<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">
<h2><?php echo $name; ?></h2>
<h3>
Select graph type:</h3>

 <form action="../vis/realtime" method="get">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
        <input type="submit" class="button06" value="Real-time"></input>
 </form>

 <form action="../vis/rawdata" method="get">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
        <input type="submit" class="button06" value="Raw data"></input>
 </form>

 <form action="../vis/bargraph" method="get">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
          <input type="submit" class="button06" value="Bar graph"></input>
 </form>

 <form action="../vis/histgraph" method="get">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
          <input type="submit" class="button06" value="Histogram"></input>
 </form>

<h2>Tag feed</h2>
<form action="tag" method="get">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="text" name="tag" style="width:100px;" value="<?php echo $tag; ?>" />
<input type="submit" value="Save" class="button05"/>
</form>
<h2>Rename feed</h2>
<form action="rename" method="get">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="text" name="name" style="width:100px;" value="<?php echo $name; ?>" />
<input type="submit" value="Save" class="button05"/>
</form>

<h2>Delete feed?</h2>
<?php $message = "<h2>Are you sure you want to delete feed: ".$name."?</h2>"; ?>
<form action="../confirm" method="post">
<input type="hidden" name="message" value="<?php echo $message; ?>">
<input type="hidden" name="action" value="feed/delete">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="submit" value="delete" class="button05"/>
</form>

<h2>Notify</h2>
      <form action="../notify/view" method="get">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" value="Set notifications" class="button05" style="width:150px"/>
      </form>

</div>

