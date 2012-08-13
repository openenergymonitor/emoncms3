<?php 

  // no direct access
  defined('EMONCMS_EXEC') or die('Restricted access');

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
    global $mysqli, $server, $username, $password, $database;
    
    // ERROR CODES
    // 1: success!
    // 3: database settings are wrong 
    // 4: launch setup.php
 
    // Lets try to connect
    $mysqli = new mysqli($server, $username, $password, $database);
    
    if ($mysqli->connect_error) 
      return 3;
    else                     
      if (db_query('SELECT id FROM users LIMIT 1'))
        return 1;
      else
        return 4;
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
    $result = db_query('SELECT DATABASE()');
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
  
  function getdatabaseversion()
  {
    $result = db_query('SELECT dbversion FROM e3_globals;');
    $row = db_fetch_array($result);
    return $row['dbversion']; 
  }

  function setdatabaseversion($dbversion)
  {
    db_query("UPDATE e3_globals SET dbversion=".$dbversion);  
  }
  
?>