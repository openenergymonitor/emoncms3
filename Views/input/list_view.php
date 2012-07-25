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

<script type="text/javascript" src="<?php print $path; ?>Includes/flot/jquery.min.js"></script>

<h2><?php echo _("Inputs"); ?></h2>
<div id="inputlist"></div>

<script type="text/javascript">

  var path =  "<?php echo $path; ?>";
  var inputs = <?php echo json_encode($inputs); ?>;

  update_list();
  setInterval(update_list,2000);

  function update_list()
  {
    $.ajax({                                      
      url: path+"input/list.json",                
      dataType: 'json',
      async: false,
      success: function(data) 
      { 
        inputs = data; 

        var i = 0;
        var out = "<table class='catlist'><tr><th><?php echo _('Name'); ?></th><th><?php echo _('Updated'); ?></th><th><?php echo _('Value'); ?></th></tr>";

        if (inputs.length==0) {
          out += "</table><table class='catlist'><tr class='d0' ><td>You have no inputs</td></tr></table>";
        }

        for (z in inputs)
        {
          i++;
          out += "<tr class='d"+(i & 1)+"' >";
          out += "<td><form action='../process/list.html' method='get' style='margin:0px;'><input type='hidden' name='inputid' value='"+inputs[z][0]+"'><input type='submit' value='"+inputs[z][1]+"' class='button05' style='width:150px'/ ></form></td>";

          var now = (new Date()).getTime();
          var update = (new Date(inputs[z][2])).getTime();
          var lastupdate = (now-update)/1000;

          var secs = (now-update)/1000;
          var mins = secs/60;
          var hour = secs/3600

          var updated = secs.toFixed(0)+"s ago";
          if (secs>180) updated = mins.toFixed(0)+" mins ago";
          if (secs>(3600*2)) updated = hour.toFixed(0)+" hours ago";
          if (hour>24) updated = "inactive";

          var color = "rgb(255,125,20)";
          if (secs<60) color = "rgb(240,180,20)";
          if (secs<25) color = "rgb(50,200,50)";
          out += "<td style='color:"+color+";'>"+updated+"</td><td>"+inputs[z][3]+"</td></tr>";
        }

        out += "</table>";
        out += "<br><a href='../process/autoconfigure'>Autoconfigure inputs</a>";
        $("#inputlist").html(out);
      }
    });
  }
</script>
