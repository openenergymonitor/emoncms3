<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->

<?php
  global $path, $session;
?>

<script type="text/javascript" src="https://test.mrwire.co.uk/emoncms3/Includes/flot/jquery.min.js"></script>
<h2><?php echo _('Input configuration:   '); echo get_input_name($inputid); ?></h2>
<p><?php echo _('Input processes are executed sequentially with the result being passed back for further processing by the next processor in the input processing list.'); ?></p>
<?php print_r($input_processlist); ?>
<?php /*
<?php if (isset($input_processlist)) { ?>
<table class='catlist'>
  <tr>
    <th style='width:15%;'><?php echo _("Order"); ?></th>
    <th style='width:35%;'><?php echo _("Process"); ?></th>
    <th style='width:40%;'><?php echo _("Arg"); ?></th>
  </tr>
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

</table>
*/
?>
<div id='processlist'></div>

    <form action="add.json" method="get">
<table class='catlist'>
    <tr><td style='width:15%;'><?php echo _("New"); ?></td>
      <td style='width:35%;'>
      <input type="hidden" name="inputid" value="<?php echo $inputid; ?>">
      <select class="processSelect" name="type">
        <?php for ($i=1; $i<=count($process_list); $i++) { ?>
        <option value="<?php echo $i; ?>"><?php echo $process_list[$i][0]; ?></option>
        <?php } ?>
      </select></td>
      <td style='width:40%;'><div id="newProcessArgField"></div></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><input type="submit" value="<?php echo _("add"); ?>" class="button06" style="width:100px;"/></td>
    </tr>
  </table>
  </form>
  <?php //} ?>

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
var processlist = <?php echo json_encode($input_processlist); ?>;

function update_list()
{
  $.ajax({
      url: path+"process/list.json",
      dataType: 'json',
      async: false,
      success: function(data)
      {
        alert(processlist);
alert(data);
//        processlist = data;

        var i = 0;

        var out="<table class='catlist'><tr><th style='width:15%;'><?php echo _('Order'); ?></th><th style='width:35%;'><?php echo _('Process'); ?></th><th style='width:40%;'><?php echo _('Arg'); ?></th></tr>";

        for (z in processlist)
        {
          i++;
          out += "<tr class='d"+(i & 1)+"' >";
          out += "<td>"+i+"</td><td>"+processlist[z][0]+"</td><td>"+processlist[z][1]+"</td>";
        }
        
        if (processlist.length==0) {
          out += "</table><table class='catlist'><tr class='d0' ><td><?php echo _('You have no processes defined'); ?></td></tr>";
        }
        out +="</table>";
        $('#processlist').html(out);
      }
  });
}
  
function generate_process_arg_box()
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
      $('#newProcessArgField').html(out);
    }
  });
}

$('.processSelect').change(function() {
  generate_process_arg_box();
});

$(document).ready(function() {
  update_list();
  generate_process_arg_box();
}); 

</script>
