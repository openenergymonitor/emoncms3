<?php

  defined('EMONCMS_EXEC') or die('Restricted access');

  function dash_controller()
  {
    //require "Models/dashboards_model.php";
    global $session, $action, $format;

    $output['content'] = "";
    $output['message'] = "";

    if ($action == 'edit' && $session['write']) // write access required
    {
      $apikey = get_apikey_read($session['userid']);
      $output['content'] = view("dash/dash_view.php", array("apikey_read"=>$apikey));
    }

    return $output;
  }

?>
