<?php
// die;
define('EMONCMS_EXEC', 1);

if ($_GET['key'] == "xTC7005d")// IMPORTANT SET THIS TO A UNIQUE PASSWORD OF YOUR CHOICE
{
  // Ensures no one else can run cron
  ini_set('display_errors', 'on');
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

  //---------------------------------------------------------------------------------------------
  // Calculates and updates user total memory use
  require "Models/user_model.php";
  require "Models/statistics_model.php";

  $userlist = get_user_list();
  $total_memuse = 0;
  foreach ($userlist as $user)
  {
    // Changed $userlist[i] to $user (equals in foreach loop)
    $user['memuse'] = get_user_feeds_size($user['userid']);
    $total_memuse += $user['memuse'];
    set_memory_statistics($user['userid'], $user['memuse']);
  }
}
?>
