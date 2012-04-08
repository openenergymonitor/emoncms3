<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */


  function get_process_list()
  {

    $list = array();

    // Arg type
    // 0 - value
    // 1 - input id
    // 2 - feed id

    //		      Process description	Arg type	Function Name
    $list[1] = array( "Log to feed",		2,		"insert_feed_data"	);
    $list[2] = array( "x" ,					0,		"scale"			);
    $list[3] = array( "+" ,					0,		"offset"		);
    $list[4] = array( "Power to kWh" ,		2,		"power_to_kwh"		);
    $list[5] = array( "Power to kWh/d", 	2,		"power_to_kwhd"		);
    $list[6] = array( "x input",			1,		"times_input"		);
    $list[7] = array( "input on-time",		2,		"input_ontime"		);
    $list[8] = array( "kWhinc to kWh/d",	2,		"kwhinc_to_kwhd"	);
    $list[9] = array( "kWh to kWh/d",		2,		"kwh_to_kwhd"		);
    $list[10] = array( "update feed @time",	2,		"update_feed_data"	);
    $list[11] = array( "+ input",		1,		"add_input"		);
    $list[12] = array( "/ input" ,		0,		"divide"		);
    $list[13] = array( "phaseshift" ,		0,		"phaseshift"		);
    $list[14] = array( "accumulator" ,		2,		"accumulator"		);
    $list[15] = array( "rate of change" ,	2,		"ratechange"		);
    $list[16] = array( "histogram" ,		2,		"histogram"		);
    // $list[14] = array( "save_to_input" ,	4,		"save_to_input"		);
    // $list[15] = array( "+ feed",		3,		"add_feed"		);
    return $list;
  }

  function get_process($id)
  {
    $list = get_process_list();
    return $list[$id];
  }

  function scale($arg,$time,$value)
  {
    return $value * $arg;
  }

  function divide($arg,$time,$value)
  {
    return $value / $arg;
  }

  function offset($arg,$time,$value)
  {
    return $value + $arg;
  }

  //---------------------------------------------------------------------------------------
  // Times value by current value of another input
  //---------------------------------------------------------------------------------------
  function times_input($id,$time,$value)
  {
    $result = db_query("SELECT value FROM input WHERE id = '$id'");
    $row = db_fetch_array($result);
    $value = $value * $row['value'];
    return $value;
  }

  function add_input($id,$time,$value)
  {
    $result = db_query("SELECT value FROM input WHERE id = '$id'");
    $row = db_fetch_array($result);
    $value = $value + $row['value'];
    return $value;
  }

  function add_feed($id,$time,$value)
  {
    $result = db_query("SELECT value FROM feeds WHERE id = '$id'");
    $row = db_fetch_array($result);
    $value = $value + $row['value'];
    return $value;
  }

  //---------------------------------------------------------------------------------------
  // Power to kwh
  //---------------------------------------------------------------------------------------
  function power_to_kwh($feedid,$time_now,$value)
  {
    $feedname = "feed_".trim($feedid)."";
    $new_kwh = 0;

    // Get last value
    $result = db_query("SELECT * FROM $feedname ORDER BY time DESC LIMIT 1");
    $last_row = db_fetch_array($result);
    if ($last_row)
    {
      $last_time = strtotime($last_row['time']);
      $last_kwh = $last_row['data'];
      
      // kWh calculation
      $time_elapsed = ($time_now - $last_time);
      $kwh_inc = ($time_elapsed * $value) / 3600000;
      $new_kwh = $last_kwh + $kwh_inc;
    }

    // Insert new feed
    $time = date("Y-n-j H:i:s", $time_now);  
    db_query("INSERT INTO $feedname (`time`,`data`) VALUES ('$time','$new_kwh')");
    db_query("UPDATE feeds SET value = '$new_kwh', time = '$time' WHERE id='$feedid'");

    return $value;
  }

  //---------------------------------------------------------------------------------------
  // Power to kWh/d
  //---------------------------------------------------------------------------------------
  function power_to_kwhd($feedid,$time_now,$value)
  {
    $feedname = "feed_".trim($feedid)."";
    $new_kwh = 0;

    $time = date('y/m/d', mktime(0, 0, 0, date("m") , date("d") , date("Y")));

    // Get last value
    $result = db_query("SELECT * FROM $feedname WHERE time = '$time'");
    $last_row = db_fetch_array($result);

    if (!$last_row)
    {
      $result = db_query("INSERT INTO $feedname (time,data) VALUES ('$time','0.0')");

    $updatetime = date("Y-n-j H:i:s", $time_now);
    db_query("UPDATE feeds SET value = '0.0', time = '$updatetime' WHERE id='$feedid'");
    }
    else
    {
      $result = db_query("SELECT * FROM feeds WHERE id = '$feedid'");
      $last_row = db_fetch_array($result);

      $last_kwh = $last_row['value'];
      $last_time = strtotime($last_row['time']);
      // kWh calculation
      $time_elapsed = ($time_now - $last_time);
      $kwh_inc = ($time_elapsed * $value) / 3600000;
      $new_kwh = $last_kwh + $kwh_inc;
    }

    // update kwhd feed
    db_query("UPDATE $feedname SET data = '$new_kwh' WHERE time = '$time'");

    $updatetime = date("Y-n-j H:i:s",     $time_now);
    db_query("UPDATE feeds SET value = '$new_kwh', time = '$updatetime' WHERE id='$feedid'");

    return $value;
  }

  //---------------------------------------------------------------------------------------
  // kWh increment to kWhd
  //---------------------------------------------------------------------------------------
  function kwhinc_to_kwhd($feedid,$time_now,$value)
  {
    $feedname = "feed_".trim($feedid)."";
    $new_wh = $value/1000;

    $time = date('y/m/d', mktime(0, 0, 0, date("m") , date("d") , date("Y")));

    // Get last value
    $result = db_query("SELECT * FROM $feedname WHERE time = '$time'");
    $last_row = db_fetch_array($result);

    if (!$last_row)
    {
      $result = db_query("INSERT INTO $feedname (time,data) VALUES ('$time','0.0')");

    $updatetime = date("Y-n-j H:i:s", $time_now);
    db_query("UPDATE feeds SET value = '0.0', time = '$updatetime' WHERE id='$feedid'");
    }
    else
    {
      $new_wh = $last_row['data'] + ($value/1000);
    }

    // update kwhd feed
    db_query("UPDATE $feedname SET data = '$new_wh' WHERE time = '$time'");

    $updatetime = date("Y-n-j H:i:s", $time_now);
    db_query("UPDATE feeds SET value = '$new_wh', time = '$updatetime' WHERE id='$feedid'");

    return $value;
  }

  //---------------------------------------------------------------------------------------
  // input on-time counter
  //---------------------------------------------------------------------------------------
  function input_ontime($feedid,$time_now,$value)
  {
    $feedname = "feed_".trim($feedid)."";
    $new_kwh = 0;

    $time = date('y/m/d', mktime(0, 0, 0, date("m") , date("d") , date("Y")));

    // Get last value
    $result = db_query("SELECT * FROM $feedname WHERE time = '$time'");
    $last_row = db_fetch_array($result);

    if (!$last_row)
    {
      $result = db_query("INSERT INTO $feedname (time,data) VALUES ('$time','0.0')");

      $updatetime = date("Y-n-j H:i:s", $time_now);
      db_query("UPDATE feeds SET value = '0.0', time = '$updatetime' WHERE id='$feedid'");
    }
    else
    {
      $result = db_query("SELECT * FROM feeds WHERE id = '$feedid'");
      $last_row = db_fetch_array($result);

      $last_kwh = $last_row['value'];
      $last_time = strtotime($last_row['time']);
      // time elapsed calculation
      $time_elapsed = ($time_now - $last_time);
      if ($value>0) {$new_kwh = $last_kwh + $time_elapsed;} else {$new_kwh = $last_kwh;}
    }

    db_query("UPDATE $feedname SET data = '$new_kwh' WHERE time = '$time'");

    $updatetime = date("Y-n-j H:i:s", $time_now);
    db_query("UPDATE feeds SET value = '$new_kwh', time = '$updatetime' WHERE id='$feedid'");

    return $value;
  }

  //---------------------------------------------------------------------------------
  // This method converts accumulated energy to kwhd
  //---------------------------------------------------------------------------------
  function kwh_to_kwhd($feedid,$time_now,$value)
  {
    $kwh = $value;
    // tmpkwhd table: rows of: feedid | kwh

    $kwh_today = 0;

    $result = db_query("SELECT * FROM kwhdproc WHERE feedid = '$feedid'");
    $row = db_fetch_array($result);


    $start_day_kwh_value = $row['kwh'];
    if (!$row) db_query("INSERT INTO kwhdproc (feedid,kwh) VALUES ('$feedid','0.0')");

    $feedname = "feed_".trim($feedid)."";

    $time = date('y/m/d', mktime(0, 0, 0, date("m") , date("d") , date("Y")));
    // Check if there is an entry for this day
    $result = db_query("SELECT * FROM $feedname WHERE time = '$time'");
    $entry = db_fetch_array($result);

    if (!$entry)
    {
      //Log start of day kwh
      db_query("UPDATE kwhdproc SET kwh = '$kwh' WHERE feedid='$feedid'");
      $result = db_query("INSERT INTO $feedname (time,data) VALUES ('$time','0.0')");

      $updatetime = date("Y-n-j H:i:s", $time_now);
      db_query("UPDATE feeds SET value = '0.0', time = '$updatetime' WHERE id='$feedid'");
    }
    else
    {
      $kwh_today = $kwh - $start_day_kwh_value;
    }


    // update kwhd feed
    db_query("UPDATE $feedname SET data = '$kwh_today' WHERE time = '$time'");

    $updatetime = date("Y-n-j H:i:s", $time_now);
    db_query("UPDATE feeds SET value = '$kwh_today', time = '$updatetime' WHERE id='$feedid'");

    return $value;
  }
 
  function phaseshift($feedid,$time,$value)
  {
    $rad = acos($value);
    $rad = $rad + (($arg/360.0) * (2.0*3.14159265));
    return cos($rad);
  }

  //--------------------------------------------------------------------------------
  // Display the rate of change for the current and last entry
  //--------------------------------------------------------------------------------
  function ratechange($feedid,$time_now,$value)
  {
	// Get the feed
	$feedname = "feed_".trim($feedid)."";
	$time = date("Y-n-j H:i:s", $time_now);

	// Get the current input id 
	$result = db_query("Select * from input where processList like '%:$feedid%';");
	$rowfound = db_fetch_array($result);
	if ($rowfound)
	{
		$inputid = trim($rowfound['id']);
		$processlist = $rowfound['processList'];
		// Now get the feed for the log to feed command for the input 
		$logfeed = preg_match('/1:(\d+)/',$processlist,$matches);
		$logfeedid = trim($matches[1]);
		// Now need to get the last but one value in the main log to feed table
		$oldfeedname = "feed_".trim($logfeedid)."";
		$lastentry = db_query("Select * from $oldfeedname order by time desc LIMIT 2;");  
		$lastentryrow = db_fetch_array($lastentry); 
		// Calling again so can get the 2nd row
		$lastentryrow = db_fetch_array($lastentry);
		$prevValue = trim($lastentryrow['data']);
		$ratechange = $value - $prevValue;
		// now put this rate change into the correct feed table
		db_query("INSERT INTO $feedname (time,data) VALUES ('$time','$ratechange');");
		db_query("UPDATE feeds SET time='$time', value='$ratechange' WHERE id='$feedid';");
	}
	
  }

function save_to_input($arg,$time,$value)
{
  $name = $arg;
  $userid = $_SESSION['userid'];

    $id = get_input_id($userid,$name);				// If input does not exist this return's a zero
    if ($id==0) {
      create_input_timevalue($userid,$name,$time,$value);	// Create input if it does not exist
    } else {			
      set_input_timevalue($id,$time,$value);			// Set time and value if it does
    }

  return $value;
}

function accumulator($arg,$time,$value)
{
   $feedid = $arg;

   $last_value = get_feed_value($feedid);

   $value = $last_value+$value;

   insert_feed_data($feedid,$time,$value);

   return $value;
}
  
//---------------------------------------------------------------------------------
  // This method converts power to energy vs power (Histogram)
  //---------------------------------------------------------------------------------
  function histogram($feedid,$time_now,$value)
  {
    ///return $value;
  	
    $feedname = "feed_".trim($feedid)."";
    $new_kwh = 0;
    // Allocate power values into pots of varying sizes 
    if ($value < 500) 	 	{$pot = 50;}
    elseif ($value < 2000) 	{$pot = 100;}
    else 					{$pot = 500;}
    $new_value = round($value/$pot,0,PHP_ROUND_HALF_UP)*$pot;
    
    $time = date('y/m/d', mktime(0, 0, 0, date("m") , date("d") , date("Y")));

    // Get the last time
    $result = db_query("SELECT * FROM feeds WHERE id = '$feedid'");
    $last_row = db_fetch_array($result);

    if ($last_row)
    {
    	$last_time = strtotime($last_row['time']);
      	// kWh calculation
      	$time_elapsed = ($time_now - $last_time);
      	$kwh_inc = ($time_elapsed * $value) / 3600000;
    }

    // Get last value
    $result = db_query("SELECT * FROM $feedname WHERE time = '$time' AND data2 = '$new_value'");
    $last_row = db_fetch_array($result);

    if (!$last_row)
    {
      $result = db_query("INSERT INTO $feedname (time,data,data2) VALUES ('$time','0.0','$new_value')");

	  $updatetime = date("Y-n-j H:i:s", $time_now);
	  db_query("UPDATE feeds SET value = $new_value, time = '$updatetime' WHERE id='$feedid'");
      $new_kwh = $kwh_inc;
    }
    else
    {
      $last_kwh = $last_row['data'];
      $new_kwh = $last_kwh + $kwh_inc;
    }

    // update kwhd feed
    db_query("UPDATE $feedname SET data = '$new_kwh' WHERE time = '$time' AND data2 = '$new_value'");

    $updatetime = date("Y-n-j H:i:s",     $time_now);
    db_query("UPDATE feeds SET value = '$new_value', time = '$updatetime' WHERE id='$feedid'");

    return $value;
  }

  function histogram_history($feedid,$inputfeedid, $start, $end)
  {
    ///return $value;
  	
    $feedname = "feed_".trim($feedid)."";
    $feedinput = "feed_".trim($inputfeedid)."";
	$last_dt = 0;
    
    ///$start = "2011-09-01";
    ///$end = "2011-10-01";

    // Get the input feed data
   	$result = db_query("SELECT time, data, date(time) as dt FROM $feedinput WHERE time BETWEEN '$start' AND '$end' ORDER BY time ASC");
	while($row = db_fetch_array($result))             // for all the new lines
	{
		$value = $row['data'] ;                        //get the datavalue
	    $time = (strtotime($row['time']))*1000;            //and the time value - converted to unix time * 1000
	    $dt = $row['dt'] ;

	    // Allocate power values into pots of varying sizes 
	    if ($value < 500) 	 	{$pot = 50;}
	    elseif ($value < 2000) 	{$pot = 100;}
	    else 					{$pot = 500;}
	    $watts = round($value/$pot,0,PHP_ROUND_HALF_UP)*$pot;
	    
      	// kWh calculation
      	$time_elapsed = ($time - $last_time);
      	$kwh_inc = ($time_elapsed * $value) / 3600000;
	    
		// Clear original data for each new date.
		if ($dt != $last_dt)
		{
			$result3 = db_query("DELETE FROM $feedname WHERE time = '$dt'");
		}
		
      	// Don't process the first row or too long since the last reading
      	if (($last_dt != 0) && $time_elapsed < 20*60*60*1000)
      	{ 
	      	// Find if that pot already exists
		    $result2 = db_query("SELECT * FROM $feedname WHERE time = '$last_dt' AND data2 = '$last_watts'");
		    $last_row = db_fetch_array($result2);
		
		    if (!$last_row)
		    {
		      	$result3 = db_query("INSERT INTO $feedname (time,data,data2) VALUES ('$last_dt','$kwh_inc','$last_watts')");
		      	$rows++;
		    }
		    else
		    {
			    $result3 = db_query("UPDATE $feedname SET data = data + $kwh_inc WHERE time = '$last_dt' AND data2 = '$last_watts'");
		    }
      	}
	    $last_time 	= $time;
	    $last_dt 	= $dt;
	    $last_value = $value;
	    $last_watts = $watts;
	}

	return $rows;
  }
  
?>

