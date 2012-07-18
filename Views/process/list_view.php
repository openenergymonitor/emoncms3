<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->
  <h2><?php echo _("Input configuration:   "); ?><?php echo get_input_name($inputid); ?></h2>

  <?php if (isset($input_processlist)) { ?>
  <table class='catlist'><tr><th><?php echo _("Order"); ?></th><th><?php echo _("Process"); ?></th><th><?php echo _("Arg"); ?></th></tr>
  <?php 
    $i = 0;
    foreach ($input_processlist as $input_process)// For all input processes
    {
      $i++;
      echo "<tr class='d" . ($i & 1) . "' >";
      echo "<td>" . $i . "</td><td>" . $input_process[0] . "</td><td>" . $input_process[1] . "</td>";
      echo "</tr>";
    }
  ?>
  <tr><td><?php echo _("New"); ?></td><td>
    <form action="add" method="get">
      <input type="hidden" name="inputid" value="<?php echo $inputid; ?>">
      <select class="processSelect" name="type">
        <?php for ($i=1; $i<=count($process_list); $i++) { ?>
        <option value="<?php echo $i; ?>"><?php echo $process_list[$i][0]; ?></option>
        <?php } ?>
      </select></td>
      <td><input type="text" name="arg" class="processBox" style="width:100px;" /></td>
    </tr>
    <tr>
      <td></td><td></td><td><input type="submit" value="<?php echo _("add"); ?>" class="button06" style="width:100px;"/></td>
    </tr>
  </form>
  </table>
  <?php } ?>

  <form action="../input/resetprocess" method="get">
    <input type="hidden" name="inputid" value="<?php echo $inputid; ?>">
    <input type="submit" value="<?php echo _("Reset process list?"); ?>" class="btn btn-danger"/>
  </form>
  <hr/>

  <?php $name = get_input_name($inputid); ?>

  <?php $message = "<h2>" . _("Are you sure you want to delete input: ") . $name . "?</h2>"; ?>

  <form action="../confirm" method="post">
    <input type="hidden" name="message" value="<?php echo $message; ?>">
    <input type="hidden" name="action" value="input/delete">
    <input type="hidden" name="id" value="<?php echo $inputid; ?>">
    <input type="submit" value="<?php echo _("Delete input?"); ?>" class="btn btn-danger"/>
  </form>
