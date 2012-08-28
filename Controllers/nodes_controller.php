<?php 
  /*
    All Emoncms code is released under the GNU Affero General Public License.
    See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    NODES CONTROLLER ACTIONS		ACCESS
		list												write
    
  */

  // no direct access
  defined('EMONCMS_EXEC') or die('Restricted access');

  function nodes_controller()
  {
    require "Models/nodes_model.php";
    global $session, $action, $format;

    $output['content'] = "";
    $output['message'] = "";

    //---------------------------------------------------------------------------------------------------------
    // Nodes List
    // http://yoursite/emoncms/nodes/list.html
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'list' && $session['write'])
    {
      $nodes = get_user_nodes($session['userid']);
          
      if ($format == 'html') $output['content'] = view("nodes/nodes_view.php", array('nodes' => $nodes));
    }
    
		return $output;
	}
	
?>	