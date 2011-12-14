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

  function confirmdelete_controller()
  {
    if ($_POST['form'] == "delete")
    {
      $id = $_POST['feedid'];
      $name = $_POST['feedname'];

      $content = view("confirmdelete_view.php", array('id'=>$id,'name'=>$name));
    }
    else 
    {
      $content = "Please select feed to delete from feed page";
    }

    return $content;
  }

?>
