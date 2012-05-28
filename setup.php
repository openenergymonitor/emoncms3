  
<h3>Emoncms Database Setup Script</h3>
<p><a href="index.php" >Continue to emoncms</a></p>
<?php

  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

  //=====================================================
  $runnable = TRUE; // ENABLE THIS ONCE TO FORCE UPDATE
  //=====================================================
  define('EMONCMS_EXEC', 1);
  require "Includes/db.php";
  $e = db_connect();
  if ($e == 4) $runnable = TRUE;

  if(!$runnable) {echo "to run script uncomment runnable"; die;}

  $shema = array();

  $schema['users'] = array(
    'id'=> array('type'=>'int NOT NULL AUTO_INCREMENT, PRIMARY KEY(id)'),
    'username'=> array('type'=>'varchar(30)'),
    'password'=> array('type'=>'varchar(64)'),
    'salt'=> array('type'=>'varchar(3)'),
    'apikey_write'=> array('type'=>'varchar(64)'),
    'apikey_read'=> array('type'=>'varchar(64)'),
    'lastlogin'=> array('type'=>'DATETIME'),
    'admin'=> array('type'=>'INT NOT NULL'),
    'lang'=> array('type'=>'varchar(2)')
  );

  $schema['input'] = array(
    'id'=> array('type'=>'int NOT NULL AUTO_INCREMENT, PRIMARY KEY(id)'),
    'userid'=> array('type'=>'text'),
    'name'=> array('type'=>'text'),
    'processList'=> array('type'=>'text'),
    'time'=> array('type'=>'DATETIME'),
    'value'=> array('type'=>'FLOAT'),
    'status'=> array('type'=>'int')
  );

  $schema['feeds'] = array(
    'id'=> array('type'=>'int NOT NULL AUTO_INCREMENT, PRIMARY KEY(id)'),
    'name'=> array('type'=>'text'),
    'tag'=> array('type'=>'text'),
    'time'=> array('type'=>'DATETIME'),
    'value'=> array('type'=>'FLOAT'),
    'status'=> array('type'=>'int'),
    'today'=> array('type'=>'FLOAT'),
    'yesterday'=> array('type'=>'FLOAT'),
    'week'=> array('type'=>'FLOAT'),
    'month'=> array('type'=>'FLOAT'),
    'year'=> array('type'=>'FLOAT'),
    'type'=> array('type'=>'int NOT NULL'),
    'datatype'=> array('type'=>'int NOT NULL')
  );

  $schema['feed_relation'] = array(
    'userid'=> array('type'=>'int'),
    'feedid'=> array('type'=>'int')
  );

  $schema['dashboard'] = array(
    'id'=> array('type'=>'int NOT NULL AUTO_INCREMENT, PRIMARY KEY(id,userid)'),
    'userid'=> array('type'=>'int'),
    'content'=> array('type'=>'text'),
    'name'=> array('type'=>"VARCHAR(30) DEFAULT 'no name'"),
    'description'=> array('type'=>"VARCHAR(255) DEFAULT 'no description'")
  );

  $schema['notify'] = array(
    'userid'=> array('type'=>'int'),
    'feedid'=> array('type'=>'int'),
    'onvalue'=> array('type'=>'FLOAT'),
    'onvalue_sent'=> array('type'=>'bool'),
    'oninactive'=> array('type'=>'bool'),
    'oninactive_sent'=> array('type'=>'bool'),
    'periodic'=> array('type'=>'bool')
  );

  $schema['notify_mail'] = array(
    'userid'=> array('type'=>'int'),
    'recipients'=> array('type'=>'text')
  );

  $schema['kwhdproc'] = array(
    'feedid'=> array('type'=>'int'),
    'time'=> array('type'=>'INT UNSIGNED'),
    'kwh'=> array('type'=>'FLOAT')
  );

  $schema['statistics'] = array(
    'userid'=> array('type'=>'int'),
    'uphits'=> array('type'=>'int'),
    'dnhits'=> array('type'=>'int'),
    'memory'=> array('type'=>'int')
  );

  $schema['multigraph'] = array(
    'userid'=> array('type'=>'int'),
    'feedlist'=> array('type'=>'text')
  );

  $out = "<table style='font-size:12px'><tr><th width='220'></th><th></th></tr>";

    while($table = key($schema)) 
    {

      if (table_exists($table))
      {
        $out .= "<tr><td>TABLE ".$table."</td><td>ok</td></tr>";
        //-----------------------------------------------------
        // Check table fields from schema
        //-----------------------------------------------------
        while($field = key($schema[$table])) 
        {
          if (field_exists($table,$field)) {
            $out .= "<tr><td>..".$field."</td><td>ok</td></tr>";
          } else {
            $type = $schema[$table][$field]['type'];
            $query = "ALTER TABLE `$table` ADD `$field` $type";
            echo $query;
            $out .= "<tr><td>..".$field."</td><td>added</td></tr>";
            db_query($query);
          }
          next($schema[$table]);
        }
        //-----------------------------------------------------

      } else {

        //-----------------------------------------------------
        // Create table from schema
        //-----------------------------------------------------
        $query = "CREATE TABLE ".$table." (";
        while($field = key($schema[$table])) 
        {
          $query .= $field." ".$schema[$table][$field]['type'];
          next($schema[$table]);
          if (key($schema[$table])) $query .= ", ";
        }
        $query .= ")";
        $out .= "<tr><td>TABLE ".$table."</td><td>created</td></tr>";
        db_query($query);                      // EXECUTE QUERY
        //-----------------------------------------------------
      }

      $out .= "<tr><td></td></tr>";
      next($schema);
    }
    $out .= "</table>";
    echo $out;
?>
