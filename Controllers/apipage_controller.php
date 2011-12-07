<?php 
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */
function apipage_controller()
{

  if (!$_SESSION['valid']) return "Sorry, you must be logged in to see this page";

  $userid = $_SESSION['userid'];

  $apikey_write = get_apikey_write($userid);
  $apikey_read = get_apikey_read($userid);

  if ($_POST["form"] == "newapi_write" || !$apikey_write)
  { 
    $apikey_write = md5(uniqid(rand(), true));
    set_apikey_write($userid, $apikey_write);
  }

  if ($_POST["form"] == "newapi_read" || !$apikey_read)
  { 
    $apikey_read = md5(uniqid(rand(), true));
    set_apikey_read($userid, $apikey_read);
  }


  // Render view
  $content = view("api_view.php",array('apikey_read' => $apikey_read,'apikey_write' => $apikey_write));

  return $content;
}

?>


