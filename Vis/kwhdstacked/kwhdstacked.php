
<html>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--
   All Emoncms code is released under the GNU General Public License v3.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->
  <?php $path = "YOUR EMONCMS PATH"; ?>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script language="javascript" type="text/javascript" src="<?php echo $path; ?>flot/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $path; ?>flot/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $path; ?>flot/jquery.flot.stack.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $path; ?>flot/date.format.js"></script>
    <script language="javascript" type="text/javascript" src="kwhd_functions.js"></script>

    <?php
      require "../../Includes/db.php";
      $e = db_connect();

      require "../../Models/feed_model.php";
      $dataA = get_all_feed_data($_GET["kwhdA"]);
      $dataB = get_all_feed_data($_GET["kwhdB"]);
      //$power = $_GET["power"];
    ?>

  </head>
  <body style="margin: 0px; padding:10px; font-family: arial; background-color:rgb(245,245,235);">

    
    <div id="test" style="height:100%; width:100%; position:relative; ">
      <div id="placeholder" style="font-family: arial;"></div>
      <div id="loading" style="position:absolute; top:0px; left:0px; width:100%; height:100%; background-color: rgba(255,255,255,0.5);"></div>
      <h2 style="position:absolute; top:0px; left:40px;"><span id="out"></span></h2>
    </div>

    <script id="source" language="javascript" type="text/javascript">
      var dataA = <?php echo json_encode($dataA); ?>;    
      var dataB = <?php echo json_encode($dataB); ?>;

      //var power = <?php echo $power; ?>;    
      var path = "<?php echo $path; ?>";  

      // API key
      var apikey = 'YOUR APIKEY';

      $(function () 
      {
        $('#placeholder').width($('#test').width());
        $('#placeholder').height($('#test').height());

        $('#loading').hide();
        var view = 0;
 
        var daysA = [];
        var monthsA = [];
        var daysB = [];
        var monthsB = [];

        monthsA = get_months(dataA);
        monthsB = get_months(dataB);
        bargraph(monthsA,monthsB,3600*24*20);

        $("#placeholder").bind("plotclick", function (event, pos, item)
        {
          if (item!=null)
          {
            if (view==1)
            {

            }
            if (view==0)
            {
              var d = new Date();
              d.setTime(item.datapoint[0]);
              daysA = get_days_month(dataA,d.getMonth(),d.getFullYear());
              daysB = get_days_month(dataB,d.getMonth(),d.getFullYear());
              bargraph(daysA,daysB,3600*22);
              view = 1;
              $("#out").html("");
            }
          }
          else
          {
            
            if (view==1) { $("#out").html(""); view = 0; bargraph(monthsA,monthsB,3600*24*20); }     
            if (view==2) { $("#out").html(""); view = 1; bargraph(daysA,daysB,3600*22); }      
          }
        });

        $("#placeholder").bind("plothover", function (event, pos, item)
        {
          if (item!=null)
          {
            var d = new Date();
            d.setTime(item.datapoint[0]);
            var mdate = new Date(item.datapoint[0]);
            if (view==0) $("#out").html(item.datapoint[1].toFixed(1)+" kWh/d | "+mdate.format("mmm yyyy"));
            if (view==1) $("#out").html(item.datapoint[1].toFixed(1)+" kWh/d | "+mdate.format("dS mmm yyyy"));
          }
        });

        function bargraph(dataA,dataB,barwidth)
        {
          $.plot($("#placeholder"), [ {color: "#0096ff", data:dataA}, {color: "#7cc9ff", data:dataB}], 
          {
            series: {
            stack: true,
            bars: { show: true,align: "center",barWidth: (barwidth*1000),fill: true }
            },
  	    grid: { show: true, hoverable: true, clickable: true },
            xaxis: { mode: "time"}
          });
        }
     });
    </script>
  </body>
</html>

