<?php

  defined('EMONCMS_EXEC') or die('Restricted access');

  function dash_controller()
  {
    require "Models/dashboard_model.php";
    require "Models/dashboards_model.php";
    global $session, $action, $format;

    $output['content'] = "";
    $output['message'] = "";

    if ($action == 'view' && $session['write']) // write access required
    {
      $id = intval($_GET['id']);
      $page = get_dashboard_id($session['userid'],$id);
      $apikey = get_apikey_read($session['userid']);
      $menu = build_dashboard_menu($session['userid'],"view");
      $output['content'] = view("dash/dash_view.php", array("dashid"=>$id,"apikey_read"=>$apikey,'page'=>$page, 'menu'=>$menu));
    }

    if ($action == 'run' && $session['write']) // write access required
    {
      $_SESSION['editmode'] = FALSE;
      $id = intval($_GET['id']);
      $page = get_dashboard_id($session['userid'],$id);
      $apikey = get_apikey_read($session['userid']);
      $menu = build_dashboard_menu($session['userid'],"view");
      $output['content'] = view("dash/dash_view.php", array("dashid"=>$id,"apikey_read"=>$apikey,'page'=>$page, 'menu'=>$menu));
      $output['menu'] = build_dashboardmenu($session['userid']);
    }

    if ($action == 'edit' && $session['write']) // write access required
    {
      $id = intval($_GET['id']);
      $dashboard = get_dashboard_id($session['userid'],$id);
      $apikey = get_apikey_read($session['userid']);
      $menu = build_dashboard_menu($session['userid'],"edit");
      $output['content'] = view("dash/dash_edit.php", array("dashid"=>$id,"apikey_read"=>$apikey,'dashboard'=>$dashboard, 'menu'=>$menu));
    }

    return $output;
  }

?>
