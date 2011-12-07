<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */
  function dashboard_controller()
  {
    if (!$_SESSION['valid']) return "Sorry, you must be logged in to see this page";

    require "Models/dashboard_model.php";

    $userid = $_SESSION['userid'];

    $apikey_read = get_apikey_read($userid);
    $apikey_write = get_apikey_write($userid);
    $page = get_dashboard($userid);

    if (!$page) $page = "<h2>Welcome to your dashboard: please edit me!</h2>";
    $content = view("dashboard_view.php", array('page'=>$page,'apikey_read'=>$apikey_read,'apikey_write'=>$apikey_write,'path'=>$GLOBALS['path']));
   
    return $content;
  }

?>
