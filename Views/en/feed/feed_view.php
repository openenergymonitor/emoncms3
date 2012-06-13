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
  $type = $feed[6];
  $status = $feed[7];

?>

<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">
<h2><?php echo $name; ?></h2>
<h3>
Select graph type:</h3>

 <?php if ($type==1 || $type==0) { ?>
 <form action="../vis/realtime" method="get">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
        <input type="submit" class="button06" value="Real-time"></input>
 </form>

 <form action="../vis/rawdata" method="get">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
        <input type="submit" class="button06" value="Raw data"></input>
 </form>
 <?php } ?>

 <?php if ($type==2 || $type==0) { ?>
 <form action="../vis/bargraph" method="get">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
          <input type="submit" class="button06" value="Bar graph"></input>
 </form>
 <?php } ?>

 <?php if ($type==3 || $type==0) { ?>
 <form action="../vis/histgraph" method="get">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
          <input type="submit" class="button06" value="Histogram"></input>
 </form>
 <?php } ?>

 <?php if ($type!=3) { ?>
 <form action="../vis/edit" method="get">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
          <input type="submit" class="button06" value="Datapoint Editor"></input>
 </form>
 <?php } ?>

<?php if ($status==0) { ?>

<h2>Feed type</h2>
<form action="type" method="get">

<select name="type">
<option value="0" <?php if ($type==0) echo "selected"; ?> >Undefined</option>
<option value="1" <?php if ($type==1) echo "selected"; ?> >Real-time data</option>
<option value="2" <?php if ($type==2) echo "selected"; ?> >Daily data</option>
<option value="3" <?php if ($type==3) echo "selected"; ?> >Histogram data</option>
</select>

<input type="hidden" name="id" value="<?php echo $id; ?>">



<input type="submit" value="Save" class="button05"/>
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

<?php } else { ?>

<h3>Restore feed:
<form action="restore" method="get">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="submit" value="restore" class="button05"/>
</form>
</h3>
<?php } ?>
