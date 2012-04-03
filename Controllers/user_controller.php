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

  */
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
      $username = preg_replace('/[^\w\s-.]/','',$_GET["name"]);	// filter out all except for alphanumeric white space and dash
      $username = db_real_escape_string($username);

      $password = db_real_escape_string($_GET['pass']);
      $result = user_logon($username,$password);
      if ($result == 0) $output['message'] = "Invalid username or password"; else { $output['message'] = "Welcome, you are now logged in";

      if ($format == 'html') header("Location: ../dashboard/view");}
    }

    //---------------------------------------------------------------------------------------------------------
    // Create a user (PUBLIC ACTION) 
    // To disable addtional user creation remove or add higher priviledges to this
    // http://yoursite/emoncms/user/create?name=john&pass=test
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'create')
    {
      $username = preg_replace('/[^\w\s-.]/','',$_GET["name"]);	// filter out all except for alphanumeric white space and dash
      $username = db_real_escape_string($username);

      $password = db_real_escape_string($_GET['pass']);

      if (get_user_id($username)!=0) $output['message'] = "Sorry username already exists";
      if (strlen($password) < 4 || strlen($password) > 30) $output['message'] = "Please enter a password that is 4 to 30 characters long<br/>";
      if (strlen($username) < 4 || strlen($username) > 30) $output['message'] = "Please enter a username that is 4 to 30 characters long<br/>";
      if (!$output['message']) {
        create_user($username,$password);
        $result = user_logon($username,$password);
        $output['message'] = "Your new account has been created";
        if ($format == 'html') header("Location: ../dashboard/view");

        if ($_SESSION['write']) create_user_statistics($_SESSION['userid']);
      }
    }

    // http://yoursite/emoncms/user/changepass?old=sdgs43&new=sdsg345
    if ($action == 'changepass' && $_SESSION['write']) {
      $oldpass =  db_real_escape_string($_GET['oldpass']);
      $newpass =  db_real_escape_string($_GET['newpass']);
      if (change_password($_SESSION['userid'],$oldpass,$newpass)) $output['message'] = "Your password has been changed"; else $output['message'] = "Invalid old password";
    }

    //---------------------------------------------------------------------------------------------------------
    // NEW API READ
    // http://yoursite/emoncms/user/newapiread
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'newapiread' && $session['write']) {
      $apikey_read = md5(uniqid(mt_rand(), true));
      set_apikey_read($session['userid'],$apikey_read);
      $output['message'] = "New read apikey: ".$apikey_read;

      if ($format == 'html') header("Location: view");
    }

    //---------------------------------------------------------------------------------------------------------
    // NEW API WRITE
    // http://yoursite/emoncms/user/newapiwrite
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'newapiwrite' && $session['write']) {
      $apikey_write = md5(uniqid(mt_rand(), true));
      set_apikey_write($session['userid'],$apikey_write);
      $output['message'] = "New write apikey: ".$apikey_write;

      if ($format == 'html') header("Location: view");
    }

    //---------------------------------------------------------------------------------------------------------
    // Logout
    // http://yoursite/emoncms/user/logout
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'logout' && $session['read'])
    { 
      user_logout(); 
      $output['message'] = "You are logged out"; 

      if ($format == 'html') header("Location: ../");
    }

    //---------------------------------------------------------------------------------------------------------
    // GET API READ
    // http://yoursite/emoncms/user/getapiread
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'getapiread' && $session['read']) {
      $apikey_read = get_apikey_read($session['userid']);
      $output = $apikey_read;
    }

    //---------------------------------------------------------------------------------------------------------
    // GET API WRITE
    // http://yoursite/emoncms/user/getapiwrite
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'getapiwrite' && $session['write']) {
      $apikey_write = get_apikey_write($session['userid']);
      $output = $apikey_write;
    }

    //---------------------------------------------------------------------------------------------------------
    // GET USER
    // http://yoursite/emoncms/user/view
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'view' && $session['write']) {
      $user = get_user($session['userid']);
      $stats = get_statistics($session['userid']);

      if ($format == 'json') $output['content'] = json_encode($user);
      if ($format == 'html') $output['content'] = view("user_view.php", array('user' => $user, 'stats'=>$stats));
    }

    return $output;
  }

?>
