<!--
All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org
-->
<div style="margin: 0px auto; max-width:940px; padding:10px;">

  <?php echo $message; ?>
  <form action="<?php echo $action; ?>" method="get">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="submit" value="<?php echo _("Yes please"); ?>" class="button05"/>
  </form>

</div>

