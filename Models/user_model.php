<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */
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
    $string = md5(uniqid(rand(), true));
    $salt = substr($string, 0, 3);
    $hash = hash('sha256', $salt . $hash);
    db_query("INSERT INTO users ( username, password, salt ) VALUES ( '$username' , '$hash' , '$salt' );"); 
  }

  function user_logon($username,$password)  
  {
    $result = db_query("SELECT id,password, salt FROM users WHERE username = '$username'");
    $userData = db_fetch_array($result);
    $hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );
    
    if ((db_num_rows($result) < 1) || ($hash != $userData['password']))
    {
      $_SESSION['valid'] = 0;
      $success = 0;
    }
    else
    {
      //this is a security measure
      session_regenerate_id(); 
      $_SESSION['valid'] = 1;
      $_SESSION['userid'] = $userData['id'];
      $success = 1;
    }
    return $success;
  }

  function user_logout()
  {
    $_SESSION['valid'] = 0;
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
