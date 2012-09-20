<?php

/*
 All Emoncms code is released under the GNU Affero General Public License.
 See COPYRIGHT.txt and LICENSE.txt.

 ---------------------------------------------------------------------
 Emoncms - open source energy visualisation
 Part of the OpenEnergyMonitor project:
 http://openenergymonitor.org

 Contributors: Trystan Lea: trystan.lea@googlemail.com, Ildefonso Martínez Marchena
 If you have any questions please get in touch, try the forums here:
 http://openenergymonitor.org/emon/forum
 */

define('EMONCMS_EXEC', 1);

// Process user settings
require "Includes/process_settings.php";

require "Includes/core.inc.php";

require "Includes/enum.php";

require_once "Includes/locale.php";

// Default to http protocol
$proto = "http";

// Detect if we are running HTTPS or proxied HTTPS
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
	// Web server is running native HTTPS
	$proto = "https";
}
elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == "https") {
	// Web server is running behind a proxy which is running HTTPS
	$proto = "https";
}

$path = dirname("$proto://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']) . "/";

require "Includes/db.php";
require "Models/user_model.php";
require "Models/statistics_model.php";

switch(db_connect()) {
  case 0: break;
  case 1: break;  
  case 3: show_dbsettingserror_message(); die ;
  case 4: header("Location: setup.php"); break;
}
  
//---------------------------------------------------------------------------------
// DECODE URL ARGUMENT
//---------------------------------------------------------------------------------
// Init vars
$format = "";
$controller = "";
$action = "";
$subaction = "";
if (isset($_GET['q'])) {
  $q = preg_replace('/[^.\/A-Za-z0-9]/', '', $_GET['q']);
  // filter out all except a-z / .
  $q = db_real_escape_string($q);
  // second layer
  $args = preg_split('/[\/]/', $q);

  // get format (part of last argument after . i.e view.json)
  $lastarg = sizeof($args) - 1;
  $lastarg_split = preg_split('/[.]/', $args[$lastarg]);
  if (count($lastarg_split) > 1) { $format = $lastarg_split[1]; }
  if ($format!="json" && $format!="html") $format = "html";
  $args[$lastarg] = $lastarg_split[0];

  if (count($args) > 0) { $controller = $args[0]; }
  if (count($args) > 1) { $action = $args[1]; }
  if (count($args) > 2) { $subaction = $args[2]; }
}

if (isset($_GET['embed']) && ($_GET['embed']))
	$embed = 1;
else
	$embed = 0;

//---------------------------------------------------------------------------------
// SESSION CONTROL
// if the apikey is set then the session is controlled by the apikey
// otherwise it is controlled by the cookie based php session.
//---------------------------------------------------------------------------------
if (isset($_GET['apikey']) &&($_GET['apikey']))
{
  $session = user_apikey_session_control($_GET['apikey']);
}
else
{
  emon_session_start();
  $session = $_SESSION;
  if (!isset($session['userid'])) $session['userid'] = 0;
}

// Set user language on every page load to avoid apache multithread setlocale error
set_emoncms_lang($session['userid']);

// Set emoncms theme TODO: get from user preferences
$GLOBALS['theme'] = 'basic';

// Redirect to login screen if user is no longer logged in (eg. after session timeout)
if ($controller=='' && $action=='') {
  $controller="user";
  $action="login";

// Don't show login page if user IS logged in.  Redirect them to dashboard list instead.
  if ($session['userid'] > 0) header("Location: ".$path."dashboard/list");
}

//---------------------------------------------------------------------------------
// CREATE OUTPUT CONTENT ARRAY
// All content is stored in the $output array
//---------------------------------------------------------------------------------

// 1) Based on controller
$output = controller($controller);

// 2) If no controller of this name - then try username
if ($output == null)
{
  $userid = get_user_id($controller);
  if ($userid) {
    $subaction = $action;
    $session['userid'] = $userid;
    $session['username'] = $controller;
    $session['read'] = 1; 
    $action = "run";
    $output = controller("dashboard"); 
  }
}

// 3) Add the main menu
$output['mainmenu'] = view("menu_view.php", array());

//---------------------------------------------------------------------------------
// PRINT THE CONTENT
//---------------------------------------------------------------------------------
if ($format == 'json') 
{
  print $output['message'] . $output['content'];
}
elseif ($embed)
{
  print view("theme/".$GLOBALS['theme']."/embed.php", $output);
}
else
{
  print view("theme/".$GLOBALS['theme']."/theme.php", $output);
}

if ($controller == "api" && $action == "post") {
  inc_uphits_statistics($session['userid']);
} else {
  inc_dnhits_statistics($session['userid']);
}

?>
