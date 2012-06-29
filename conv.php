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
if (!$runnable)
{
  echo "to run script uncomment runnable";
  die ;
}
define('EMONCMS_EXEC', 1);

set_time_limit(1000);
error_reporting(E_ALL);
ini_set('display_errors', 'on');
error_reporting(E_ALL ^ E_NOTICE);

require "Includes/db.php";
$e = db_connect();

require "Models/feed_model.php";
$feeds = get_all_feeds();
//$feeds = get_user_feed_ids(1);

foreach ($feeds as $feed)
{

  $feedname = "feed_" . trim($feed['id']) . "";

  $time = 0;
  $data = 0;
  $data2 = 0;
  $result = db_query("SHOW COLUMNS FROM $feedname");
  if ($result == false)
  {
    echo ", but there is an entry in the feeds table?";
    die ;
  }
  $row = db_fetch_array($result);
  if ($row[0] == "time")
  {
    $time = 1;
    $timeformat = $row['Type'];
  }
  $row = db_fetch_array($result);
  if ($row[0] == "data")
    $data = 1;
  $row = db_fetch_array($result);
  if ($row[0] == "data2")
    $data2 = 1;

  if (!$time)
  {
    echo "table $feedname does not have a time field?";
    die ;
  }
  if (!$data)
  {
    echo "table $feedname does not have a data field?";
    die ;
  }

  if ($timeformat == "datetime")
  {
    $result = db_query("SHOW TABLE STATUS LIKE '$feedname'");
    $row = db_fetch_array($result);
    $rows = $row['Rows'];
    echo $rows;

    // 1) rename feed to be converted as temporary
    $result = db_query("RENAME TABLE `$feedname` TO `feed_tmp`");
    if ($result == false)
      die ;

    if (!$data2)
    {
      // 2) Create the new feed table with index
      $result = db_query("CREATE TABLE $feedname (
        `time` INT UNSIGNED NOT NULL ,
        `data` FLOAT NOT NULL ,
        INDEX ( `time` ))");
      if ($result == false)
        die ;

      // 3) Copy over and convert time from datetime to unix timestamp
      $result = db_query("INSERT $feedname SELECT UNIX_TIMESTAMP (time),data FROM feed_tmp");
      if ($result == false)
        die ;
    }

    // If histogram type feed
    if ($data2)
    {
      // 2) Create the new feed table with index
      $result = db_query("CREATE TABLE $feedname (
        `time` INT UNSIGNED NOT NULL ,
        `data` FLOAT NOT NULL ,
        `data2` FLOAT NOT NULL ,
        INDEX ( `time` ))");
      if ($result == false)
        die ;

      // 3) Copy over and convert time from datetime to unix timestamp
      $result = db_query("INSERT $feedname SELECT UNIX_TIMESTAMP (time),data,data2 FROM feed_tmp");
      if ($result == false)
        die ;
    }

    $result = db_query("SHOW TABLE STATUS LIKE '$feedname'");
    $row = db_fetch_array($result);
    if ($rows != $row['Rows'])
    {
      echo "Number of rows in converted table is different from original, original data can be found in the feed_tmp table";
      die ;
    }

    // 4) Drop the temporary feed table
    $result = db_query("DROP TABLE `feed_tmp`");
    if ($result == false)
      die ;

    echo "converted feed: " . $feed['id'] . "<br/>";

  }
  else
  {
    echo "feed: " . $feed['id'] . " already converted<br/>";
  }

  $result = db_query("SHOW INDEX FROM `$feedname`");
  $row = db_fetch_array($result);
  if (!$row)
  {
    echo "Missing index added to: " . $feedname . "<br>";
    $result = db_query("ALTER TABLE `$feedname` ADD INDEX (time)");
    if ($result == false)
      die ;
  }

}
?>
