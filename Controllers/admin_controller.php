<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org


    ADMIN CONTROLLER ACTIONS		ACCESS

    users				write & admin

  */

  function admin_controller()
  {
    require "Models/feed_model.php";
    global $session, $action,$format;

    $output['content'] = "";
    $output['message'] = "";

    //---------------------------------------------------------------------------------------------------------
    // Gets the user list and user memory use
    // http://yoursite/emoncms/admin/users
    //---------------------------------------------------------------------------------------------------------
    if ($action == '' && $session['write'] && $session['admin']) {
      $userlist = get_user_list();
      $i=0; $total_memuse = 0;
      foreach ($userlist as $user) {
        $userlist[$i]['memuse'] = get_user_feeds_size($user['userid']);
        $total_memuse += $userlist[$i]['memuse'];
        $i++;
      }

      usort($userlist, 'user_sort');	// sort by highest memory user first

      $output['content'] = view("admin/admin_view.php", array('userlist'=>$userlist,'total_memuse'=>$total_memuse));
    }

    return $output;
  }


function user_sort($x, $y)
{
 if ( $x['memuse'] == $y['memuse'] )
  return 0;
 else if ( $x['memuse'] > $y['memuse'] )
  return -1;
 else
  return 1;
}

?>
