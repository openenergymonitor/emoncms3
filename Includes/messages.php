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
 * Shown when not inputs exists and want to list
 */ 
function show_noinputs_message()
{
  // TODO optimize output format (now is better readable)
  echo '<div class="alert alert-block">';
  echo '<h4 class="alert-heading">No inputs created</h4>';
  echo 'Inputs must be sent by your monitoring device. For more information visit <a href="http://openenergymonitor.org">OpenEnergyMonitor.org</a>';
  echo '</div>';
} 

/*
 * DB Settings error
 * TODO: bootstrap style and set language before database connect try
 */
function show_dbsettingserror_message()
{
  echo '<div class="alert alert-block">';
  echo '<h4 class="alert-heading">'._('BD Settings error').'</h4>';
  echo _('Please, check database settings.php file');
  echo '</div>';
}

/*
 * No settings.php file found
 */
function show_nosettingsfile_message()
{
  echo '<div class="alert alert-block">';
  echo '<h4 class="alert-heading">'._('No settings.php file found').'</h4>';
  echo _('Copy and modify Includes/default.settings.php to Includes/settings.php');
  echo '</div>'; 
}

?>