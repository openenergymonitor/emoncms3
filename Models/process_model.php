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

    //		      Process description	Arg type	Function Name		// No. of datafields if creating feed
    $list[1] = array( "Log to feed",		2,		"log_to_feed",		1	);
    $list[2] = array( "x" ,			0,		"scale",		0	);
    $list[3] = array( "+" ,			0,		"offset",		0	);
    $list[4] = array( "Power to kWh" ,		2,		"power_to_kwh",		1	);
    $list[5] = array( "Power to kWh/d", 	2,		"power_to_kwhd",	1	);
    $list[6] = array( "x input",		1,		"times_input",		0	);
    $list[7] = array( "input on-time",		2,		"input_ontime",		1	);
    $list[8] = array( "kWhinc to kWh/d",	2,		"kwhinc_to_kwhd",	1	);
    $list[9] = array( "kWh to kWh/d",		2,		"kwh_to_kwhd",		1	);
    $list[10] = array( "update feed @time",	2,		"update_feed_data",	1	);
    $list[11] = array( "+ input",		1,		"add_input",		0	);
    $list[12] = array( "/ input" ,		0,		"divide",		0	);
    $list[13] = array( "phaseshift" ,		0,		"phaseshift",		0	);
    $list[14] = array( "accumulator" ,		2,		"accumulator",		1	);
    $list[15] = array( "rate of change" ,	2,		"ratechange",		1	);
    $list[16] = array( "histogram" ,		2,		"histogram",		2	);

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

  function log_to_feed($id,$time,$value)
  {
    insert_feed_data($id,$time,$time,$value);

    return $value;
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
    $new_kwh = 0;

    // Get last value
    $last = get_feed_timevalue($feedid);
    $last_kwh = $last['value'];
    $last_time = strtotime($last['time']);
      
    if ($last_time) {
      // kWh calculation
      $time_elapsed = ($time_now - $last_time);
      $kwh_inc = ($time_elapsed * $value) / 3600000;
      $new_kwh = $last_kwh + $kwh_inc;
    }

    insert_feed_data($feedid,$time_now,$time_now,$new_kwh);

    return $value;
  }

  function power_to_kwhd($feedid,$time_now,$value)
  {
    $new_kwh = 0;

    // Get last value
    $last = get_feed_timevalue($feedid);
    $last_kwh = $last['value'];
    $last_time = strtotime($last['time']);

    if ($last_time) {
      // kWh calculation
      $time_elapsed = ($time_now - $last_time);
      $kwh_inc = ($time_elapsed * $value) / 3600000;
      $new_kwh = $last_kwh + $kwh_inc;
    }

    $feedtime = mktime(0, 0, 0, date("m") , date("d") , date("Y"));
    update_feed_data($feedid,$time_now,$feedtime,$new_kwh);

    return $value;
  }

  function kwhinc_to_kwhd($feedid,$time_now,$value)
  {
    $new_kwh = 0;

    // Get last value
    $last = get_feed_timevalue($feedid);
    $last_kwh = $last['value'];

    $kwh_inc = $value / 1000.0;
    $new_kwh = $last_kwh + $kwh_inc;

    $feedtime = mktime(0, 0, 0, date("m") , date("d") , date("Y"));
    update_feed_data($feedid,$time_now,$feedtime,$new_kwh);

    return $value;
  }

  //---------------------------------------------------------------------------------------
  // input on-time counter
  //---------------------------------------------------------------------------------------
  function input_ontime($feedid,$time_now,$value)
  {
    $new_kwh = 0;

    // Get last value
    $last = get_feed_timevalue($feedid);
    $last_ontime = $last['value'];
    $last_time = strtotime($last['time']);

    if ($last_time) {
      $time_elapsed = ($time_now - $last_time);
      $ontime = $last_ontime + $time_elapsed;
    }

    $feedtime = mktime(0, 0, 0, date("m") , date("d") , date("Y"));
    update_feed_data($feedid,$time_now,$feedtime,$ontime);

    return $value;
  }

  //---------------------------------------------------------------------------------
  // This method converts accumulated energy to kwhd
  //---------------------------------------------------------------------------------
  function kwh_to_kwhd($feedid,$time_now,$value)
  {
    $time = mktime(0, 0, 0, date("m") , date("d") , date("Y"));

    // First we check if there is an entry for the feed in the kwhdproc table
    $result = db_query("SELECT * FROM kwhdproc WHERE feedid = '$feedid'");
    $row = db_fetch_array($result);

    // If there is not we create an entry
    if (!$row) db_query("INSERT INTO kwhdproc (feedid,time,kwh) VALUES ('$feedid','0','0')");

    // We then check if the entries time is the same as todays time if it isnt its a new day
    // and we need to put the kwh figure for the start of the day in the kwhdproc table
    if ($time != $row['time']) {
      db_query("UPDATE kwhdproc SET kwh = '$value', time = '$time' WHERE feedid='$feedid'");
      $start_day_kwh_value = $value;
    } else {
      // If it isnt the start of the day then we need to get the start of the day kwh figure
      $start_day_kwh_value = $row['kwh'];
    }

    // 3) Calculate todays kwh figure
    $kwhd = $value - $start_day_kwh_value;

    // 4) Update feed kwhd
    update_feed_data($feedid,$time_now,$time,$kwhd);

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
                insert_feed_data($feedid,$time_now,$time_now,$ratechange);
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

   insert_feed_data($feedid,$time,$time,$value);

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
    else 			{$pot = 500;}
    $new_value = round($value/$pot,0,PHP_ROUND_HALF_UP)*$pot;

    $time = mktime(0, 0, 0, date("m") , date("d") , date("Y"));

    // Get the last time
    $result = db_query("SELECT * FROM feeds WHERE id = '$feedid'");
    $last_row = db_fetch_array($result);

    if ($last_row)
    {
    	$last_time = strtotime($last_row['time']);
        if (!$last_time) $last_time = $time_now;
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

/*
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
  */
?>

