<?php

  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

  // dashboard/new				New dashboard
  // dashboard/delete POST: id=			Delete dashboard
  // dashboard/list 				List dashboards
  // dashboard/view?id=1			View and run dashboard (id)
  // dashboard/edit?id=1			Edit dashboard (id) with the draw editor
  // dashboard/ckeditor?id=1			Edit dashboard (id) with the CKEditor
  // dashboard/set POST				Set dashboard
  // dashboard/setconf POST 			Set dashboard configuration

  defined('EMONCMS_EXEC') or die('Restricted access');

  function dashboard_controller()
  {
    require "Models/dashboard_model.php";
    global $path, $session, $action, $subaction, $format;

    $output['content'] = "";
    $output['message'] = "";

    //----------------------------------------------------------------------------------------------------------------------
    // New dashboard
    //----------------------------------------------------------------------------------------------------------------------
    if ($action == 'new' && $session['write']) // write access required
    {
      $dashid = new_dashboard($session['userid']);
      $output['message'] = _("dashboards new");

      if ($format == 'html')
      {
    	header("Location: ../dashboard/edit?id=".$dashid);
      }
    }

    //----------------------------------------------------------------------------------------------------------------------
    // Delete dashboard
    //----------------------------------------------------------------------------------------------------------------------
    elseif ($action == 'delete' && $session['write']) // write access required
    {
      $output['message'] = delete_dashboard($session['userid'], intval($_POST["id"]));
    }

    //----------------------------------------------------------------------------------------------------------------------
    // List dashboards
    //----------------------------------------------------------------------------------------------------------------------
    elseif ($action == 'thumb' && $session['read'])
    {
      $_SESSION['editmode'] = TRUE;
      if ($session['read']) $apikey = get_apikey_read($session['userid']);
      $dashboards = get_dashboard_list($session['userid']); 
      $menu = build_dashboard_menu($session['userid'],"edit");
      if ($format == 'html') $output['content'] = view("dashboard/dashboard_thumb_view.php", array('apikey'=>$apikey, 'dashboards'=>$dashboards,'menu'=>$menu));
    }

    //----------------------------------------------------------------------------------------------------------------------
    // View or run dashboard (id)
    //----------------------------------------------------------------------------------------------------------------------
    elseif (($action == 'run' || $action == 'view' ) && $session['read']) // write access required
    {
      $id = intval($_GET['id']);
      $alias = preg_replace('/[^a-z]/','',$subaction);
      
      if ($id) 
      {     
        // If a dashboard id is given we get the coresponding dashboard
        $dashboard = get_dashboard_id($session['userid'],$id);
      }
      elseif ($alias)
      {
        $dashboard = get_dashboard_alias($session['userid'],$alias);
      }
      else
      {  
        // Otherwise we get the main dashboard
        $dashboard = get_main_dashboard($session['userid']);
      }

      // URL ENCODE...
      if ($format == 'json') 
      {
        $output['content'] = urlencode($dashboard['content']);
        return $output;
      }

      $menu = build_dashboard_menu($session['userid'], $action);
           
      if ($action=="run")
      {
        // In run mode dashboard menu becomes the main menu
        $_SESSION['editmode'] = FALSE;
        $output['menu'] =  '<div class="nav-collapse collapse">';
        $output['menu'] .= '<ul class="nav">'.$menu.'</ul>';
        $output['menu'] .= "<ul class='nav pull-right'><li><a href='".$GLOBALS['path']."user/logout'>"._("Logout")."</a></li></ul></div>";
      }
      else
      {
        // Otherwise in view mode the dashboard menu is an additional grey menu
        $_SESSION['editmode'] = TRUE;
        $output['submenu'] = view("dashboard/dashboard_menu.php", array('id'=>$dashboard['id'], 'menu'=>$menu, 'type'=>"view"));
      }
      
      //if ($dashboard_arr) 
      //{
        $apikey = get_apikey_read($session['userid']);
        $output['content'] = view("dashboard/dashboard_view.php", array('dashboard'=>$dashboard, "apikey_read"=>$apikey));

      //}
      //else
      //{
      //  $output['content'] = view("dashboard_run_errornomain.php",array());	
      //}
    }

    //----------------------------------------------------------------------------------------------------------------------
    // Edit dashboard (id) with the draw editor
    //----------------------------------------------------------------------------------------------------------------------
    elseif ($action == 'edit' && $session['write']) // write access required
    {
      $id = intval($_GET['id']);
      $alias = preg_replace('/[^a-z]/','',$subaction);

      if ($id) 
      {     
        // If a dashboard id is given we get the coresponding dashboard
        $dashboard = get_dashboard_id($session['userid'],$id);
      }
      elseif ($alias)
      {
        $dashboard = get_dashboard_alias($session['userid'],$alias);
      }
      else
      {  
        // Otherwise we get the main dashboard
        $dashboard = get_main_dashboard($session['userid']);
      }

      $apikey = get_apikey_read($session['userid']);
      $menu = build_dashboard_menu($session['userid'],"edit");
      $output['content'] = view("dashboard/dashboard_edit_view.php", array('dashboard'=>$dashboard, "apikey_read"=>$apikey));
      $output['submenu'] = view("dashboard/dashboard_menu.php", array('id'=>$dashboard['id'], 'menu'=>$menu, 'type'=>"edit"));
    }

    //----------------------------------------------------------------------------------------------------------------------
    // Edit dashboard (id) with the CKEditor
    //----------------------------------------------------------------------------------------------------------------------
    elseif ($action == 'ckeditor' && $session['write'])
    {
      $id = intval($_GET['id']);
      $alias = preg_replace('/[^a-z]/','',$subaction);

      if ($id) 
      {     
        // If a dashboard id is given we get the coresponding dashboard
        $dashboard = get_dashboard_id($session['userid'],$id);
      }
      elseif ($alias)
      {
        $dashboard = get_dashboard_alias($session['userid'],$alias);
      }
      else
      {  
        // Otherwise we get the main dashboard
        $dashboard = get_main_dashboard($session['userid']);
      }

      $menu = build_dashboard_menu($session['userid'],"ckeditor");
      $output['content'] = view("dashboard/dashboard_ckeditor_view.php",array('dashboard' => $dashboard,'menu'=>$menu));
      $output['submenu'] = view("dashboard/dashboard_menu.php", array('id'=>$dashboard['id'], 'menu'=>$menu, 'type'=>"ckeditor"));
    }

    //----------------------------------------------------------------------------------------------------------------------
    // SET dashboard
    // dashboard/set?content=<h2>HelloWorld</h2>
    //----------------------------------------------------------------------------------------------------------------------
    if ($action == 'set' && $session['write']) // write access required
    {
      $content = $_POST['content'];
      if (!$content) $content = $_GET['content'];

      $id = intval($_POST['id']);
      if (!$id) $id = intval($_GET['id']);

      // IMPORTANT: if you get problems with characters being removed check this line:
      $content = preg_replace('/[^\w\s-.#<>?",;:=&\/%]/','',$content);	// filter out all except characters usually used

      $content = db_real_escape_string($content);

      set_dashboard_content($session['userid'],$content,$id);
      $output['message'] = _("dashboard set");
    }

    //----------------------------------------------------------------------------------------------------------------------
    // SET dashboard configuration
    //----------------------------------------------------------------------------------------------------------------------
    elseif ($action == 'setconf' && $session['write']) // write access required
    {
      $id = intval($_POST['id']);
      $name = preg_replace('/[^\w\s-]/','',$_POST['name']);
      $alias = preg_replace('/[^a-z]/','',$_POST['alias']);
      $description = preg_replace('/[^\w\s-]/','',$_POST['description']);
      $main = intval($_POST['main']);
      set_dashboard_conf($session['userid'],$id,$name,$alias,$description,$main);
      $output['message'] = _("dashboard set configuration");
    }

    return $output;
  }

?>
