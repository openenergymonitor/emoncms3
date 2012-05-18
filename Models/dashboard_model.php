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

  function set_dashboard($userid,$content,$id)
  {
    $result = db_query("SELECT * FROM dashboard WHERE userid = '$userid' and id='$id'");
    $row = db_fetch_array($result);

    if ($row)
    {
      db_query("UPDATE dashboard SET content = '$content' WHERE userid='$userid' and id='$id'");
    }
    else
    {
      db_query("INSERT INTO dashboard (`userid`,`content`,`id`) VALUES ('$userid','$content','$id')");
    }
  }

  function get_dashboard($userid)
  {
    $result = db_query("SELECT content FROM dashboard WHERE userid='$userid'");
    $result = db_fetch_array($result);
    $dashboard = $result['content'];

    return $dashboard;
  }
  
  function get_dashboard_id($userid,$id)
  {
    $result = db_query("SELECT content FROM dashboard WHERE userid='$userid' and id='$id'");
    $result = db_fetch_array($result);
    $dashboard = $result['content'];

    return $dashboard;
  }  
