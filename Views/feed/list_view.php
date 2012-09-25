<?php
/*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
*/

	global $path;
?>

<script type="text/javascript" src="<?php print $path; ?>Includes/flot/jquery.min.js"></script>

  <h2>
 		<?php 
 			if ($del) 
 				echo _('Deleted feeds'); 
  		else 
  			echo _('Feeds'); 
  	?>
  </h2>
  
  <div id="feedlist"></div>
  <?php if (!$del) { ?><br><a href="?del=1" class="btn btn-danger"><?php echo _('Deleted feeds'); ?></a><?php } ?>
  <?php if ($del && $feeds) { ?><br><a href="permanentlydelete"><?php echo _('Delete feeds permanently'); ?></a> (no confirmation)<?php } ?>
<?php if (!$del) { ?>
<a href="<?php echo $path.'sync'; ?>" class="btn btn-info"><?php echo _('Sync feeds'); ?></a><?php } ?>

<script type="text/javascript">
  var path =  "<?php echo $path; ?>";
  var feeds = <?php echo json_encode($feeds); ?>;
  var del = <?php echo $del; ?>;

  update_list();
  setInterval(update_list,2000);

  function update_list()
  {
    $.ajax({                                      
      url: path+"feed/list.json?del="+del,                
      dataType: 'json',
      success: function(data) 
      { 
        feeds = data; 

        var lastfeed;
        var i = 0;
        var out = "<table class='catlist'><tr><th>id</th><th><?php echo _('Name'); ?></th><th><?php echo _('Tag'); ?></th><th><?php echo _('Size'); ?></th><th><?php echo _('Updated'); ?></th><th><?php echo _('Value'); ?></th></tr>";

        if (feeds.length==0) {
          out += "</table><table class='catlist'><tr class='d0' ><td><?php echo _('You have no feeds'); ?></td></tr></table>";
        }

        for (z in feeds)
        {
          i++;
          // FEED ID
          if (feeds[z][2] != lastfeed) {out+= "<tr><td></td></tr>"; }
          lastfeed = feeds[z][2];

          out += "<tr class='d"+(i & 1)+"' ><td>"+feeds[z][0]+"</td>";

          // FEED NAME AND BUTTON
          out += "<td><form style='margin:0; padding:0;' action='view' method='get'><input type='hidden' name='id' value='"+feeds[z][0]+"'><input type='submit' value='"+feeds[z][1]+"' class='button05' style='width:150px'/ ></form></td>";

          var now = (new Date()).getTime();
          var update = (new Date(feeds[z][3])).getTime();
          var lastupdate = (now-update)/1000;

          var secs = (now-update)/1000;
          var mins = secs/60;
          var hour = secs/3600;

          var updated = secs.toFixed(0)+'<?php echo _("s ago"); ?>';

          if (secs>180) updated = mins.toFixed(0)+'<?php echo _(' mins ago'); ?>';
          if (secs>(3600*2)) updated = hour.toFixed(0)+'<?php echo _(' hours ago'); ?>';
          if (hour>24) updated = '<?php echo _('inactive'); ?>';

          var color = "rgb(255,125,20)";
          if (secs<60) color = "rgb(240,180,20)";
          if (secs<25) color = "rgb(50,200,50)";

          var value = 0;
          if (feeds[z][4]>10) value = (1*feeds[z][4]).toFixed(1);
          if (feeds[z][4]>100) value = (1*feeds[z][4]).toFixed(0);
          if (feeds[z][4]<10) value = (1*feeds[z][4]).toFixed(2);

          var tag = feeds[z][2];
          if (!tag) tag="";

          out += "<td>"+tag+"</td><td>"+(feeds[z][5]/1000).toFixed(1)+" KiB</td><td style='color:"+color+";'>"+updated+"</td><td>"+value+"</td></tr>";
      }

      out += "</table>";
      $("#feedlist").html(out);
    }
  });
}

</script>
