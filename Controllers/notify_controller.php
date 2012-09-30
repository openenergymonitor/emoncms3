<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

  // no direct access
  defined('EMONCMS_EXEC') or die('Restricted access');

  function notify_controller()
  {
    require "Models/notify_model.php";
    require "Models/feed_model.php";
    require "Models/mail_model.php";
    global $session, $action, $format;

    $userid = $session['userid'];

    // notify/set?feedid=1&onvalue=300&oninactive=1&periodic=1
    if ($action == 'set' && $session['write'])
    {
      $feedid = intval($_GET['feedid']);
      $onvalue = floatval($_GET['onvalue']);
      $oninactive = intval($_GET['oninactive']);
      $periodic = intval($_GET['periodic']);

      $output['content'] = set_notify($userid,$feedid,$onvalue,$oninactive,$periodic);

      if ($format == 'html')
      {
      	header("Location: ../notify/view?id=".$feedid);
	  }
    }

	elseif ($action == 'view' && $session['write'])
    {
      $feedid = intval($_GET['id']);
      $notify = get_notify($userid, $feedid);
      //if ($format == 'json') $output = json_encode($feeds);

      if ($format == 'html') $output['content'] = view("notify_view.php", array('feedid'=>$feedid,'notify'=>$notify));
    }

	elseif ($action == 'setrecipients' && $session['write'])
    {
      $recipients = preg_replace('/[^\w\s-.,@]/','',$_GET["recipients"]);
      set_notify_recipients($userid,$recipients);

      $recipients = get_notify_recipients($userid);

      if ($format == 'html') $output['content'] = view("notify_settings_view.php", array('recipients'=>$recipients));
    }

	elseif ($action == 'settings' && $session['write'])
    {
      $recipients = get_notify_recipients($userid);

      if ($format == 'html') $output['content'] = view("notify_settings_view.php", array('recipients'=>$recipients));
    }

    return $output;
  }

?>