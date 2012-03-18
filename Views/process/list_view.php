<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->

<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">
  <h2>Input Configuration:   <?php echo get_input_name($inputid); ?></h2>

  <?php 
  if (isset($input_processlist))
  {
  ?>

  <table class='catlist'><tr><th>Order</th><th>Process</th><th>Arg</th></tr>
  
  <?php $i = 0;
     

          foreach ($input_processlist as $input_process)    		// For all input processes
          {
            $i++;
            echo "<tr class='d" . ($i & 1) . "' >";
            echo "<td>".$i."</td><td>".$input_process[0]."</td><td>".$input_process[1]."</td>";
            echo "</tr>";
          }
        
   ?>
        <tr><td>New</td><td>
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
        <td></td><td></td><td><input type="submit" value="add" class="button06" style="width:100px;"/></td>
        </tr>
        </table>
        </form>

  <?php } ?>

<form action="../input/resetprocess" method="get">
<b>Reset process list?</b>
<input type="hidden" name="inputid" value="<?php echo $inputid; ?>">
<input type="submit" value="Reset" class="button05"/>
</form>

<hr/>

<?php

$name = get_input_name($inputid);

?>

<?php $message = "<h2>Are you sure you want to delete input: ".$name."?</h2>"; ?>

<form action="../confirm" method="post">
<b>Delete input?</b>
<input type="hidden" name="message" value="<?php echo $message; ?>">
<input type="hidden" name="action" value="input/delete">
<input type="hidden" name="id" value="<?php echo $inputid; ?>">
<input type="submit" value="delete" class="button05"/>
</form>

</div>

