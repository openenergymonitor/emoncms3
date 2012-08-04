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

  function time_controller()
  {
    global $session, $action;

    $output['content'] = "";
    $output['message'] = "";

    if ($action == 'server' && $session['read'])
    {
      $output['content'] = date('H:i:s');
    }

    return $output;
  }

?>
