<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->

<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">
  <h2>Input Configuration:   <?php echo get_input_name($inputsel); ?></h2>



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
        <form action="" method="post">
        <input type="hidden" name="form" value="process">
        <input type="hidden" name="id" value="<?php echo $inputsel; ?>">
        <select class="processSelect" name="sel">

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

</div>

