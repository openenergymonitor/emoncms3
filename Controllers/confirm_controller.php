<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

  //---------------------------------------------------------------------
  // The html content on this page could be seperated out into a view
  //---------------------------------------------------------------------

  function confirm_controller()
  {
    $message = $_POST['message'];
    $id = $_POST['id'];

    $action = $_POST['action'];
    $form = $_POST['form'];

    $content = view("confirm_view.php", array('message'=>$message,'id'=>$id, 'action'=>$action,'form'=>$form));

    return $content;
  }

?>
