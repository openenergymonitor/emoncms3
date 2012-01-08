<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

  function user_apikey_session_control($apikey_in)
  {

    //----------------------------------------------------
    // Check for apikey login
    //----------------------------------------------------
    $apikey_in = db_real_escape_string($apikey_in);
    $userid = get_apikey_read_user($apikey_in);
    if ($userid!=0) 
    {
      session_regenerate_id(); 
      $session['userid'] = $userid;
      $session['read'] = 1;
      $session['write'] = 0;   
    }

    $userid = get_apikey_write_user($apikey_in);
    if ($userid!=0) 
    {
      session_regenerate_id(); 
      $session['userid'] = $userid;
      $session['read'] = 1;
      $session['write'] = 1;
  
    }
    //----------------------------------------------------
    return $session;
  }


  function get_user($userid)
  {
    $result = db_query("SELECT * FROM users WHERE id=$userid");
    if ($result)
    {
      $row = db_fetch_array($result);
      $user = array('username'=>$row['username'],'apikey_read'=>$row['apikey_read'],'apikey_write'=>$row['apikey_write']);
    }
    return $user;
  }

  function get_apikey_read($userid)
  {
    $result = db_query("SELECT apikey_read FROM users WHERE id=$userid");
    if ($result)
    {
      $row = db_fetch_array($result);
      $apikey = $row['apikey_read'];
    }
    return $apikey;
  }

  function get_apikey_write($userid)
  {
    $result = db_query("SELECT apikey_write FROM users WHERE id=$userid");
    if ($result)
    {
      $row = db_fetch_array($result);
      $apikey = $row['apikey_write'];
    }
    return $apikey;
  }
 
  function set_apikey_read($userid,$apikey)
  {
    db_query("UPDATE users SET apikey_read = '$apikey' WHERE id='$userid'");
  }

  function set_apikey_write($userid,$apikey)
  {
    db_query("UPDATE users SET apikey_write = '$apikey' WHERE id='$userid'");
  }

  function get_apikey_read_user($apikey)
  {
    $result = db_query("SELECT id FROM users WHERE apikey_read='$apikey'");
    $row = db_fetch_array($result);
    return $row['id'];
  }

  function get_apikey_write_user($apikey)
  {
    $result = db_query("SELECT id FROM users WHERE apikey_write='$apikey'");
    $row = db_fetch_array($result);
    return $row['id'];
  }

  function create_user($username,$password)
  {
    $hash = hash('sha256', $password);
    $string = md5(uniqid(mt_rand(), true));
    $salt = substr($string, 0, 3);
    $hash = hash('sha256', $salt . $hash);

    $apikey_write = md5(uniqid(mt_rand(), true));
    $apikey_read = md5(uniqid(mt_rand(), true));

    db_query("INSERT INTO users ( username, password, salt ,apikey_read, apikey_write ) VALUES ( '$username' , '$hash' , '$salt', '$apikey_read', '$apikey_write' );"); 
  }

  function user_logon($username,$password)  
  {
    $result = db_query("SELECT id,password, salt FROM users WHERE username = '$username'");
    $userData = db_fetch_array($result);
    $hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );
    
    if ((db_num_rows($result) < 1) || ($hash != $userData['password']))
    {
      $_SESSION['read'] = 0;
      $_SESSION['write'] = 0;
      $success = 0;
    }
    else
    {
      //this is a security measure
      session_regenerate_id(); 
      $_SESSION['userid'] = $userData['id'];
      $_SESSION['read'] = 1;
      $_SESSION['write'] = 1;
      $success = 1;
    }
    return $success;
  }

  function user_logout()
  {
    $_SESSION['read'] = 0;
    $_SESSION['write'] = 0;
    session_destroy();
  }

  function get_user_id($username)
  {
    $result = db_query("SELECT id FROM users WHERE username = '$username';");
    $row = db_fetch_array($result);
    return $row['id'];
  }

  function get_user_name($id)
  {
    $result = db_query("SELECT username FROM users WHERE id = '$id';");
    $row = db_fetch_array($result);
    return $row['username'];
  }

?>
