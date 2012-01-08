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

  */

  function dashboard_controller()
  {
    require "Models/dashboard_model.php";
    global $session, $action, $format;

    // /dashboard/set?content=<h2>HelloWorld</h2>
    if ($action == 'set' && $session['write']) // write access required
    {
      $content = $_POST['content'];
      if (!$content) $content = $_GET['content'];

      // IMPORTANT: if you get problems with characters being removed check this line:
      $content = preg_replace('/[^\w\s-.<>?",;:=&\/]/','',$content);	// filter out all except characters usually used

      $content = db_real_escape_string($content);

      set_dashboard($session['userid'],$content);
      $output = "dashboard set";
    }

    // /dashboard/view
    if ($action == 'view' && $session['read'])
    {
      $dashboard = get_dashboard($session['userid']);

      if ($format == 'json') $output = json_encode($dashboard);
      if ($format == 'html') $output = view("dashboard_view.php", array('page'=>$dashboard));
    }

    return $output;
  }

?>
