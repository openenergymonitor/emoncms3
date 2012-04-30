<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    DASHBOARD ACTIONS		ACCESS
    set				write
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
    if ($action == 'set' && $session['write']) // write access required
    {
      $content = $_POST['content'];
      if (!$content) $content = $_GET['content'];

      // IMPORTANT: if you get problems with characters being removed check this line:
      $content = preg_replace('/[^\w\s-.#<>?",;:=&\/%]/','',$content);	// filter out all except characters usually used

      $content = db_real_escape_string($content);

      set_dashboard($session['userid'],$content);
      $output['message'] = "dashboards set";
    }

    if ($action == 'delete' && $session['write']) // write access required
    {
      delete_dashboard($session['userid']);
      $output['message'] = "dashboards delete";
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
