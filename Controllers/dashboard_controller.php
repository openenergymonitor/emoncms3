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
   	setconf			write
    view			read

  */

  // no direct access
  defined('EMONCMS_EXEC') or die('Restricted access');

  function dashboard_controller()
  {
    require "Models/dashboard_model.php";
    global $session, $action, $format;

    $output['content'] = "";
    $output['message'] = "";

    // /dashboard/set?content=<h2>HelloWorld</h2>
    if ($action == 'set' && $session['write']) // write access required
    {
      $content = $_POST['content'];
      if (!$content) $content = $_GET['content'];

      $id = $_POST['id'];
      if (!$id) $id = $_GET['id'];

      // IMPORTANT: if you get problems with characters being removed check this line:
      $content = preg_replace('/[^\w\s-.#<>?",;:=&\/%]/','',$content);	// filter out all except characters usually used

      $content = db_real_escape_string($content);

      set_dashboard($session['userid'],$content,$id);
      $output['message'] = "dashboard set";
    }

    if ($action == 'setconf' && $session['write']) // write access required
    {
     // $output['message'] = "dashboard setconf";
     set_dashboard_conf($session['userid'],$_POST['id'],$_POST['name'],$_POST['description']);
	 $output['message'] = "dashboard set configuration";
    }
	
    // /dashboard/view
    if ($action == 'view' && $session['read'])
    {
   		if ($_GET['id']) 
   			$dashboard_arr = get_dashboard_id($session['userid'],$_GET['id']);
		else
      		$dashboard = get_dashboard($session['userid']);

      if ($format == 'json') $output['content'] = json_encode($dashboard);
	  
      if ($format == 'html') $output['content'] = view("dashboard_view.php",
      	array(
      		'page'=>$dashboard_arr['ds_content'],
      		'ds_name'=>$dashboard_arr['ds_name'],
      		'ds_description'=>$dashboard_arr['ds_description'])
		);
      	       
    }

    return $output;
  }

?>
