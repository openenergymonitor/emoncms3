<?php
  /*
    All Emoncms code is released under the GNU Affero General Public License.
    See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    USER CONTROLLER ACTIONS		ACCESS

    login?name=john&pass=test		all
    create?name=john&pass=test		all
    changepass?old=sdgs43&new=sdsg345   write
    newapiread				write
    newapiwrite				write
    logout				read
    getapiread				read
    getapiwrite 			write
    view				write
    setlang				write

  */

  // no direct access
  defined('EMONCMS_EXEC') or die('Restricted access');

  function user_controller()
  {
    global $session, $action,$format;

    $output['content'] = "";
    $output['message'] = "";

    //---------------------------------------------------------------------------------------------------------
    // Login user (PUBLIC ACTION)
    // http://yoursite/emoncms/user/login?name=john&pass=test
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'login')
    {
      $username = preg_replace('/[^\w\s-.]/','',$_POST["name"]);	// filter out all except for alphanumeric white space and dash
      $username = db_real_escape_string($username);

      $password = db_real_escape_string($_POST["pass"]);
      $result = user_logon($username,$password);
	  
      if ($result == 0)
      {
      	$output['message'] = _("Invalid username or password");
	  }
	  else
	  {
	  	$output['message'] = _("Welcome, you are now logged in");
      	if ($format == 'html'){
      		header("Location: ../dashboards/view");
		}
	  }
    }

    //---------------------------------------------------------------------------------------------------------
    // Create a user (PUBLIC ACTION) 
    // To disable addtional user creation remove or add higher priviledges to this
    // http://yoursite/emoncms/user/create?name=john&pass=test
    //---------------------------------------------------------------------------------------------------------
	elseif ($action == 'create')
    {
      $username = preg_replace('/[^\w\s-.]/','',$_POST["name"]);	// filter out all except for alphanumeric white space and dash
      $username = db_real_escape_string($username);

      $password = db_real_escape_string($_POST["pass"]);

      if (get_user_id($username) != 0)
      {
      	$output['message'] = _("Sorry username already exists");
	  }
	  elseif (strlen($username) < 4 || strlen($username) > 30)
      {
      	$output['message'] = _("Please enter a username that is 4 to 30 characters long")."<br/>";
	  }
	  elseif (strlen($password) < 4 || strlen($password) > 30)
	  {
	  	$output['message'] = _("Please enter a password that is 4 to 30 characters long")."<br/>";
	  }
	  
	  else
	  {
        create_user($username,$password);
        $result = user_logon($username,$password);
        $output['message'] = _("Your new account has been created");
        if ($format == 'html')
        {
        	header("Location: ../dashboards/view");
		}
        if ($_SESSION['write']){
        	create_user_statistics($_SESSION['userid']);
        }
      }
    }

    // http://yoursite/emoncms/user/changepass?old=sdgs43&new=sdsg345
	elseif ($action == 'changepass' && $_SESSION['write'])
	{
      $oldpass =  db_real_escape_string($_POST['oldpass']);
      $newpass =  db_real_escape_string($_POST['newpass']);
      if (change_password($_SESSION['userid'],$oldpass,$newpass))
	  {
	  	$output['message'] = _("Your password has been changed");
	  } 
	  else
	  {
	  	$output['message'] = _("Invalid old password");
	  }
    }

    //---------------------------------------------------------------------------------------------------------
    // NEW API READ
    // http://yoursite/emoncms/user/newapiread
    //---------------------------------------------------------------------------------------------------------
	elseif ($action == 'newapiread' && $session['write'])
	{
      $apikey_read = md5(uniqid(mt_rand(), true));
      set_apikey_read($session['userid'],$apikey_read);
      $output['message'] = _("New read apikey: ").$apikey_read;

      if ($format == 'html')
      {
      	header("Location: view");
	  }
    }

    //---------------------------------------------------------------------------------------------------------
    // NEW API WRITE
    // http://yoursite/emoncms/user/newapiwrite
    //---------------------------------------------------------------------------------------------------------
	elseif ($action == 'newapiwrite' && $session['write'])
	{
      $apikey_write = md5(uniqid(mt_rand(), true));
      set_apikey_write($session['userid'],$apikey_write);
      $output['message'] = _("New write apikey: ").$apikey_write;

      if ($format == 'html')
      {
      	header("Location: view");
	  }
    }

    //---------------------------------------------------------------------------------------------------------
    // Logout
    // http://yoursite/emoncms/user/logout
    //---------------------------------------------------------------------------------------------------------
	elseif ($action == 'logout' && $session['read'])
    { 
        if ($_POST['CSRF_token'] == $_SESSION['CSRF_token'])
        {
            user_logout();
            $output['message'] = _("You are logged out");
        }
        else
        {
            reset_CSRF_token();
            $output['message'] = _("Invalid token");
        }

		if ($format == 'html'){
       		header("Location: ../");
		}
    }

    //---------------------------------------------------------------------------------------------------------
    // GET API READ
    // http://yoursite/emoncms/user/getapiread
    //---------------------------------------------------------------------------------------------------------
	elseif ($action == 'getapiread' && $session['read'])
	{
      $apikey_read = get_apikey_read($session['userid']);
      $output = $apikey_read;
    }

    //---------------------------------------------------------------------------------------------------------
    // GET API WRITE
    // http://yoursite/emoncms/user/getapiwrite
    //---------------------------------------------------------------------------------------------------------
	elseif ($action == 'getapiwrite' && $session['write'])
	{
      $apikey_write = get_apikey_write($session['userid']);
      $output = $apikey_write;
    }

    //---------------------------------------------------------------------------------------------------------
    // GET USER
    // http://yoursite/emoncms/user/view
    //---------------------------------------------------------------------------------------------------------
	elseif ($action == 'view' && $session['write'])
    {
      $user = get_user($session['userid']);
      $stats = get_statistics($session['userid']);

      if ($format == 'json') $output['content'] = json_encode($user);
      if ($format == 'html') $output['content'] = view_lang("user_view.php", array('user' => $user, 'stats'=>$stats));
    }

    //---------------------------------------------------------------------------------------------------------
    // SET USERS DEFAULT LANGUAGE
    // http://yoursite/emoncms/user/setlang
    //---------------------------------------------------------------------------------------------------------
	elseif ($action == 'setlang' && $session['write'])
    {
      set_user_lang($session['userid'],$session['lang']);
      if ($format == 'html')
      {
      	header("Location: view");
	  }
    }

    return $output;
  }

?>
