<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

  <!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  -->


<?php
  $apikey_read = $_GET['apikey'];
  $path = dirname("http://".$_SERVER['HTTP_HOST'].str_replace('Vis/Dashboard', '', $_SERVER['SCRIPT_NAME']))."/";
?>

<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="user-scalable=no, width=device-width" />

    <link rel="stylesheet" type="text/css" href="../../Views/theme/dark/style.css" />

<!------------------------------------------------------------------------------------------
  Dashboard related javascripts
------------------------------------------------------------------------------------------->
<script type="text/javascript" src="../flot/jquery.js"></script>
<script type="text/javascript" src="../flot/jquery.flot.js"></script>
<script type="text/javascript" src="widgets/dial.js"></script>
<script type="text/javascript" src="widgets/led.js"></script>

    <title>emoncms</title>
  </head>
  <body>

<!------------------------------------------------------------------------------------------
  Dashboard HTML
------------------------------------------------------------------------------------------->

    <div id="page"></div>
   
<script type="application/javascript">

$(function() {
  var path = "<?php echo $path; ?>";
  var apikey_read = "<?php echo $apikey_read; ?>";

  var dashboard_content = "";

    //-------------------------------------------------
    // Get dashboard from server
    $.ajax({                                      
      type: "GET",
      url: path+"dashboard/view.json?apikey="+apikey_read,           
      dataType: 'json',   
      success: function(data) 
      {
        dashboard_content = data;
        $("#page").html(data);
      }
    });
    //-------------------------------------------------

  var feedids = [];		// Array that holds ID's of feeds of associative key
  var assoc = [];		// Array for exact values
  var assoc_curve = [];		// Array for smooth change values - creation of smooth dial widget

  var firstdraw = 1;

  update();
  setInterval(update,30000);
  setInterval(fast_update,30);
  setInterval(slow_update,60000);
  slow_update();

  function update()
  {
        $.ajax({                                      
          url: path+"feed/list.json?apikey="+apikey_read,                  
          dataType: 'json',
          success: function(data) 
          { 

            for (z in data)
            {
              var newstr = data[z][1].replace(/\s/g, '-');

              var value = parseFloat(data[z][4]);
              if (value<100) value = value.toFixed(1); else value = value.toFixed(0);
              console.log(newstr);
              $("."+newstr).html(value);
              assoc[newstr] = value*1;
              feedids[newstr] = data[z][0];
            }

            draw_graphs();
  
            // Calls specific page javascript update function for any in page javascript
            if(typeof page_js_update == 'function') { page_js_update(assoc); }
            //--------------------------------------------------------------------------

          }  // End of data return function
        });  // End of AJAX function

  } // End of update function


  function fast_update()
  {
    draw_dials();
    draw_leds();
  }

  function slow_update()
  {
  }

  function curveValue(start,end,rate)
  {
    if (!start) start = 0;
    return start + ((end-start)*rate);
  }

  function draw_dials()
  {
           $('.dial').each(function(index) {
              var feed = $(this).attr("feed");
              var maxval = $(this).attr("max");
              var units = $(this).attr("units");
              var scale = $(this).attr("scale");

              assoc_curve[feed] = curveValue(assoc_curve[feed],parseFloat(assoc[feed]),0.02);
              var val = assoc_curve[feed]*1;

                var id = "can-"+feed+"-"+index;

                if (!$(this).html()) {	// Only calling this when its empty saved a lot of memory! over 100Mb
                  $(this).html('<canvas id="'+id+'" width="200px" height="160px"></canvas>');
                  firstdraw = 1;
                }

              if ((val*1).toFixed(1)!=(assoc[feed]*1).toFixed(1) || firstdraw == 1){ //Only update graphs when there is a change to update
                var canvas = document.getElementById(id);
                var ctx = canvas.getContext("2d");
                draw_gauge(ctx,200/2,100,80,val*scale,maxval,units); firstdraw = 0;
              }
            });
  }

function draw_leds()
  {
           $('.led').each(function(index) {
              var feed = $(this).attr("feed");
              var val = assoc[feed];
	         var id = "canled-"+feed+"-"+index;
                if (!$(this).html()) {	// Only calling this when its empty saved a lot of memory! over 100Mb
                  $(this).html('<canvas id="'+id+'" width="50px" height="50px"></canvas>');
                  firstdraw = 1;
                }

       //   if ( firstdraw == 1){ //Only update graphs when there is a change to update

                var canvas = document.getElementById(id);
                var circle = canvas.getContext("2d");
                draw_led(circle,val); 
			firstdraw = 0;
       //       }
            });
  }

  function draw_graphs()
  {
    $('.graph').each(function(index) {
      var feed = $(this).attr("feed");
      var id = "#"+$(this).attr('id');
      var feedid = feedids[feed];
      $(id).width(200);
      $(id).height(200);

      var data = [];

      var timeWindow = (3600000*12);
      var start = ((new Date()).getTime())-timeWindow;		//Get start time

      var ndp_target = 200;
      var postrate = 5000; //ms
      var ndp_in_window = timeWindow / postrate;
      var res = ndp_in_window / ndp_target;
      if (res<1) res = 1;
      $.ajax({                                      
          url: path+"feed/data.json",                         
          data: "&apikey="+apikey_read+"&id="+feedid+"&start="+start+"&end="+0+"&res="+res,
          dataType: 'json',                           
          success: function(data) 
          { 
             $.plot($(id),
              [{data: data, lines: { fill: true }}],
              {xaxis: { mode: "time", localTimezone: true },
              grid: { show: true }
             });
          } 
      });
    });
  }



});

</script>

</body>
</html>
