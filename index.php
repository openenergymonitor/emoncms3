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

  require "Includes/core.inc.php";
  emon_session_start();

  //error_reporting(E_ALL);
  ini_set('display_errors','on');
  error_reporting(E_ALL ^ E_NOTICE);

  // Thanks to seanwg for https addition
  $ssl = $_SERVER['HTTPS'];
  echo $ssl;
  $proto = "http";
  if ($ssl == "on") $proto = "https";
  $path = dirname("$proto://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'])."/";

  require "Includes/db.php";
  require "Models/user_model.php";
  require "Models/statistics_model.php";
  $e = db_connect();

  if ($e == 2) {echo "no settings.php"; die;}
  if ($e == 3) {echo "db settings error"; die;}
  if ($e == 4) header("Location: setup.php");

  $q = preg_replace('/[^.\/a-z0-9]/','',$_GET['q']); // filter out all except a-z / . 
  $q = db_real_escape_string($q);		  // second layer
  $args = preg_split( '/[\/.]/',$q);		  // split string at / .

  $controller	= $args[0];
  $action	= $args[1];
  if ($args[2]) $format	= $args[2]; else $format = "html";

  $lang = preg_replace('/[a-z]/','',$_GET['lang']);

  // Multilanguage support
  // Set language from url attribute lang and save it to the session variable
  // The view function in core.inc.php then selects the view depending on the lang session variable
  $lang =  $_GET['lang']; if ($lang=='en') $_SESSION['lang'] = $lang; else $lang = null;
  if (!$_SESSION['lang']) $_SESSION['lang'] = "en";	// Set default language

  $session['read'] = $_SESSION['read'];
  $session['write'] = $_SESSION['write'];
  $session['userid'] = $_SESSION['userid'];
  $session['admin'] = $_SESSION['admin'];
  $session['lang'] = $_SESSION['lang'];

  if ($_GET['apikey']) $session = user_apikey_session_control($_GET['apikey']);

  $output = controller($controller);
  $message = $output['message'];
  $content = $output['content']; 

  if ($format == 'json')
  {
    print $message.$content;
    if (!($message.$content)) print "Sorry, you need a valid apikey or be logged in to see this page";
  }

  if ($format == 'html')
  {
    if ($session['write']){
      $user = view("user/account_block.php", array());
      $menu = view("menu_view.php", array());
    }
    if (!$session['read']) $content = view("user/login_block.php", array());
    print theme("theme/wp/theme.php", array('menu' => $menu, 'user' => $user, 'content' => $content,'message' => $message));
  }

  if ($controller == "api" && $action == "post") inc_uphits_statistics($session['userid']); else inc_dnhits_statistics($session['userid']);
  
  //----------------------------------------------------
?>
