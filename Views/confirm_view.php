<?php
  /*
  All Emoncms code is released under the GNU Affero General Public License.
  See COPYRIGHT.txt and LICENSE.txt.

  ---------------------------------------------------------------------
  Emoncms - open source energy visualisation
  Part of the OpenEnergyMonitor project:
  http://openenergymonitor.org
  */

  echo $message; 
?>

<form action="<?php echo $action; ?>" method="get">
  <input type="hidden" name="id" value="<?php echo $id; ?>">
  <input type="submit" value="<?php echo _("Yes please"); ?>" class="button05"/>
</form>



