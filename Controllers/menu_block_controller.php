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
    $path = $GLOBALS['path'];
    $menu = '';

    if ($_SESSION['valid'])
    {
      $menu .= "<li><a href='".$path."inputpage'>Inputs</a></li>";
      $menu .= "<li><a href='".$path."feed'>Feeds</a></li>";
      $menu .= "<li><a href='".$path."apipage'>API</a></li>";
      $menu .= "<li><a href='".$path."dashboard'>Dashboard</a></li>";
    } 
    else 
    {
      //$menu .= "<li><a href='".$path."home'>Home</a></li>";
    }

    return $menu;
  }


?>
