<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

  //----------------------------------------------------------------------------------------------------------------------------------------------------------------
  // Creates a feed entry and relates the feed to the user
  //----------------------------------------------------------------------------------------------------------------------------------------------------------------
  function create_feed($userid,$name)
  {
    $result = db_query("INSERT INTO feeds (name,status) VALUES ('$name','0')");				// Create the feed entry
    $ido = db_insert_id();
    $result = db_query("SELECT id FROM feeds WHERE name='$name'");				// Select the same feed to find the auto assigned id
    if ($result) {
      $array = db_fetch_array($result);
      $feedid = $ido;											// Feed id
      db_query("INSERT INTO feed_relation (userid,feedid) VALUES ('$userid','$feedid')");	// Create a user->feed relation

      // create feed table
      $feedname = "feed_".$feedid;
      $result = db_query(
      "CREATE TABLE $feedname
      (
        time DATETIME,
        data float
      )");

      return $feedid;												// Return created feed id
    } else return 0;
  }

    function get_user_feeds($userid)
    {
        $result = db_query("SELECT * FROM feed_relation WHERE userid = '$userid'");
        $feeds = array();
        if ($result)
        {
          while ($row = db_fetch_array($result)) {
            $feed = get_feed($row['feedid']);
            if ($feed) $feeds[] = $feed;
          }
        }

        //array_multisort($feeds[2],SORT_ASC);
        usort($feeds, 'compare');		// Sort feeds by tag's

        return $feeds;
    }

function compare($x, $y)
{
 if ( $x[2] == $y[2] )
  return 0;
 else if ( $x[2] < $y[2] )
  return -1;
 else
  return 1;
}

  function get_feed($feedid)
  {
    $feed_result = db_query("SELECT * FROM feeds WHERE id = '$feedid'");
    $feed_row = db_fetch_array($feed_result);
    if ($feed_row['status'] != 1) { // if feed is not deleted
      $size = get_feedtable_size($feed_row['id']);
      $feed = array($feed_row['id'],$feed_row['name'],$feed_row['tag'],$feed_row['time'],$feed_row['value'],$size);
    }
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
  //---------------------------------------------------------------------------
  function insert_feed_data($feedid,$time,$value)
  {                   
    $feedname = "feed_".trim($feedid)."";
    $time = date("Y-n-j H:i:s", $time);                        
    db_query("INSERT INTO $feedname (`time`,`data`) VALUES ('$time','$value')");
    db_query("UPDATE feeds SET value = '$value', time = '$time' WHERE id='$feedid'");
    return $value;
  }

  function update_feed_data($feedid,$time,$value)
  {                     
    $feedname = "feed_".trim($feedid)."";
    $time = date("Y-n-j H:i:s", $time);

    $result = db_query("SELECT * FROM $feedname WHERE time = '$time'");
    $row = db_fetch_array($result);

    if ($row)
    {
      db_query("UPDATE $feedname SET data = '$value', time = '$time' WHERE time = '$time'");
    } else {
      db_query("INSERT INTO $feedname (`time`,`data`) VALUES ('$time','$value')");
    }

    db_query("UPDATE feeds SET value = '$value', time = '$time' WHERE id='$feedid'");
    return $value;
  }

  //---------------------------------------------------------------------------
  // Get all feed data (it might be best not to call this on a really large dataset use function below to select data @ resolution)
  //---------------------------------------------------------------------------
  function get_all_feed_data($feedid)
  {
    $feedname = "feed_".trim($feedid)."";

    $data = array();   
    $result = db_query("select * from $feedname ORDER BY time");
    while($array = db_fetch_array($result))
    {
      $time = strtotime($array['time'])*1000;
      $kwhd = $array['data'];    
      $data[] = array($time , $kwhd);
    }
    return $data;
  }

  //---------------------------------------------------------------------------
  // Get feed data - within date range and @ specified resolution
  //---------------------------------------------------------------------------
  function get_feed_data($feedid,$start,$end,$resolution)
  {
    if ($end == 0) $end = time()*1000;

    $feedname = "feed_".trim($feedid)."";
    $start = date("Y-n-j H:i:s", ($start/1000));		//Time format conversion
    $end = date("Y-n-j H:i:s", ($end/1000));  			//Time format conversion

    //This mysql query selects data from the table at specified resolution
    if ($resolution>1){
      $result = db_query(
      "SELECT * FROM 
      (SELECT @row := @row +1 AS rownum, time,data FROM ( SELECT @row :=0) r, $feedname) 
      ranked WHERE (rownum % $resolution = 1) AND (time>'$start' AND time<'$end') order by time Desc");
    }
    else
    {
      //When resolution is 1 the above query doesnt work so we use this one:
      $result = db_query("select * from $feedname WHERE time>'$start' AND time<'$end' order by time Desc"); 
    }

    $data = array();                                     //create an array for them
    while($row = db_fetch_array($result))             // for all the new lines
    {
      $dataValue = $row['data'] ;                        //get the datavalue
      $time = (strtotime($row['time']))*1000;            //and the time value - converted to unix time * 1000
      $data[] = array($time , $dataValue);               //add time and data to the array
    }
    return $data;
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
      $time = strtotime($row['time']);
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
    // feed status of 1 = deleted, this provides a way to soft delete so that if the delete was a mistake it can be taken out of the recycle bin as it where.
    // It would be a good idea to have a hard delete option as well so that one can completely erase data.
    db_query("UPDATE feeds SET status = 1 WHERE id='$feedid'");
    //db_query("DELETE FROM feeds WHERE id = '$feedid'");
    //db_query("DELETE FROM feeds WHERE id = '$feedid'");
    //db_query("DELETE FROM feed_relation WHERE userid = '$userid' AND feedid = '$feedid' LIMIT 1");
    //db_query("DROP TABLE feed_".$feedid);
    //echo "delete feed ".$feedid;
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


?>
