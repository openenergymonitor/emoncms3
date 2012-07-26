<?php

/*
 All Emoncms code is released under the GNU Affero General Public License.
 See COPYRIGHT.txt and LICENSE.txt.

 ---------------------------------------------------------------------
 Emoncms - open source energy visualisation
 Part of the OpenEnergyMonitor project:
 http://openenergymonitor.org

 Author: Trystan Lea: trystan.lea@googlemail.com
 If you have any questions please get in touch, try the forums here:
 http://openenergymonitor.org/emon/forum
 */

define('EMONCMS_EXEC', 1);

// Load the debug library for debug purposes ( http://www.firephp.org/ )
//require_once('./Includes/debug/FirePHPCore/fb.php'); ob_start();
$ckeditor = false;	// ckeditor installed

require "Includes/core.inc.php";

//error_reporting(E_ALL);
ini_set('display_errors', 'on');
error_reporting(E_ALL ^ E_NOTICE);

require_once ("Includes/locale.php");

// Thanks to seanwg for https addition
$ssl = $_SERVER['HTTPS'];
echo $ssl;
$proto = "http";
if ($ssl == "on") {
	$proto = "https";
}
$path = dirname("$proto://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']) . "/";

require "Includes/db.php";
require "Models/user_model.php";
require "Models/statistics_model.php";
$e = db_connect();

if ($e == 2) {
	echo "no settings.php";
	die ;
} else if ($e == 3) {
	echo "db settings error";
	die ;
} else if ($e == 4) {
	header("Location: setup.php");
}

//---------------------------------------------------------------------------------
// DECONDE URL ARGUMENT
//---------------------------------------------------------------------------------
$q = preg_replace('/[^.\/a-z0-9]/', '', $_GET['q']);
// filter out all except a-z / .
$q = db_real_escape_string($q);
// second layer
$args = preg_split('/[\/]/', $q);

// get format (part of last argument after . i.e view.json)
$lastarg = sizeof($args) - 1;
$lastarg_split = preg_split('/[.]/', $args[$lastarg]);
$format = $lastarg_split[1];
if ($format!="json" && $format!="html") $format = "html";
$args[$lastarg] = $lastarg_split[0];

$controller = $args[0];
$action = $args[1];
$subaction = $args[2];

if (!$controller) {$controller = "user"; $action = "login";}

if ($_GET['embed'])
	$embed = 1;
else
	$embed = 0;

//---------------------------------------------------------------------------------
// SESSION CONTROL
// if the apikey is set then the session is controlled by the apikey
// otherwise it is controlled by the cookie based php session.
//---------------------------------------------------------------------------------
if ($_GET['apikey'])
{
  $session = user_apikey_session_control($_GET['apikey']);
}
else
{
  emon_session_start();
  $session = $_SESSION;
}


// Set user language on every page load to avoid apache multithread setlocale error
set_emoncms_lang($session['userid']);

// Set emoncms theme TODO: get from user preferences
$GLOBALS['theme'] = 'basic';

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
