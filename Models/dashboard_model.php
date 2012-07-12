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

function set_dashboard($userid, $content, $id)
{
  $result = db_query("SELECT * FROM dashboard WHERE userid = '$userid' and id='$id'");
  $row = db_fetch_array($result);

  if ($row)
  {
    db_query("UPDATE dashboard SET content = '$content' WHERE userid='$userid' and id='$id'");
  }
  else
  {
    db_query("INSERT INTO dashboard (`userid`,`content`,`id`) VALUES ('$userid','$content','$id')");
  }
}

function set_dashboard_conf($userid, $id, $name, $description, $main)
{
  $result = db_query("SELECT * FROM dashboard WHERE userid = '$userid' and id='$id'");
  $row = db_fetch_array($result);

  if ($row)
  {
    db_query("UPDATE dashboard SET name = '$name', description = '$description' WHERE userid='$userid' and id='$id'");

    // set user main dashboard
    if ($main == 'main')
    {
      db_query("update dashboard SET main = FALSE WHERE userid='$userid' and id<>'$id'");

      // set main to the main dashboard
      db_query("update dashboard SET main = TRUE WHERE userid='$userid' and id='$id'");
    }
    else
    {
      // set main to false all other user dashboards
      db_query("update dashboard SET main = FALSE WHERE userid='$userid' and id='$id'");
    }
  }

}

// Return the main dashboard from $userid
function get_dashboard($userid)
{
  $result = db_query("SELECT * FROM dashboard WHERE userid='$userid' and main=TRUE");
  $result = db_fetch_array($result);

  if ($result == FALSE)
    return FALSE;
  else
    return array(
      'ds_content' => $result['content'],
      'ds_name' => $result['name'],
      'ds_description' => $result['description'],
      'ds_main' => $result['main']
    );
}

// Returns the $id dashboard from $userid
function get_dashboard_id($userid, $id)
{
  $result = db_query("SELECT content,name,description,main FROM dashboard WHERE userid='$userid' and id='$id'");
  $result = db_fetch_array($result);

  return array(
    'ds_content' => $result['content'],
    'ds_name' => $result['name'],
    'ds_description' => $result['description'],
    'ds_main' => $result['main']
  );
}

function build_dashboardmenu($userid)
{
  $dsb = get_dashboards_info($userid);	
	
  $k = sizeof($dsb);
	
  // Only show menu if more than one dashboard were created
  if ($k>1)
  {
    $topmenu = '<div class="nav-collapse collapse"> <ul class="nav">';

    while ($k>0) {
      $row = $dsb[$k-1];
      $k = $k - 1;
      $topmenu = $topmenu.'<li><a href="./run&id='.$row['id'].'">'.$path.$row['name'].'</a></li>';
    }
  }
  
  // Dashboard list + logout
  return $topmenu."</ul><ul class='nav pull-right'><li><a href='".$GLOBALS['path']."user/logout'>"._("Logout")."</a></li></ul></div>";
}

function build_dashboard_menu($userid,$location)
{
  $dsb = get_dashboards_info($userid);	
	
  $k = sizeof($dsb);
	
  // Only show menu if more than one dashboard were created
  if ($k>1)
  {
    $topmenu = '<div class="nav-collapse collapse"> <ul class="nav">';
    
    while ($k>0) {
      $row = $dsb[$k-1];
      $k = $k - 1;
      $topmenu = $topmenu.'<li><a href="./'.$location.'&id='.$row['id'].'">'.$path.$row['name'].'</a></li>';
    }
  }
  
  return $topmenu;
}


