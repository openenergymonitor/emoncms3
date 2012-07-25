<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->

<?php
  $apikey = $_GET["apikey"];
  global $path, $embed;
?>

<!--[if IE]><script language="javascript" type="text/javascript" src="<?php echo $path; ?>Includes/flot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.flot.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.flot.selection.min.js"></script>

<script language="javascript" type="text/javascript" src="<?php echo $path; ?>Views/vis/common/api.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path; ?>Views/vis/common/inst.js"></script>
 
<?php if (!$embed) { ?>
<h2>Multigraph</h2>
<?php } ?>

    <div id="graph_bound" style="height:400px; width:100%; position:relative; ">
      <div id="graph"></div>
      <div style="position:absolute; top:20px; right:20px;">

        <input class="time" type="button" value="D" time="1"/>
        <input class="time" type="button" value="W" time="7"/>
        <input class="time" type="button" value="M" time="30"/>
        <input class="time" type="button" value="Y" time="365"/> | 

        <input id="zoomin" type="button" value="+"/>
        <input id="zoomout" type="button" value="-"/>
        <input id="left" type="button" value="<"/>
        <input id="right" type="button" value=">"/>

      </div>
    </div>

<script id="source" language="javascript" type="text/javascript">

  $('#graph').width($('#graph_bound').width());
  $('#graph').height($('#graph_bound').height());

  var path = "<?php echo $path; ?>";
  var apikey = "<?php echo $apikey; ?>";

  var timeWindow = (3600000*24.0*7);				//Initial time window
  var start = ((new Date()).getTime())-timeWindow;		//Get start time
  var end = (new Date()).getTime();				//Get end time

  var timeWindowChanged = 0;

  var feedlist = [];
  feedlist[0] = {id: 1, selected: 1, plot: {data: null, label: "power", lines: { show: true, fill: true } } };
  feedlist[1] = {id: 4, selected: 1, plot: {data: null, label: "voltage", lines: { show: true, fill: false }, yaxis:2} };

  vis_feed_data();

  /*

  Handle_feeds

  For all feeds in the feedlist:
  - remove all plot data if the time window has changed
  - if the feed is selected load new data
  - add the feed to the multigraph plot
  - plot the multigraph

  */
  function vis_feed_data()
  {
    var plotdata = [];
    for(var i in feedlist) {
      if (timeWindowChanged) feedlist[i].plot.data = null;
      if (feedlist[i].selected) {        
        if (!feedlist[i].plot.data) feedlist[i].plot.data = get_feed_data(feedlist[i].id,start,end,2);
        if ( feedlist[i].plot.data) plotdata.push(feedlist[i].plot);
      }
    }

    var plot = $.plot($("#graph"), plotdata, {
      grid: { show: true, hoverable: true, clickable: true },
      xaxis: { mode: "time", min: start, max: end },
      selection: { mode: "xy" },
      legend: { position: "nw"}
    });

    timeWindowChanged=0;
  }

  //--------------------------------------------------------------------------------------
  // Graph zooming
  //--------------------------------------------------------------------------------------
  $("#graph").bind("plotselected", function (event, ranges) 
  {
     start = ranges.xaxis.from; end = ranges.xaxis.to;
     timeWindowChanged = 1; vis_feed_data();
  });

  //----------------------------------------------------------------------------------------------
  // Operate buttons
  //----------------------------------------------------------------------------------------------
  $("#zoomout").click(function () {inst_zoomout(); vis_feed_data();});
  $("#zoomin").click(function () {inst_zoomin(); vis_feed_data();});
  $('#right').click(function () {inst_panright(); vis_feed_data();});
  $('#left').click(function () {inst_panleft(); vis_feed_data();});
  $('.time').click(function () {inst_timewindow($(this).attr("time")); vis_feed_data();});
  //-----------------------------------------------------------------------------------------------
</script>

