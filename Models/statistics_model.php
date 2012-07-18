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

function create_user_statistics($userid)
{
  db_query("INSERT INTO statistics (`userid`,`uphits`,`dnhits`,`memory`) VALUES ('$userid','0','0','0')");
}

function set_memory_statistics($userid, $memory)
{
  db_query("UPDATE statistics SET memory = '$memory' WHERE userid='$userid'");
}

function inc_uphits_statistics($userid)
{
  db_query("update statistics SET uphits = uphits + 1 WHERE userid='$userid'");
}

function inc_dnhits_statistics($userid)
{
  db_query("update statistics SET dnhits = dnhits + 1 WHERE userid='$userid'");
}

function get_statistics($userid)
{
  $result = db_query("SELECT * FROM statistics WHERE userid='$userid'");
  $result = db_fetch_array($result);

  if (!$result)
  {
    db_query("INSERT INTO statistics (`userid`,`uphits`,`dnhits`,`memory`) VALUES ('$userid','0','0','0')");
  }

  return $result;
}
