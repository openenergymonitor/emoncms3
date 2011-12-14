<?php 
  $mysqli = 0;

  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

  function db_connect()
  {
    global $mysqli;
    // ERROR CODES
    // 1: success!
    // 2: no settings.php file
    // 3: database settings are wrong	
  
    $success = 1;	

    if(!file_exists(dirname(__FILE__)."/settings.php"))
    {
      $success = 2;
    }
    else
    {
      require_once ('settings.php');
      $mysqli = new mysqli($server, $username, $password, $database);
      if ($mysqli->connect_error) $success = 3;
    }

    if ($success == 1){ 
      $result = db_query("SELECT * FROM users");
      if (!$result) $success = 4;
    }

    return $success;
  }

  function db_query($query)
  {
    return $GLOBALS['mysqli']->query($query);
  }

  function db_fetch_array($result)
  {
    return $result->fetch_array();
  }

  function db_num_rows($result)
  {
    return $result->num_rows;
  }

  function db_real_escape_string($string)
  {
    return $GLOBALS['mysqli']->real_escape_string($string);
  }

  function db_insert_id()
  {
    return $GLOBALS['mysqli']->insert_id;
  }


?>
