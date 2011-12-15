<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->
<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">
<h2><?php echo $name; ?></h2>
<h3>
Select graph type:</h3>


 <form action="graph" method="post">
<input type="hidden" name="form" value="graph">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
        <input type="hidden" name="feedname" value="<?php echo $name; ?>">
        <input type="hidden" name="sel" value="1">
          <input type="submit" class="button06" value="Real-time">
</input>
        </form>

 <form action="graph" method="post">
<input type="hidden" name="form" value="graph">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
        <input type="hidden" name="feedname" value="<?php echo $name; ?>">
        <input type="hidden" name="sel" value="2">
          <input type="submit" class="button06" value="Raw data">
</input>
        </form>

 <form action="graph" method="post">
<input type="hidden" name="form" value="graph">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
        <input type="hidden" name="feedname" value="<?php echo $name; ?>">
        <input type="hidden" name="sel" value="3">
          <input type="submit" class="button06" value="Bar graph">
</input>
        </form>

<h2>Tag feed</h2>
<form action="feed" method="post">
<input type="hidden" name="form" value="tag">
<input type="hidden" name="feedid" value="<?php echo $id; ?>">
<input type="text" name="tag" style="width:100px;" value="<?php echo $tag; ?>" />
<input type="submit" value="Save" class="button05"/>
</form>
<h2>Rename feed</h2>
<form action="feed" method="post">
<input type="hidden" name="form" value="rename">
<input type="hidden" name="feedid" value="<?php echo $id; ?>">
<input type="text" name="feedname" style="width:100px;" value="<?php echo $name; ?>" />
<input type="submit" value="Save" class="button05"/>
</form>

<h2>Delete feed?</h2>
<?php $message = "<h2>Are you sure you want to delete feed: ".$name."?</h2>"; ?>
<form action="confirm" method="post">
<input type="hidden" name="message" value="<?php echo $message; ?>">
<input type="hidden" name="action" value="feed">
<input type="hidden" name="form" value="delete">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="submit" value="delete" class="button05"/>
</form>
</div>

