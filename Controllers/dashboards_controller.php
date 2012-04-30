<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    DASHBOARD ACTIONS		ACCESS
    new				write
    view			read
	delete			write
   
  */

  function dashboards_controller()
  {
    require "Models/dashboards_model.php";
    global $session, $action, $format;

    $output['content'] = "";
    $output['message'] = "";

    // /dashboard/set?content=<h2>HelloWorld</h2>
    if ($action == 'new' && $session['write']) // write access required
    {
      new_dashboards($session['userid']);
      $output['message'] = "dashboards new";
	}

    if ($action == 'delete' && $session['write']) // write access required
    {
      $output['message'] = delete_dashboards(intval($_POST["content"]));
      //$output['message'] = "dashboards delete";
	}
	
    // /dashboard/view
    if ($action == 'view' && $session['read'])
    {
      $dashboards = get_dashboards($session['userid']); 
	  
      //if ($format == 'json') $output['content'] = json_encode($dashboard);
      if ($format == 'html') $output['content'] = view("dashboards_view.php", array('dashboards'=>$dashboards));
    }

    return $output;
  }

?>
