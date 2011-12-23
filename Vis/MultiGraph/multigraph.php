<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
   All Emoncms code is released under the GNU General Public License v3.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->
  <?php $path = "YOUR EMONCMS DIRECTORY"; ?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script language="javascript" type="text/javascript" src="<?php echo $path; ?>flot/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $path; ?>flot/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $path; ?>flot/jquery.flot.selection.js"></script>
</head>
<body style="font-family:arial;">
  
<div style="text-align:right;">
<span id="date"></span>
 | Last: 
<button class="timewindow" time="1440" >2 Months</button>
<button class="timewindow" time="720" >Month</button>
<button class="timewindow" time="168" >Week</button>
<button class="timewindow" time="24" >Day</button>
<button class="timewindow" time="1" >Hour</button>
<button class="zoomin" >+</button>
<button class="zoomout" >-</button>
<button class="left" ><</button>
<button class="right" >></button>
</div>

<div id="graph_bound" style="height:100%; width:100%; position:relative; ">
  <div id="placeholder" style="float: left;"></div><br/ >
  <div id="choices" style="margin:10px; float:left; background-color:#eee; width:740px; padding:10px;"></div>
  <p id="out"></p>
</div>


<script id="source" language="javascript" type="text/javascript">

  $(function () {
    var path = "<?php echo $path; ?>";  

    // API key
    var apikey = 'YOUR APIKEY';

    $('#placeholder').width($('#graph_bound').width());
    $('#placeholder').height($('#graph_bound').height());

    //-----------------------------------------------------------------
    // Initial Time window settings
    //-----------------------------------------------------------------
    var start = ((new Date()).getTime())-(3600000*24.0)*5;		//Get start time
    var end = (new Date()).getTime()-(3600000*24.0)*0;		//Get end time

    var resolution;
    
    //-----------------------------------------------------------------
    // FeedList: stores all feed related info required to build dataset
    //-----------------------------------------------------------------
    var feedlist = [ 
                     {id: 2, key: "Power", data: 0, LOD: 1, selected: 1, loaded: 0, style: 1}
                   ];

    //-----------------------------------------------------------------
    // plotdata:    	selected feed data to be plotted
    //-----------------------------------------------------------------
    var plotdata = [];

    //-----------------------------------------------------------------
    // Program
    //-----------------------------------------------------------------

    var timeWindowChanged = 0;
    var timer = new Date().getTime();

    feed_selector();
    handle_feeds();

    //-----------------------------------------------------------------
    // load_Selected_But_Unloaded_Feeds
    //-----------------------------------------------------------------
    function handle_feeds()
    {
      plotdata = [];
      for(var i in feedlist) {
        // 1) Set all feeds to unloaded if time window changes
        if (timeWindowChanged) { feedlist[i].loaded = 0; }
        
        // 2) If feed is selected but unloaded the load the feed
        if (feedlist[i].selected!=0 && feedlist[i].loaded==0) 
        { 
       
          if (feedlist[i].LOD==0) {resolution = 1;} else { resolution = getResolution(start, end); }
          feedlist[i].data = {label: feedlist[i].key, data: getDataLOD(feedlist[i].id,start,end,resolution), yaxis: feedlist[i].selected};
          if (feedlist[i].style==3) feedlist[i].data.bars = { show: true, align: "left", barWidth: 3600*24*1000, fill: true};
          if (feedlist[i].style==2) feedlist[i].data.lines = { show: true, fill: false };
          if (feedlist[i].style==1) feedlist[i].data.lines = { show: true, fill: true };
          
          feedlist[i].loaded = 1;    
        }

        // 3) If feed is selected and loaded then plot the feed
        if (feedlist[i].selected!=0 && feedlist[i].loaded==1) { 
          plotdata.push(feedlist[i].data);
        }
      }
      plotGraph();
      timeWindowChanged=0;
    }
   //-------------------------------------------------------------------------------
   // Get feed data
   //-------------------------------------------------------------------------------
   function getDataLOD(feedID,start,end,resolution)
   {
     var feedIn = [];

     $.ajax({                                    
            url: path+'api/getfeed',                         
            data: "&apikey="+apikey+"&feedid="+feedID+"&start="+start+"&end="+end+"&resolution="+resolution,  
       dataType: 'json',                           
       async: false,
       success: function(datain) { feedIn = datain;}
     });
     return feedIn;
   }

   //-------------------------------------------------------------------------------
   // Plot Graph
   //-------------------------------------------------------------------------------
   function plotGraph()
   {

     var plot = $.plot($("#placeholder"), plotdata, {
         grid: { show: true, hoverable: true, clickable: true },
       //yaxis: { min: 0},
       //y2axis: { min: 0},
       xaxis: { mode: "time", 
                min: ((start)),
		max: ((end))
              },
       selection: { mode: "xy" },
        legend: {
         position: "nw",
       }
       

     });
     return plot;
   }

     //--------------------------------------------------------------------------------------
     // Graph zooming
     //--------------------------------------------------------------------------------------
     $("#placeholder").bind("plotselected", function (event, ranges) 
     {
       var newDate = new Date( );
       newDate.setTime( ranges.xaxis.from + ((ranges.xaxis.to-ranges.xaxis.from)/2));
       dateString = newDate.toUTCString();


       $("#date").html(dateString);
       // clamp the zooming to prevent eternal zoom
       if (ranges.xaxis.to - ranges.xaxis.from < 0.00001) ranges.xaxis.to = ranges.xaxis.from + 0.00001;
       if (ranges.yaxis.to - ranges.yaxis.from < 0.00001) ranges.yaxis.to = ranges.yaxis.from + 0.00001;
        
       var laststart = start; var lastend = end;
       var lastres = getResolution(start,end);
       start = ranges.xaxis.from;					//covert into usable time values
       end = ranges.xaxis.to;						//covert into usable time values

       if (start>laststart && end<lastend && lastres==1) timeWindowChanged = 0;
       else { timeWindowChanged = 1; }

       handle_feeds();
     });

   //-------------------------------------------------------------------------------
   // Zoomin
   //-------------------------------------------------------------------------------
   $('.zoomin').click(function () {	

   var laststart = start; var lastend = end;
   var lastres = getResolution(start,end);

   var timeWindow = (end-start);
   var midpoint = start + (timeWindow/2.0);
   timeWindow = timeWindow / 2;				//Initial time window
   start = midpoint - (timeWindow/2.0);
   end = midpoint + (timeWindow/2.0);			//Get end time

       if (start>laststart && end<lastend && lastres==1) timeWindowChanged = 0;
       else { timeWindowChanged = 1; }

       handle_feeds();

   });

   //-------------------------------------------------------------------------------
   // Zoomout
   //-------------------------------------------------------------------------------
   $('.zoomout').click(function () {
   var timeWindow = (end-start);
   var midpoint = start + (timeWindow/2.0);
   timeWindow = timeWindow * 2;				//Initial time window
   start = midpoint - (timeWindow/2.0);
   end = midpoint + (timeWindow/2.0);
       timeWindowChanged = 1;
       handle_feeds();
   });

   //-------------------------------------------------------------------------------
   // Zoomin
   //-------------------------------------------------------------------------------
   $('.right').click(function () {	

   var laststart = start; var lastend = end;
   var lastres = getResolution(start,end);

   var timeWindow = (end-start);
   var shiftsize = timeWindow * 0.2;
   start += shiftsize;
   end += shiftsize;

       timeWindowChanged = 1;

       handle_feeds();

   });

   //-------------------------------------------------------------------------------
   // Zoomin
   //-------------------------------------------------------------------------------
   $('.left').click(function () {	

   var laststart = start; var lastend = end;
   var lastres = getResolution(start,end);

   var timeWindow = (end-start);
   var shiftsize = timeWindow * 0.2;
   start -= shiftsize;
   end -= shiftsize;

       timeWindowChanged = 1;

       handle_feeds();

   });

   //-------------------------------------------------------------------------------
   // Day, Week, Month buttons
   //-------------------------------------------------------------------------------
   $('.timewindow').click(function () {
   var hours = Number($(this).attr("time"));
   var timeWindow = (3600000*hours);				//Initial time window
   start = ((new Date()).getTime())-timeWindow;		//Get start time
   end = (new Date()).getTime();				//Get end time
       timeWindowChanged = 1;
       handle_feeds();
   });

  $("#placeholder").bind("plotclick", function (event, pos, item)
     {
       if (item!=null)
       {
        //start = item.datapoint[0];
        var timeWindow = (3600000*24.0);				//Initial time window
        start = item.datapoint[0];
        end = item.datapoint[0]+timeWindow;

       timeWindowChanged = 1;
       handle_feeds();
       }
     });

   function getResolution(start, end)
   {
     var res = Math.round( ((end-start)/8000000) );	//Calculate resolution
     if (res<1) res = 1;
     return res;
   }

   function feed_selector()
   {
      var choiceContainer = $("#choices");
      var out = "<table border='0'><tr><th>Select Feeds</th><th width=60px>Left vs</th><th>Right</th></tr>";

      for(var i in feedlist) {

        var checkedA = '',checkedB = '';
        if (feedlist[i].selected!=0) {
          if (feedlist[i].selected==1) checkedA = 'checked="checked"';
          if (feedlist[i].selected==2) checkedB = 'checked="checked"';
        }
        out += '<tr><td width=200px><label>' + feedlist[i].key + '</label></td>';
        out += '<td><input type="checkbox" id="' + feedlist[i].id + '"' + checkedA + 'axis="1" ></td>';
        out += '<td><input type="checkbox" id="' + feedlist[i].id + '"' + checkedB + 'axis="2" ></td>';
      }
      out += "</table>";
      out += "<p style='font-size:12px'><i>Loading a feed can take time.. </i></p>";
      choiceContainer.append(out);

      choiceContainer.find("input[type='checkbox']").click(function() {
        var id = $(this).attr("id");
        var axis = $(this).attr("axis");
        var checked = $(this).attr("checked");

        if (axis==1 && checked==true) choiceContainer.find("input[id='"+id+"'][axis='2']").removeAttr("checked");
        if (axis==2 && checked==true) choiceContainer.find("input[id='"+id+"'][axis='1']").removeAttr("checked");

        for(var i in feedlist) {

          if (feedlist[i].id==id && checked==true) {feedlist[i].selected = Number(axis); feedlist[i].data.yaxis = Number(axis);}
          if (feedlist[i].id==id && checked==false) {feedlist[i].selected = 0;}
          timeWindowChanged = 0;
          handle_feeds();
        }
      });

   }

  });

</script>

</body>
</html>
