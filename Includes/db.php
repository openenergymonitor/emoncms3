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
    $ret = $GLOBALS['mysqli']->query($query);
    if ($ret == false) {echo $GLOBALS['mysqli']->error;}
    return $ret;
  }

  function db_fetch_array($result)
  {
  	$ret = $result->fetch_array();
    if ($ret == false) {echo $GLOBALS['mysqli']->error;}
    return $ret;
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

  function table_exists($tablename)
  {
    $result = db_query("SELECT DATABASE()");
    $row = db_fetch_array($result);
    $database = $row[0];

    $result = db_query("
        SELECT COUNT(*) AS count 
        FROM information_schema.tables 
        WHERE table_schema = '$database' 
        AND table_name = '$tablename'
    ");

    $row = db_fetch_array($result);
    return $row[0];
  }

  function field_exists($tablename,$field)
  {
    $field_exists = 0;
    $result = db_query("SHOW COLUMNS FROM $tablename");
    while( $row = db_fetch_array($result) ){
      if ($row['Field']==$field) $field_exists = 1;
    }
    return $field_exists;
  }



?>
