<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

  // no direct access
  defined('EMONCMS_EXEC') or die('Restricted access');

function get_user_nodes($userid)
{
  $result = db_query("SELECT DISTINCT nodeid FROM input WHERE userid = '$userid' AND nodeid <> 0");
  $nodes = array();
  if ($result)
  {
    while ($row = db_fetch_array($result))
    {
      $nodes[] = array(
        'nodeid'=>$row['nodeid']
      );
    }
  }
  return $nodes;
}

?>