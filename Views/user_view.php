<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->

<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">

  <h2>User: <?php echo $user['username']; ?></h2>

  <h3>API keys</h3>
  <table>
    <tr>
      <td><b>Read only access: </b><?php echo $user['apikey_read']; ?></td>
      <td>
        <form action="newapiread" method="post">
          <input type="submit" value="new" class="button05">
        </form>
      </td>
    </tr>

    <tr>
      <td><b>Write only access: </b><?php echo $user['apikey_write']; ?></td>
      <td>
        <form action="newapiwrite" method="post">
          <input type="submit" value="new" class="button05">
        </form>
      </td>
    </tr>
  </table>

  <?php
  $testjson = $GLOBALS['path']."api/post?apikey=".$user['apikey_write']."&json={power:252.4,temperature:15.4}"
  ?>

  <p><b>API url: </b><?php echo $GLOBALS['path']; ?>api/post</p>
  <p><b>Example: Copy this to your web browser or send from a nanode: </b><br/><?php echo $testjson; ?> <a href="<?php echo $testjson; ?>">try me</a></p>

<h3>Change password</h3>
<form action="changepass" method="get">
<p><b>Old password:</b><br/>
<input class="inp01" type="password" name="oldpass" style="width:250px"/></p>
<p><b>New password:</b><br/>
<input class="inp01" type="password" name="newpass" style="width:250px"/></p>
<input type="submit" class="button04" value="Change" /> 
</form>

</div>





