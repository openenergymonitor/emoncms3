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

  function new_dashboards($userid)
  {
    db_query("INSERT INTO dashboard (`userid`) VALUES ('$userid')");
  }

  function get_dashboards($userid)
  {
    $result = db_query("SELECT * FROM dashboard WHERE userid='$userid'");
    return $result;
  }

  function delete_dashboards($id)
  {
	db_query("DELETE FROM dashboard WHERE id = '$id'");
	return "DELETE FROM dashboard WHERE id = '$id'";
  }
