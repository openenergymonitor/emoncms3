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

  function confirm_controller()
  {
    $message = preg_replace('/[^\w\s-.<>?:]/','',$_POST['message']);	// filter out all except for alphanumeric white space and dash
    $message = db_real_escape_string($message);
    $id = intval($_POST['id']);

    $action = preg_replace('/[^.\/a-z]/','',$_POST['action']); 		// filter out all except a-z / . 
    $action = db_real_escape_string($action);

    $content['content'] = view("confirm_view.php", array('message'=>$message,'id'=>$id, 'action'=>$action));

    return $content;
  }

?>