<?php
/*
 All Emoncms code is released under the GNU Affero General Public License.
 See COPYRIGHT.txt and LICENSE.txt.

 ---------------------------------------------------------------------
 Emoncms - open source energy visualisation
 Part of the OpenEnergyMonitor project:
 http://openenergymonitor.org
 */
?>

<!DOCTYPE html>
<html lang="en">  
<head>
  <meta charset="UTF-8" />
  <title>Emoncms3 setup</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="Emoncms3 script setup" />
</head>	
<body>		
  <h1><?php echo _("Emoncms database setup script") ?></h1>
  <p>
    <a href="index.php" ><?php echo _("Continue to emoncms") ?></a>
  </p>
<?php


//=====================================================
// $runnable = TRUE;
// ENABLE THIS ONCE TO FORCE UPDATE
//=====================================================
define('EMONCMS_EXEC', 1);
require "Includes/process_settings.php";
require "Includes/db.php";
switch(db_connect()) {
  case 0: break;
  case 1: break;  
  case 3: show_dbsettingserror_message(); die ;
  case 4: $runnable = true; break;
}

if (!$runnable)
{
  echo _("to run script uncomment runnable");
  die ;
}

$schema = array();

$schema['users'] = array(
  'id' => array('type' => 'int(11)', 'Null'=>'NO', 'Key'=>'PRI', 'Extra'=>'auto_increment'),
  'username' => array('type' => 'varchar(30)'),
  'email' => array('type' => 'varchar(30)'),
  'password' => array('type' => 'varchar(64)'),
  'salt' => array('type' => 'varchar(3)'),
  'apikey_write' => array('type' => 'varchar(64)'),
  'apikey_read' => array('type' => 'varchar(64)'),
  'lastlogin' => array('type' => 'datetime'),
  'admin' => array('type' => 'int(11)', 'Null'=>'NO'),
  'lang' => array('type' => 'varchar(5)'),
  'timeoffset' => array('type' => 'int(11)'),
  'settingsarray' => array('type' => 'text')
);

$schema['input'] = array(
  'id' => array('type' => 'int(11)', 'Null'=>'NO', 'Key'=>'PRI', 'Extra'=>'auto_increment'),
  'userid' => array('type' => 'text'),
  'name' => array('type' => 'text'),
  'nodeid' => array('type' => 'int(11)'),
  'processList' => array('type' => 'text'),
  'time' => array('type' => 'datetime'),
  'value' => array('type' => 'float')
);

$schema['feeds'] = array(
  'id' => array('type' => 'int(11)', 'Null'=>'NO', 'Key'=>'PRI', 'Extra'=>'auto_increment'),
  'name' => array('type' => 'text'),
  'tag' => array('type' => 'text'),
  'time' => array('type' => 'datetime'),
  'value' => array('type' => 'float'),
  'status' => array('type' => 'int(11)'),
  'today' => array('type' => 'float'),
  'yesterday' => array('type' => 'float'),
  'week' => array('type' => 'float'),
  'month' => array('type' => 'float'),
  'year' => array('type' => 'float'),
  'datatype' => array('type' => 'int(11)', 'Null'=>'NO')
);

$schema['feed_relation'] = array(
  'userid' => array('type' => 'int(11)'),
  'feedid' => array('type' => 'int(11)')
);

$schema['dashboard'] = array(
  'id' => array('type' => 'int(11)', 'Null'=>'NO', 'Key'=>'PRI', 'Extra'=>'auto_increment'),
  'userid' => array('type' => 'int(11)'),
  'content' => array('type' => 'text'),
  'name' => array('type' => "varchar(30)", 'default'=>'no name'),
  'alias' => array('type' => "varchar(10)"),
  'description' => array('type' => "varchar(255)", 'default'=>'no description'),
  'main' => array('type' => 'tinyint(1)', 'default'=>false),
  'public' => array('type' => 'tinyint(1)', 'default'=>false),
  'published' => array('type' => 'tinyint(1)', 'default'=>false),
  'showdescription' => array('type' => 'tinyint(1)', 'default'=>false)  
);

$schema['notify'] = array(
  'userid' => array('type' => 'int(11)'),
  'feedid' => array('type' => 'int(11)'),
  'onvalue' => array('type' => 'float'),
  'onvalue_sent' => array('type' => 'tinyint(1)'),
  'oninactive' => array('type' => 'tinyint(1)'),
  'oninactive_sent' => array('type' => 'tinyint(1)'),
  'periodic' => array('type' => 'tinyint(1)')
);

$schema['notify_mail'] = array(
  'userid' => array('type' => 'int(11)'),
  'recipients' => array('type' => 'text')
);

$schema['kwhdproc'] = array(
  'feedid' => array('type' => 'int(11)'),
  'time' => array('type' => 'int(10) unsigned'),
  'kwh' => array('type' => 'float')
);

$schema['statistics'] = array(
  'userid' => array('type' => 'int(11)'),
  'uphits' => array('type' => 'int(11)'),
  'dnhits' => array('type' => 'int(11)'),
  'memory' => array('type' => 'int(11)')
);

$schema['multigraph'] = array(
  'userid' => array('type' => 'int(11)'),
  'feedlist' => array('type' => 'text')
);

$schema['nodes'] = array(
  'nodeid' => array('type' => 'int(11)', 'Null'=>'NO', 'Key'=>'PRI', 'Extra'=>'auto_increment'),
  'name' => array('type' => "varchar(30)", 'default'=>'no name'),
  'description' => array('type' => "varchar(255)", 'default'=>'no description')
);

$schema['importqueue'] = array(
  'queid' => array('type' => 'int(11)', 'Null'=>'NO', 'Key'=>'PRI', 'Extra'=>'auto_increment'),
  'baseurl' => array('type' => 'text'),
  'apikey' => array('type' => 'text'),
  'feedid' => array('type' => 'int(11)'),
  'localfeedid' => array('type' => 'int(11)')
);

//$schema['e3_globals'] = array(
//	'dbversion' => array('type' => 'int unsigned not null default 2012062900')
//);

$out = "<table style='font-size:12px'><tr><th width='220'></th><th></th></tr>";

while ($table = key($schema))
{

  if (table_exists($table))
  {
    $out .= "<tr><td>TABLE " . $table . "</td><td>ok</td></tr>";
    //-----------------------------------------------------
    // Check table fields from schema
    //-----------------------------------------------------
    while ($field = key($schema[$table]))
    {
      $type = $schema[$table][$field]['type'];
      $null = $schema[$table][$field]['Null']; if (!$null) $null = "YES";
      $key = $schema[$table][$field]['Key'];
      $default = $schema[$table][$field]['default'];
      $extra = $schema[$table][$field]['Extra'];

      $out .= "<tr>";
      if (field_exists($table, $field))
      {
        $out .= "<td>.." . $field . "</td><td>ok</td>";
      }
      else
      {

        $query = "ALTER TABLE `$table` ADD `$field` $type";
        echo $query;
        $out .= "<tr><td>.." . $field . "</td><td>added</td></tr>";
        db_query($query);
      }

      $result = db_query("DESCRIBE $table $field");
      $array = db_fetch_array($result);

      $out .= "<td>";

      $query = "";

      if ($array['Type']!=$type) { $out .= "Type: $type, "; $query .= ";"; }
      if ($array['Default']!=$default) { $out .= "Default: $default, "; $query .= " Default '$default'"; }
      if ($array['Null']!=$null && $null=="NO") { $out .= "Null: $null, "; $query .= " not null"; }
      if ($array['Extra']!=$extra && $extra=="auto_increment") { $out .= "Extra: $extra"; $query .= " auto_increment"; }
      if ($array['Key']!=$key && $key=="PRI") { $out .= "Key: $key, "; $query .= " primary key"; }

      $out .= "</td>";

      if ($query) $query = "ALTER TABLE $table MODIFY $field $type".$query;
      $out .= "<td>$query</td>";
      if ($query) db_query($query);

      $out .= "</tr>";

      next($schema[$table]);
    }
    //-----------------------------------------------------

  }
  else
  {

    //-----------------------------------------------------
    // Create table from schema
    //-----------------------------------------------------
    $query = "CREATE TABLE " . $table . " (";
    while ($field = key($schema[$table]))
    {
      $type = $schema[$table][$field]['type'];
      $null = $schema[$table][$field]['Null']; if (!$null) $null = "YES";
      $key = $schema[$table][$field]['Key'];
      $default = $schema[$table][$field]['default'];
      $extra = $schema[$table][$field]['Extra'];

      $query .= $field;
      $query .= " $type";
      if ($default) $query .= " Default '$default'";
      if ($null=="NO") $query .= " not null";
      if ($extra) $query .= " auto_increment";
      if ($key) $query .= " primary key";

      next($schema[$table]);
      if (key($schema[$table]))
      {
        $query .= ", ";
      }
    }
    $query .= ")";
    $out .= "<tr><td>TABLE " . $table . "</td><td>created</td></tr>";

     
    if ($query) db_query($query);
    // EXECUTE QUERY
    //-----------------------------------------------------
  }

  $out .= "<tr><td></td></tr>";
  next($schema);
}

$out .= "</table>";

// Test for feed conversion requirement
$runconv = false;
require "Models/feed_model.php";
$feeds = get_all_feeds();
foreach ($feeds as $feed)
{
  $feedname = "feed_" . trim($feed['id']) . "";
  $result = db_query("DESCRIBE $feedname time");
  $row = db_fetch_array($result);
  if ($row['Type'] == "datetime") $runconv = true;
}
if ($runconv==true)  echo "<p>You have feeds that need converting from datetime format to indexed timestamp. This improves performance. Its best to backup your data before conversion, then once your ready run: emoncms3/conv.php</p>";

echo $out;

/*
  This code may be used in future to do more complex upgrade procedures

    db_query("INSERT INTO e3_globals SET dbversion=2012062900;");
    upgradedatabase();
  }
	
function upgradedatabase() {
  echo "Installed database version";
  $dbversion = getdatabaseversion();
  echo "<center>".$dbversion."</center>";
  echo "Upgrading database<br>";
  startupgrade($dbversion);
  echo "<br>Upgrade done";
}

function startupgrade($dbversion)
{ 
  // add here upgrade points
  if ($dbversion < 2012063000) upgrade2012063000();	
} 

// upgrade points 
// yyyymmddxx
// change lang field size from 2 to 5 to support en_EN, es_ES,... format
function upgrade2012063000()
{
  echo "Upgrade 2012063000";
  db_query("ALTER TABLE users MODIFY lang varchar(5);");
  setdatabaseversion("2012063000");
}
*/
?>
</body>
</html>
