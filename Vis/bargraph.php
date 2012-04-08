
<html>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<!----------------------------------------------------------------------------------------------------
  
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

-------------------------------------------------------------------------------------->

 <?php
  error_reporting(E_ALL);
  ini_set('display_errors','On');

  $feedid = $_GET["feedid"];                 //Get the table ID so that we know what graph to draw
  $path = dirname("http://".$_SERVER['HTTP_HOST'].str_replace('Vis', '', $_SERVER['SCRIPT_NAME']))."/";

  $apikey = $_GET["apikey"];


 ?>
 <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--[if IE]><script language="javascript" type="text/javascript" src="<?php echo $path;?>Vis/flot/excanvas.min.js"></script><![endif]-->
    <script language="javascript" type="text/javascript" src="<?php echo $path;?>Vis/flot/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $path;?>Vis/flot/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $path;?>Vis/flot/jquery.flot.selection.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $path; ?>Vis/flot/date.format.js"></script>

 </head>
 <body style="font-family:arial">

    <div id="graph_bound" style="height:100%; width:100%; position:relative; ">
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
        <div id="loading" style="position:absolute; top:0px; left:0px; width:100%; height:100%; background-color: rgba(255,255,255,0.5);"></div>
        <h3 style="position:absolute; top:00px; left:40px;"><span id="stat"></span></h3>
    </div>

   <script id="source" language="javascript" type="text/javascript">
   //--------------------------------------------------------------------------------------
   var feedid = "<?php echo $feedid; ?>";				//Fetch table name
   var path = "<?php echo $path; ?>";
   var apikey = "<?php echo $apikey; ?>";
   //----------------------------------------------------------------------------------------
   // These start time and end time set the initial graph view window 
   //----------------------------------------------------------------------------------------
   var timeWindow = (3600000*24.0*365);				// Load 365 days worth of data 
   var start = ((new Date()).getTime())-timeWindow;		//Get start time
   var end = (new Date()).getTime();				//Get end time

   var paverage;
   var npoints;

   $(function () {

     var placeholder = $("#graph");

     //----------------------------------------------------------------------------------------
     // Get window width and height from page size
     //----------------------------------------------------------------------------------------
     $('#graph').width($('#graph_bound').width());
     $('#graph').height($('#graph_bound').height());
     //----------------------------------------------------------------------------------------

     var graph_data = [];                              //data array declaration

     vis_feed_data(apikey,feedid,start,end,1);

     //--------------------------------------------------------------------------------------
     // Plot flot graph
     //--------------------------------------------------------------------------------------
     
     function plotGraph(start, end)
     {
          $.plot(placeholder,[                    
          {
            data: graph_data ,				//data
            bars: {
	    show: true,
	    align: "center",
            
	    barWidth: 3600*18*1000,
	    fill: true
          }
          }], {
        xaxis: { mode: "time", 
                  min: ((start)),
		  max: ((end))
        },
grid: { show: true, hoverable: true },
       selection: { mode: "x" }
     } ); 
     $('#loading').hide();
     }

     //--------------------------------------------------------------------------------------
     // Fetch Data
     //--------------------------------------------------------------------------------------
     function vis_feed_data(apikey,feedid,start,end,res)
     {
       $('#loading').show();
       $("#stat").html("Loading...  please wait about 5s");
       $.ajax({                                       //Using JQuery and AJAX
         url: path+'feed/data.json',                         
         data: "&apikey="+apikey+"&id="+feedid+"&start="+start+"&end="+end+"&res="+res,
         dataType: 'json',                            //and passes it through as a JSON    
         success: function(data) 
         {
           graph_data = [];   
           graph_data = data;

           timeWindow = (3600000*24.0*7);				//Initial time window
           start = ((new Date()).getTime())-timeWindow;		//Get start time

           plotGraph(start, end);
           $("#stat").html("");
         } 
       });
     }

     placeholder.bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

        var mdate = new Date(item.datapoint[0]);
    
        $("#stat").html((item.datapoint[1]).toFixed(1)+"kWh | "+mdate.format("ddd, mmm dS, yyyy"));
     });

     //--------------------------------------------------------------------------------------
     // Graph zooming
     //--------------------------------------------------------------------------------------
     placeholder.bind("plotselected", function (event, ranges) 
     {
       // clamp the zooming to prevent eternal zoom
       if (ranges.xaxis.to - ranges.xaxis.from < 0.00001) ranges.xaxis.to = ranges.xaxis.from + 0.00001;
       if (ranges.yaxis.to - ranges.yaxis.from < 0.00001) ranges.yaxis.to = ranges.yaxis.from + 0.00001;
        
       start = ranges.xaxis.from;					//covert into usable time values
       end = ranges.xaxis.to;						//covert into usable time values
       plotGraph(start, end);
     });

     //----------------------------------------------------------------------------------------------
     // Operate buttons
     //----------------------------------------------------------------------------------------------
     $("#zoomout").click(function () 
     { 
       var time_window = end - start;
       var middle = start + time_window / 2;
       time_window = time_window * 2;					// SCALE
       start = middle - (time_window/2);
       end = middle + (time_window/2);
       plotGraph(start, end);
     });

     $("#zoomin").click(function () 
     {
       var time_window = end - start;
       var middle = start + time_window / 2;
       time_window = time_window * 0.5;					// SCALE
       start = middle - (time_window/2);
       end = middle + (time_window/2);
       plotGraph(start, end);
     });

     $('#right').click(function () 
     {	
       var laststart = start; var lastend = end;
       var timeWindow = (end-start);
       var shiftsize = timeWindow * 0.2;
       start += shiftsize;
       end += shiftsize;
       plotGraph(start, end);
     });

     $('#left').click(function ()
     {	
       var laststart = start; var lastend = end;
       var timeWindow = (end-start);
       var shiftsize = timeWindow * 0.2;
       start -= shiftsize;
       end -= shiftsize;
       plotGraph(start, end);
     });

     $('.time').click(function () 
     {
       var time = $(this).attr("time");					//Get timewindow from button
       start = ((new Date()).getTime())-(3600000*24*time);			//Get start time
       end = (new Date()).getTime();					//Get end time
       plotGraph(start, end);
     });

     //-----------------------------------------------------------------------------------------------
  });
  //--------------------------------------------------------------------------------------
  </script>

  </body>
</html>  
