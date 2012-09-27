<?php
  // die;
  define('EMONCMS_EXEC', 1);

  // IMPORTANT SET THIS TO A UNIQUE PASSWORD OF YOUR CHOICE
  if ($_GET['key'] != "xTC7005d") die;

  // Ensures no one else can run cron
  ini_set('display_errors', 'on');
  error_reporting(E_ALL ^ E_NOTICE);

  // Process user settings
  require "Includes/process_settings.php";

  require "Models/notify_model.php";
  require "Models/feed_model.php";
  require "Models/mail_model.php";

  require "Includes/db.php";

  switch(db_connect()) {
    case 0: break;
    case 1: break;  
    case 3: show_dbsettingserror_message(); die ;
  }

  $users = get_notify_users();

  foreach ($users as $user)
  {
    run_notify($user['userid']);
  }
?>
