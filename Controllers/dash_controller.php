<?php

  defined('EMONCMS_EXEC') or die('Restricted access');

  function dash_controller()
  {
    require "Models/dashboard_model.php";
    //require "Models/dashboards_model.php";
    global $session, $action, $format;

    $output['content'] = "";
    $output['message'] = "";

    if ($action == 'view' && $session['write']) // write access required
    {
      $id = intval($_GET['id']);
      $page = get_dashboard_id($session['userid'],$id);
      $apikey = get_apikey_read($session['userid']);
      $output['content'] = view("dash/dash_view.php", array("dashid"=>$id,"apikey_read"=>$apikey,'page'=>$page));
    }

    if ($action == 'edit' && $session['write']) // write access required
    {
      $id = intval($_GET['id']);
      $page = get_dashboard_id($session['userid'],$id);
      $apikey = get_apikey_read($session['userid']);
      $output['content'] = view("dash/dash_edit.php", array("dashid"=>$id,"apikey_read"=>$apikey,'page'=>$page));
    }

    return $output;
  }

?>
