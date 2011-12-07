<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->

<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">

  <h3>API keys</h3>
  <table>
    <tr>
      <td><b>Read only access: </b><?php echo $apikey_read; ?></td>
      <td>
        <form action="" method="post">
          <input type="hidden" name="form" value="newapi_read">
          <input type="submit" value="new" class="button05">
        </form>
      </td>
    </tr>

    <tr>
      <td><b>Write only access: </b><?php echo $apikey_write; ?></td>
      <td>
        <form action="" method="post">
          <input type="hidden" name="form" value="newapi_write">
          <input type="submit" value="new" class="button05">
        </form>
      </td>
    </tr>
  </table>

  <?php
  $testjson = $GLOBALS['path']."api/post?apikey=".$apikey_write."&json={power:252.4,temperature:15.4}"
  ?>

  <p><b>API url: </b><?php echo $GLOBALS['path']; ?>api/post</p>
  <p><b>Example: Copy this to your web browser or send from a nanode: </b><br/><?php echo $testjson; ?></p>
</div>





