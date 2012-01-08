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
      if ($result == 0) $output = "invalid username or password"; else $output = "login successful";

      if ($format == 'html') header("Location: ../dashboard/view");
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

      if (get_user_id($username)!=0) $output = "username already exists";
      if (strlen($password) < 4 || strlen($password) > 30) $output = "password must be 4 to 30 characters<br/>";
      if (strlen($username) < 4 || strlen($username) > 30) $output = "username must be 4 to 30 characters<br/>";
      if (!$output) {
        create_user($username,$password);
        $result = user_logon($username,$password);
        $output = "user created";
        if ($format == 'html') header("Location: ../dashboard/view");
      } else { echo "there was a problem?"; }
    }

    //---------------------------------------------------------------------------------------------------------
    // NEW API READ
    // http://yoursite/emoncms/user/newapiread
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'newapiread' && $session['write']) {
      $apikey_read = md5(uniqid(mt_rand(), true));
      set_apikey_read($session['userid'],$apikey_read);
      $output = "New read apikey: ".$apikey_read;

      if ($format == 'html') header("Location: view");
    }

    //---------------------------------------------------------------------------------------------------------
    // NEW API WRITE
    // http://yoursite/emoncms/user/newapiwrite
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'newapiwrite' && $session['write']) {
      $apikey_write = md5(uniqid(mt_rand(), true));
      set_apikey_write($session['userid'],$apikey_write);
      $output = "New write apikey: ".$apikey_write;

      if ($format == 'html') header("Location: view");
    }

    //---------------------------------------------------------------------------------------------------------
    // Logout
    // http://yoursite/emoncms/user/logout
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'logout' && $session['read'])
    { 
      user_logout(); 
      $output = "logout"; 

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

      if ($format == 'json') $output = json_encode($user);
      if ($format == 'html') $output = view("user_view.php", array('user' => $user));
    }

    return $output;
  }

?>
