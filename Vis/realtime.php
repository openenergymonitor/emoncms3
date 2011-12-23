
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

  $feedid = $_GET["feedid"];
  $path = dirname("http://".$_SERVER['HTTP_HOST'].str_replace('Vis', '', $_SERVER['SCRIPT_NAME']))."/";

  $apikey = $_GET["apikey"];

 ?>

 <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script language="javascript" type="text/javascript" src="<?php echo $path;?>Vis/flot/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $path;?>Vis/flot/jquery.flot.js"></script>
 </head>
 <body style="font-family:arial">

   <!---------------------------------------------------------------------------------------------------
   // Time window buttons
   ---------------------------------------------------------------------------------------------------->


    <div id="graph_bound" style="height:100%; width:100%; position:relative; ">
      <div id="graph"></div>
      <div style="position:absolute; top:20px; right:20px;">
   <button class="viewWindow" time="24">1 day</button>
   <button class="viewWindow" time="12">12 hour</button>
   <button class="viewWindow" time="1.0">1 hour</button>
   <button class="viewWindow" time="0.50">30 mins</button>
   <button class="viewWindow" time="0.25">15 mins</button>
   <button class="viewWindow" time="0.01">1 mins</button>
      </div>
    </div>

   <div class="inc" style="font-size: 10px;"></div>		<!-- Refresh counter placeholder -->
   <div id="stat" style="font-size: 20px; font-family: arial;  font-weight: normal; color: #333;">---</div>

   <script id="source" language="javascript" type="text/javascript">
   //--------------------------------------------------------------------------------------
   var feedid = <?php echo $feedid; ?>;				//Fetch table name
   var path = "<?php echo $path; ?>";
   var apikey = "<?php echo $apikey; ?>";	

   //----------------------------------------------------------------------------------------
   // These start time and end time set the initial graph view window 
   //----------------------------------------------------------------------------------------
   var timeWindow = (3600000*0.1);				//Initial time window
   var start = ((new Date()).getTime())-timeWindow;		//Get start time
   var end = (new Date()).getTime();				//Get end time

   $(function () {

     var placeholder = $("#placeholder");

      $('#graph').width($('#graph_bound').width());
      $('#graph').height($('#graph_bound').height());

     var graph_data = [];                              //data array declaration

     doSome();
     setInterval ( doSome, 2000 );

      function doSome()
      {				//Initial time window
        start = ((new Date()).getTime())-timeWindow;		//Get start time
        end = (new Date()).getTime();				//Get end time

       var res = Math.round( ((end-start)/10000000) );			//Calculate resolution
       if (res<1) res = 1;

        vis_feed_data(apikey,feedid,start,end,res);
      }
      function vis_feed_data(apikey,feedid,start,end,res)
      {
        $.ajax({                                      
          url: path+'feed/data.json',                         
          data: "&apikey="+apikey+"&id="+feedid+"&start="+start+"&end="+end+"&res="+res,
          dataType: 'json',                           
          success: function(data) 
          { 
           $.plot($("#graph"),
              [{data: data, lines: { fill: true }}],
              {xaxis: { mode: "time", localTimezone: true},
              //grid: { show: true, hoverable: true, clickable: true },
              selection: { mode: "xy" }
            });
          } 
        });
      }


     //----------------------------------------------------------------------------------------------
     // Operate buttons
     //----------------------------------------------------------------------------------------------
     $('.viewWindow').click(function () 
     {
       var time = $(this).attr("time");					//Get timewindow from button
       timeWindow = (3600000*time);			//Get start time

     });
     //-----------------------------------------------------------------------------------------------
  });
  //--------------------------------------------------------------------------------------
  </script>

  </body>
</html>  
