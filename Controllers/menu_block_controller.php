<?php 
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */
  function menu_block_controller()
  {
    $menu = '';

    if ($_SESSION['valid'])
    {
      $menu .= "<li><a href='inputpage'>Inputs</a></li>";
      $menu .= "<li><a href='feed'>Feeds</a></li>";
      $menu .= "<li><a href='apipage'>API</a></li>";
      $menu .= "<li><a href='dashboard'>Dashboard</a></li>";
    } 
    else 
    {
    }

    return $menu;
  }


?>
