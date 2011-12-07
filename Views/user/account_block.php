<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->
<form action="<?php echo $GLOBALS['path']; ?>" method="post">
  <input type="hidden" name="form" value="logout"/>
  <div style="margin:5px; float:right;">
  <span style="margin:10px;"><?php echo $name; ?></span><input type="submit" value="Logout" class="button04" />
  </div>
</form>
