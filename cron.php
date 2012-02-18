<?php

if ($_GET['key'] == "xTC7005d")		// IMPORTANT SET THIS TO A UNIQUE PASSWORD OF YOUR CHOICE
{					// Ensures no one else can run cron
  ini_set('display_errors','on');
  error_reporting(E_ALL ^ E_NOTICE);

  require "Models/notify_model.php";
  require "Models/feed_model.php";
  require "Models/mail_model.php";

  require "Includes/db.php";
  $e = db_connect();

  $users = get_notify_users();

  foreach ($users as $user)
  {
    run_notify($user['userid']);
  }
}
?>
