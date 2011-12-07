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

  function graphselect_controller()
  {
    if ($_POST['form'] == "graph")
    {
      $id = $_POST['feedid'];
      $name = $_POST['feedname'];

      $content = view("graphselect_view.php", array('id'=>$id,'name'=>$name));
    }
    else 
    {
      $content = "Please select feed to view from feeds page";
    }

    return $content;
  }

?>
