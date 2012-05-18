
<html>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<!----------------------------------------------------------------------------------------------------
  
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    This script creates visualises an all-time histogram from a histogram feed.
    
    On line 92 it requests from the get feed data api a start time of 0 end time of 0 and full resolution:
    
    &start=0&end=0&res=1

    These arguments tells the get feed data model to fetch all data and sum all energy used over the different power segments.
 
    

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
 </head>
 <body style="font-family:arial">

    <div id="graph_bound" style="height:100%; width:100%; position:relative; ">
      <div id="graph"></div>
    
        <div id="loading" style="position:absolute; top:0px; left:0px; width:100%; height:100%; background-color: rgba(255,255,255,0.5);"></div>
        <h3 style="position:absolute; top:00px; left:50px;"><span id="stat"></span></h3>
    </div>

   <script id="source" language="javascript" type="text/javascript">
   //--------------------------------------------------------------------------------------
   var feedid = "<?php echo $feedid; ?>";				//Fetch table name
   var path = "<?php echo $path; ?>";
   var apikey = "<?php echo $apikey; ?>";

   $(function () {

     var placeholder = $("#graph");

     //----------------------------------------------------------------------------------------
     // Get window width and height from page size
     //----------------------------------------------------------------------------------------
     $('#graph').width($('#graph_bound').width());
     $('#graph').height($('#graph_bound').height());
     //----------------------------------------------------------------------------------------

     var graph_data = [];                              //data array declaration
     vis_feed_data(apikey,feedid);

     //--------------------------------------------------------------------------------------
     // Plot flot graph
     //--------------------------------------------------------------------------------------
     
     function plotGraph()
     {
          $.plot(placeholder,[                    
          {
            data: graph_data ,				//data
            bars: { show: true, align: "center", barWidth: 50, fill: true }
          }], {
            xaxis: { mode: null }, grid: { show: true, hoverable: true }
          }); 
          $('#loading').hide();
     }

     //--------------------------------------------------------------------------------------
     // Fetch Data
     //--------------------------------------------------------------------------------------
     function vis_feed_data(apikey,feedid)
     {
       $('#loading').show();
       $("#stat").html("Loading...");
       $.ajax({                                       //Using JQuery and AJAX
         url: path+'feed/data.json',                         
         data: "&apikey="+apikey+"&id="+feedid+"&start=0&end=0&res=1",
         dataType: 'json',                            //and passes it through as a JSON    
         success: function(data) 
         {
           graph_data = [];   
           graph_data = data;
           $("#stat").html("");
           plotGraph();
         } 
       });
     }

     placeholder.bind("plothover", function (event, pos, item) {
        if (item!=null) $("#stat").html((item.datapoint[1]).toFixed(2)+" kWh");
     });
  });
  //--------------------------------------------------------------------------------------
  </script>

  </body>
</html>  
