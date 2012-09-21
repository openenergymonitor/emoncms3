<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

  // no direct access
  defined('EMONCMS_EXEC') or die('Restricted access');

  //----------------------------------------------------------------------------------------------------------------------------------------------------------------
  // Creates a feed entry and relates the feed to the user
  //----------------------------------------------------------------------------------------------------------------------------------------------------------------
  function create_feed($userid,$name,$NoOfDataFields,$datatype)
  {
    // Check if feed of given name by the user already exists
    $feedid = get_feed_id($userid,$name);
    if ($feedid!=0) return $feedid;

    $result = db_query("INSERT INTO feeds (name,status,datatype) VALUES ('$name','0','$datatype')");				// Create the feed entry
    $feedid = db_insert_id();
    if ($feedid>0) {
      db_query("INSERT INTO feed_relation (userid,feedid) VALUES ('$userid','$feedid')");	        // Create a user->feed relation

      $feedname = "feed_".$feedid;									// Feed name

      if ($NoOfDataFields==1) {										// Create a table with one data field
        $result = db_query(										// Used for most feeds
        "CREATE TABLE $feedname (
	  time INT UNSIGNED, data float,
        INDEX ( `time` ))");
      }

      if ($NoOfDataFields==2) {										// Create a table with two data fields
        $result = db_query(										// User for histogram feed
        "CREATE TABLE $feedname (
	  time INT UNSIGNED, data float, data2 float,
        INDEX ( `time` ))");
      }

      return $feedid;											// Return created feed id
    } else return 0;
  }

  //-----------------------------------------------------------------------------------------------------------
  // This function gets a users feed list, its used to create the feed/list page
  // - status: 0 list of active user feeds, 1 list of deleted user feeds
  //-----------------------------------------------------------------------------------------------------------
  function get_user_feeds($userid,$status)
  {
    $result = db_query("SELECT * FROM feed_relation WHERE userid = '$userid'");
    $feeds = array();
    if ($result) {
      while ($row = db_fetch_array($result)) {
        $feed = get_feed($row['feedid']);
        if ($feed && $feed[7]==$status) $feeds[] = $feed;
      }
    }
    usort($feeds, 'compare');		// Sort feeds by tag's
    return $feeds;
  }
  
  // Used as part of get_user_feeds to sort by tag.
  function compare($x, $y)
  {
    if ( $x[2] == $y[2] )
     return 0;
    else if ( $x[2] < $y[2] )
     return -1;
    else
     return 1;
  }

  //-----------------------------------------------------------------------------------------------------------
  // Return only a list of the users feed id's rather than get_user_feeds 
  // which returns all the details about the feed
  //-----------------------------------------------------------------------------------------------------------
  function get_user_feed_ids($userid)
  {
    $result = db_query("SELECT feedid FROM feed_relation WHERE userid = '$userid'");
    $feeds = array();
    if ($result) {
      while ($row = db_fetch_array($result)) {
        $feeds[] = $row['feedid'];
      }
    }
    return $feeds;
  }
 
  //-----------------------------------------------------------------------------------------------------------
  // Get a list of users feed ids and names
  //-----------------------------------------------------------------------------------------------------------
  function get_user_feed_names($userid)
  {
    $result = db_query("SELECT name, id FROM feeds WHERE id IN (
SELECT feedid FROM feed_relation WHERE userid = $userid) ORDER BY name ASC");
    $feeds = array();
    if ($result) {
      while ($row = db_fetch_array($result)) {
        $feeds[] = array($row['id'],$row['name']);
      }
    }
    return $feeds;
  }
 
  //-----------------------------------------------------------------------------------------------------------
  // Gets all details about a particular feed, including:
  // id, name, tag, lastupdatetime, value, size, datatype and status
  //-----------------------------------------------------------------------------------------------------------
  function get_feed($feedid)
  {
    $feed_result = db_query("SELECT * FROM feeds WHERE id = '$feedid'");
    $feed_row = db_fetch_array($feed_result);

    $size = get_feedtable_size($feed_row['id']);
    $feed = array($feed_row['id'],$feed_row['name'],$feed_row['tag'],strtotime($feed_row['time'])*1000,$feed_row['value'],$size, $feed_row['datatype'], $feed_row['status']);

    return $feed;
  }

  //----------------------------------------------------------------------------------------------------------------------------------------------------------------
  // Gets a feeds ID from it's name and user ID
  //----------------------------------------------------------------------------------------------------------------------------------------------------------------
  function get_feed_id($user,$name)
  {
    $result = db_query("SELECT * FROM feed_relation WHERE userid='$user'");
    while ($row = db_fetch_array($result))
    {
      $feedid = $row['feedid'];
      $result2 = db_query("SELECT name FROM feeds WHERE id='$feedid' AND status = 0");
      $row_name = db_fetch_array($result2);
      if ($name == $row_name['name']) return $feedid;
    }
    return 0;
  }

  //----------------------------------------------------------------------------------------------------------------------------------------------------------------
  // Gets a feeds name from its ID
  //----------------------------------------------------------------------------------------------------------------------------------------------------------------
  function get_feed_name($feedid)
  {
    $result = db_query("SELECT name FROM feeds WHERE id='$feedid'");
    if ($result) { $array = db_fetch_array($result); return $array['name']; } 
    else return 0;
  }

  function set_feed_name($feedid,$name)
  {
    db_query("UPDATE feeds SET name = '$name' WHERE id='$feedid'");
  }

  function set_feed_tag($feedid,$tag)
  {
    db_query("UPDATE feeds SET tag = '$tag' WHERE id='$feedid'");
  }

  //---------------------------------------------------------------------------
  // Function feed insert
  // updatetime - is the time value that goes in the feeds table
  // feedtime   - is the time value that goes in the feed_no. table
  //---------------------------------------------------------------------------
  function insert_feed_data($feedid,$updatetime,$feedtime,$value)
  { 
    if (get_feed_status($feedid)==1) return $value;	// Dont insert if deleted

    $feedname = "feed_".trim($feedid)."";

    // a. Insert data value in feed table
    db_query("INSERT INTO $feedname (`time`,`data`) VALUES ('$feedtime','$value')");

    // b. Update feeds table
    $updatetime = date("Y-n-j H:i:s", $updatetime); 
    db_query("UPDATE feeds SET value = '$value', time = '$updatetime' WHERE id='$feedid'");

    return $value;
  }

  function update_feed_data($feedid,$updatetime,$feedtime,$value)
  {        
    if (get_feed_status($feedid)==1) return $value;	// Dont update if deleted
             
    $feedname = "feed_".trim($feedid)."";

    // a. update or insert data value in feed table
    $result = db_query("SELECT * FROM $feedname WHERE time = '$feedtime'");
    $row = db_fetch_array($result);

    if ($row) db_query("UPDATE $feedname SET data = '$value', time = '$feedtime' WHERE time = '$feedtime'");
    if (!$row) {$value = 0; db_query("INSERT INTO $feedname (`time`,`data`) VALUES ('$feedtime','$value')"); }

    // b. Update feeds table
    $updatetime = date("Y-n-j H:i:s", $updatetime); 
    db_query("UPDATE feeds SET value = '$value', time = '$updatetime' WHERE id='$feedid'");
    return $value;
  }

  function get_feed_data($feedid,$start,$end,$dp)
  {
    if ($end == 0) $end = time()*1000;

    $feedname = "feed_".trim($feedid)."";
    $start = $start/1000; $end = $end/1000;

    $data = array();
    if (($end - $start) > (5000) && $dp>0) //why 5000?
    {
      $range = $end - $start;
      $td = $range / $dp;

      for ($i=0; $i<$dp; $i++)
      {
        $t = $start + $i*$td;
        $tb = $start + ($i+1)*$td;
        $result = db_query("SELECT * FROM $feedname WHERE `time` >$t AND `time` <$tb LIMIT 1");

        if($result){
          $row = db_fetch_array($result);
          $dataValue = $row['data'];               
          if ($dataValue!=NULL) { // Remove this to show white space gaps in graph      
            $time = $row['time'] * 1000;     
            $data[] = array($time , $dataValue); 
          } 
        }         
      }
    } else {
      $result = db_query("select * from $feedname WHERE time>$start AND time<$end order by time Desc");
      while($row = db_fetch_array($result)) {
        $dataValue = $row['data'];
        $time = $row['time'] * 1000;  
        $data[] = array($time , $dataValue); 
      }
    }

    return $data;
  }

 function get_histogram_data($feedid,$start,$end)
 {
   if ($end == 0) $end = time()*1000;
   $feedname = "feed_".trim($feedid)."";
   $start = $start/1000; $end = $end/1000;
   $data = array();

   // Histogram has an extra dimension so a sum and group by needs to be used.
   $result = db_query("select data2, sum(data) as kWh from $feedname WHERE time>='$start' AND time<'$end' group by data2 order by data2 Asc"); 
	
   $data = array();                                      // create an array for them
   while($row = db_fetch_array($result))                 // for all the new lines
   {
     $dataValue = $row['kWh'];                           // get the datavalue
     $data2 = $row['data2'];            		 // and the instant watts
     $data[] = array($data2 , $dataValue);               // add time and data to the array
   }
   return $data;
 }

 function get_kwhd_atpower($feedid, $min, $max)
 {
   $feedname = "feed_".trim($feedid)."";
   $result = db_query("SELECT time, sum(data) as kWh FROM `$feedname` WHERE `data2`>='$min' AND `data2`<='$max' group by time");

   $data = array();
   while($row = db_fetch_array($result)) $data[] = array($row['time']* 1000 , $row['kWh']); 

   return $data;
 }

 function get_feed_timevalue($feedid)
 {
    $result = db_query("SELECT time,value FROM feeds WHERE id='$feedid'");
    $feed = db_fetch_array($result);
    return $feed;
 }

 function get_feed_value($feedid)
 {
    $result = db_query("SELECT value FROM feeds WHERE id='$feedid'");
    $feed = db_fetch_array($result);
    return $feed['value'];
 }

 function get_feed_total($feedid)
 {
    $result = db_query("SELECT total FROM feeds WHERE id='$feedid'");
    $feed = db_fetch_array($result);
    return $feed['total'];
 }

 function get_feed_stats($feedid)
 {
    $result = db_query("SELECT * FROM feeds WHERE id='$feedid'");
    $feed = db_fetch_array($result);
    return array($feed['id'],$feed['name'],$feed['time'],$feed['value'],$feed['today'],$feed['yesterday'],$feed['week'],$feed['month'],$feed['year']);
 }

  function calc_feed_stats($id)
  {
    $kwhd_table = "feed_".$id;
    $result = db_query("SELECT * FROM $kwhd_table ORDER BY time DESC");

    $now = time();

    $day7   = $now - (3600*24*7);
    $day30  = $now - (3600*24*30);
    $day365 = $now - (3600*24*365); 

    $sum_day7 = 0; $i7 = 0;
    $sum_day30 = 0; $i30 = 0;
    $sum_day365 = 0; $i365 = 0;
    $i=0;
    $row = db_fetch_array($result); //get rid of today
    while($row = db_fetch_array($result))
    {
      $i++;
    
      $time = $row['time'];

      $kwhd = $row['data'];

      if ($i==1) { $yesterday = $kwhd; }
      if ($day7<=$time) { $i7++; $sum_day7 += $kwhd; }
      if ($day30<=$time) { $i30++; $sum_day30 += $kwhd; }
      if ($day365<=$time) { $i365++; $sum_day365 += $kwhd; }
    }

    $yesterday = number_format($yesterday,1);
    $av7 = number_format($sum_day7 / $i7,1);
    $av30 = number_format($sum_day30 / $i30,1);
    $av365 = number_format($sum_day365 / $i365,1);
 
    $result = db_query("UPDATE feeds SET yesterday = '$yesterday', week='$av7', month = '$av30', year = '$av365' WHERE id = '$id'");
  }

  function delete_feed($userid,$feedid)
  {
    // feed status of 1 = deleted, this provides a way to soft delete so that if the delete was a mistake
    // it can be taken out of the recycle bin.
    db_query("UPDATE feeds SET status = 1 WHERE id='$feedid'");
  }

  function restore_feed($userid,$feedid)
  {
    // feed status of 1 = deleted, this provides a way to soft delete so that if the delete was a mistake
    // it can be taken out of the recycle bin.
    db_query("UPDATE feeds SET status = 0 WHERE id='$feedid'");
  }

  function permanently_delete_feeds($userid)
  {
    $result = db_query("SELECT * FROM feed_relation WHERE userid = '$userid'");
    $feeds = array();
    if ($result) {
      while ($row = db_fetch_array($result)) {
        $feed = get_feed($row['feedid']);
        $feedid = $feed[0];
        if ($feed && $feed[7]==1){
          db_query("DELETE FROM feeds WHERE id = '$feedid'");
          db_query("DELETE FROM feeds WHERE id = '$feedid'");
          db_query("DELETE FROM feed_relation WHERE userid = '$userid' AND feedid = '$feedid' LIMIT 1");
          db_query("DROP TABLE feed_".$feedid);
        }
      }
    }
  }

  function feed_belongs_user($feedid,$userid)
  {
    $result = db_query("SELECT * FROM feed_relation WHERE userid = '$userid' AND feedid = '$feedid'");
    $row = db_fetch_array($result);

    if ($row) return 1;
    return 0;
  }

  function get_feedtable_size($feedid)
  {
    $feedname = "feed_".$feedid;
    $result = db_query("SHOW TABLE STATUS LIKE '$feedname'");
    $row = db_fetch_array($result);
    $tablesize = $row['Data_length']+$row['Index_length'];
    return $tablesize;
  }

  function get_user_feeds_size($userid)
  {
    $result = db_query("SELECT * FROM feed_relation WHERE userid = '$userid'");
    $total = 0;
    if ($result) {
      while ($row = db_fetch_array($result)) {
        $total += get_feedtable_size($row['feedid']);
      }
    }

    return $total;
  }

  // Get the feed status
  function get_feed_status($feedid)
  {
    $result = db_query("SELECT status FROM feeds WHERE id='$feedid'");
    $feed = db_fetch_array($result);
    return $feed['status'];
  }

  // Get feed datatype: 0: undefined type, 1: real-time data, 2: daily data, 3: histogram data
  function get_feed_datatype($feedid)
  {
    $result = db_query("SELECT datatype FROM feeds WHERE id='$feedid'");
    $feed = db_fetch_array($result);
    return $feed['datatype'];
  }

  // Set feed datatype: 0: undefined type, 1: real-time data, 2: daily data, 3: histogram data
  function set_feed_datatype($feedid,$type)
  {
    db_query("UPDATE feeds SET datatype = '$type' WHERE id='$feedid'");
  }

  // Get a list of all feeds in table (not just a particular users feeds.
  function get_all_feeds()
  {
    $result = db_query("SELECT id FROM feeds");
    $feeds = array();

    while ($row = db_fetch_array($result)) {
        $feeds[]['id'] = $row['id'];
    }
    return $feeds;
  }
?>
