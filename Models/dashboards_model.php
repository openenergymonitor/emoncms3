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

  function new_dashboards($userid)
  {
    db_query("INSERT INTO dashboard (`userid`) VALUES ('$userid')");
  }

  function get_dashboards($userid)
  {
    $result = db_query("SELECT * FROM dashboard WHERE userid='$userid'");
    return $result;
  }

  function delete_dashboards($id)
  {
	db_query("DELETE FROM dashboard WHERE id = '$id'");
	return "DELETE FROM dashboard WHERE id = '$id'";
  }

  // Returns the dashboards from $userid
  function get_dashboards_info($userid)
  {
    $result = db_query("SELECT content,name,description,main FROM dashboard WHERE userid='$userid'");
    //$result = db_fetch_array($result);
    
    $dsb = array();
	
	while($row = db_fetch_array($result))							
    {
    	$dsb[] = $row;	
	}
    	
	return $dsb;
	
	/*array(
		'ds_content'=>$row['content'],
		'ds_name'=>$row['name'],
		'ds_description'=>$row['description'],
		'ds_main'=>$row['main']);*/
  }  