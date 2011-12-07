<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */
  function user_block_controller()
  {
    $form = $_POST['form'];

    if ($form == 'Login')
    {
      $username = db_real_escape_string($_POST['username']);
      $password = db_real_escape_string($_POST['password']);
      $result = user_logon($username,$password);
      if ($result == 0) $error = "Invalid username or password";
    }

    if ($form == 'Register')
    {
      $username = db_real_escape_string($_POST['username']);
      $password = db_real_escape_string($_POST['password']);

      $error = '';
      if (get_user_id($username)!=0) $error .= "Username already exists<br/>";
      if (strlen($username) < 4 || strlen($username) > 30) $error = "Username must be 4 to 30 characters<br/>";
      if (strlen($password) < 4 || strlen($password) > 30) $error = "Password must be 4 to 30 characters<br/>";

      $content = $error;
      if (!$error) {
        create_user($username,$password);
        $result = user_logon($username,$password);
      }
    }

    if ($form == 'logout') user_logout();

    if ($_SESSION['valid']) {
      $name = get_user_name($_SESSION['userid']);
      $content = view("user/account_block.php", array('name' => $name));
      if (!$name) $_SESSION['valid'] = 0;
    }

    //if (!$_SESSION['valid']) $content = view("user/login_block.php", array('error'=>$error));

    return $content;
  }

?>
