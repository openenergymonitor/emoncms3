<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->
<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">

<h3>Are you sure you want to delete the feed: <?php echo $name; ?></h3>
<p>This does not permanently delete the feed, deleted feeds will be recoverable from the recycle bin once this functionality is built.</p>
<form action="feed" method="post">
<input type="hidden" name="form" value="delete">
<input type="hidden" name="feedid" value="<?php echo $id; ?>">
<input type="submit" value="Yes please delete" class="button05"/>
</form>

</div>

