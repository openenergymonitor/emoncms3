<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->
<?php global $path; ?>

<script type="text/javascript" src="<?php print $path; ?>Vis/flot/jquery.js"></script>

<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">
  <h2>Feeds</h2>

<div id="feedlist">
</div>

</div>

<script type="text/javascript">

  var path = "<?php echo $path; ?>";
  var feeds = <?php echo json_encode($feeds); ?>;

  update_list();
  setInterval(update_list,2000);

  function update_list()
  {
    $.ajax({                                      
      url: path+"feed/list.json",                
      dataType: 'json',
      success: function(data) { feeds = data; 

    var lastfeed;
    var i = 0;
    var out = "<table class='catlist'><tr><th>id</th><th>Name</th><th>Tag</th><th>Size</th><th>Updated</th><th>Value</th></tr>";
    for (z in feeds)
    {
      i++;
      // FEED ID
      if (feeds[z][2] != lastfeed) {out+= "<tr><td></td></tr>"; }
      lastfeed = feeds[z][2];

      out += "<tr class='d"+(i & 1)+"' ><td>"+feeds[z][0]+"</td>";

      // FEED NAME AND BUTTON
      out += "<td><form action='view' method='get'><input type='hidden' name='id' value='"+feeds[z][0]+"'><input type='submit' value='"+feeds[z][1]+"' class='button05' style='width:150px'/ ></form></td>";

      var now = (new Date()).getTime();
      var update = (new Date(feeds[z][3])).getTime();
      var lastupdate = (now-update)/1000;

      var secs = (now-update)/1000;
      var mins = secs/60;
      var hour = secs/3600;

      var updated = secs.toFixed(0)+"s ago";
      if (secs>180) updated = mins.toFixed(0)+" mins ago";
      if (secs>(3600*2)) updated = hour.toFixed(0)+" hours ago";
      if (hour>24) updated = "inactive";

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
