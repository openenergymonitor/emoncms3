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
  //$runnable = TRUE; // ENABLE THIS ONCE TO FORCE UPDATE
  //=====================================================
  if(!$runnable) {echo "to run script uncomment runnable"; die;}
  define('EMONCMS_EXEC', 1);

  set_time_limit ( 1000 );
  error_reporting(E_ALL);
  ini_set('display_errors','on');
  error_reporting(E_ALL ^ E_NOTICE);

  require "Includes/db.php";
  $e = db_connect();

  require "Models/feed_model.php";
  //$feeds = get_all_feeds();
  //$feeds = get_user_feed_ids(1);

  foreach ($feeds as $feed ){

    $feedname = "feed_".trim($feed['id'])."";

    if (get_feed_type($feed['id'])==0)
    {
      echo "converted feed: ".$feed['id']."<br/>";

      // 1) rename feed to be converted as temporary
      $result = db_query("RENAME TABLE `$feedname` TO `feed_tmp`");

      $result = db_query("SELECT * FROM feed_tmp LIMIT 1");
      $row = db_fetch_array($result);
      if(!isset($row['data2']))
      {

      // 2) Create the new feed table with index
      $result = db_query("CREATE TABLE $feedname (
      `time` INT UNSIGNED NOT NULL ,
      `data` FLOAT NOT NULL ,
      INDEX ( `time` ))");

      // 3) Copy over and convert time from datetime to unix timestamp
      $result = db_query("INSERT $feedname SELECT UNIX_TIMESTAMP (time),data FROM feed_tmp");

      } else {

      // 2) Create the new feed table with index
      $result = db_query("CREATE TABLE $feedname (
      `time` INT UNSIGNED NOT NULL ,
      `data` FLOAT NOT NULL ,
      `data2` FLOAT NOT NULL ,
      INDEX ( `time` ))");

      // 3) Copy over and convert time from datetime to unix timestamp
      $result = db_query("INSERT $feedname SELECT UNIX_TIMESTAMP (time),data,data2 FROM feed_tmp");

      }

      // 4) Drop the temporary feed table
      $result = db_query("DROP TABLE `feed_tmp`");

      // 5) Update feed type status
      $feedid = $feed['id'];
      $result = db_query("UPDATE feeds SET type = 1 WHERE id='$feedid'");

    } else { echo "feed: ".$feed['id']." already converted<br/>";}

    $result = db_query("SHOW INDEX FROM `$feedname`");
    $row = db_fetch_array($result);
    if (!$row) 
    {
      echo "Missing index added to: ".$feedname."<br>";
      db_query("ALTER TABLE `$feedname` ADD INDEX (time)");
    }
    

  }
?>
