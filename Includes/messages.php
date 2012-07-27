<?php
/*
 All Emoncms code is released under the GNU Affero General Public License.
 See COPYRIGHT.txt and LICENSE.txt.

 ---------------------------------------------------------------------
 Emoncms - open source energy visualisation
 Part of the OpenEnergyMonitor project:
 http://openenergymonitor.org
 */

/*
 * Shown when not dashboards exists and want to list
 */ 
function show_nodashboards_message()
{
  // TODO optimize output format (now is better readable)
  echo '<div class="alert alert-block">';
  echo '<h4 class="alert-heading">No dashboards created</h4>';
  echo 'Maybe you would like to add your first dashboard using the <a href="#" onclick="';
  echo "$.ajax({type : 'POST',url :  path + 'dashboard/new.json  ',data : '',dataType : 'json',success : location.reload()});";
  echo '"><i class="icon-plus-sign"></i></a> button';
  echo '</div>';
} 

/*
 * DB Settings error
 * TODO: bootstrap style
 */
function show_dbsettingserror_message()
{
  echo '<div class="alert alert-block">';
  echo '<h4 class="alert-heading">BD Settings error</h4>';
  echo 'Please, check database settings.php file';
  echo '</div>';
}

/*
 * No settings.php file found
 */
function show_nosettingsfile_message()
{
  echo '<div class="alert alert-block">';
  echo '<h4 class="alert-heading">No settings.php file found</h4>';
  echo 'Copy and modify default.settings.php to settings.php';
  echo '</div>'; 
}

?>