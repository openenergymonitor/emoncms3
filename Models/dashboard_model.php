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

function new_dashboard($userid)
{
  db_query("INSERT INTO dashboard (`userid`) VALUES ('$userid')");
  return db_insert_id();
}

function delete_dashboard($userid, $id)
{
  $result = db_query("DELETE FROM dashboard WHERE userid = '$userid' AND id = '$id'");
  return $result;
}

function get_dashboard_list($userid)
{
  $result = db_query("SELECT id, name, alias, description, main FROM dashboard WHERE userid='$userid'");
  $list = array();
  while ($row = db_fetch_array($result)) $list[] = $row;
  return $list;
}


function set_dashboard_content($userid, $content, $id)
{
  $result = db_query("SELECT * FROM dashboard WHERE userid = '$userid' AND id='$id'");
  $row = db_fetch_array($result);

  if ($row)
  {
    db_query("UPDATE dashboard SET content = '$content' WHERE userid='$userid' AND id='$id'");
  }
  else
  {
    db_query("INSERT INTO dashboard (`userid`,`content`,`id`) VALUES ('$userid','$content','$id')");
  }
}

function set_dashboard_conf($userid, $id, $name, $alias, $description, $main)
{
  $result = db_query("SELECT id FROM dashboard WHERE userid = '$userid' AND id='$id'");
  $row = db_fetch_array($result);

  if ($row)
  {
    db_query("UPDATE dashboard SET name = '$name', alias = '$alias', description = '$description' WHERE userid='$userid' AND id='$id'");

    // set user main dashboard
    if ($main == 'main')
    {
      db_query("UPDATE dashboard SET main = FALSE WHERE userid='$userid' AND id<>'$id'");

      // set main to the main dashboard
      db_query("UPDATE dashboard SET main = TRUE WHERE userid='$userid' AND id='$id'");
    }
    else
    {
      // set main to false all other user dashboards
      db_query("UPDATE dashboard SET main = FALSE WHERE userid='$userid' AND id='$id'");
    }
  }
}

// Return the main dashboard from $userid
function get_main_dashboard($userid)
{
  $result = db_query("SELECT * FROM dashboard WHERE userid='$userid' and main=TRUE");
  return db_fetch_array($result);
}

// Returns the $id dashboard from $userid
function get_dashboard_id($userid, $id)
{
  $result = db_query("SELECT * FROM dashboard WHERE userid='$userid' and id='$id'");
  return db_fetch_array($result);
}

// Returns the $id dashboard from $userid
function get_dashboard_alias($userid, $alias)
{
  $result = db_query("SELECT * FROM dashboard WHERE userid='$userid' and alias='$alias'");
  return db_fetch_array($result);
}


function build_dashboard_menu($userid,$location)
{
  global $path, $session;
  if ($location!="run") { $dashpath = 'dashboard/'.$location; } else { $dashpath = $session['username']; }

  $dashboards = get_dashboard_list($userid);
  foreach ($dashboards as $dashboard)
  {
    if ($dashboard['alias'])
    {
      $topmenu.='<li><a href="'.$path.$dashpath."/".$dashboard['alias'].'">'.$dashboard['name'].'</a></li>';
    }
    else
    {
      $topmenu.='<li><a href="'.$path.$dashpath.'&id='.$dashboard['id'].'">'.$dashboard['name'].'</a></li>';
    }
  }
  return $topmenu;
}


