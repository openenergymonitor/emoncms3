<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->

<?php
  global $path;
?>

<script type="text/javascript" src="https://test.mrwire.co.uk/emoncms3/Includes/flot/jquery.min.js"></script>

<h2><?php echo _('Input configuration:   '); echo get_input_name($inputid); ?></h2>
<p><?php echo _('Input processes are executed sequentially with the result being passed back for further processing by the next processor in the input processing list.'); ?></p>

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
      <td><div id="processArg"><input type="text" name="arg" id="procBox" class="processBox" style="width:100px;" /></div></td>
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


<script type="text/javascript">

  var path = "<?php echo $path; ?>";

//  $process = get_process($processid)

function get_process_arg_type()
{
  var out = "";
  $.ajax({
    url: path+"process/query.json?type="+$('select[name="type"]').val(),
    dataType: 'json',
    async: false,
    success: function(data)
    {
      // data[0]=ProcessArg,data[1]="Text description",data[2]=listarray
      out = data[1]+": ";
      switch (data[0]) {
      case 0:
        out += '<input type="text" name="arg" class="processBox" style="width:100px;" />';
        break;
      case 1:
      case 2:
        out +='<select class="processBox" name="arg">'
        for (arg in data[2]) {
          out += '<option value="'+data[2][arg][0]+'">'+data[2][arg][1]+'</option>';
        }
        out +='</select>';
        break;
      }
//      out += "</p>";
      $('#processArg').html(out);
    }
  });
}
$('.processSelect').change(function() {
get_process_arg_type();
});
  

</script>
