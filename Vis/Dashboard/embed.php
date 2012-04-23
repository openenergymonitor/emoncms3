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
    <!--[if IE]><script language="javascript" type="text/javascript" src="../flot/excanvas.min.js"></script><![endif]-->
<script type="text/javascript" src="../flot/jquery.js"></script>
<script type="text/javascript" src="../flot/jquery.flot.js"></script>
<script type="text/javascript" src="common.js"></script>
<script type="text/javascript" src="widgets/dial.js"></script>
<script type="text/javascript" src="widgets/led.js"></script>

    <title>emoncms</title>
  </head>
  <body>

<!------------------------------------------------------------------------------------------
  Dashboard HTML
------------------------------------------------------------------------------------------->

    <div id="page"></div>
   
<script>

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
              
              $("."+newstr).html(value);
              assoc[newstr] = value*1;
              feedids[newstr] = data[z][0];
            }

            draw_graphs(feedids, path,apikey_read);
  
            // Calls specific page javascript update function for any in page javascript
            if(typeof page_js_update == 'function') { page_js_update(assoc); }
            //--------------------------------------------------------------------------

          }  // End of data return function
        });  // End of AJAX function

  } // End of update function

function fast_update()
{
	draw_dials(assoc_curve, assoc, firstdraw);
	draw_leds(firstdraw);
}


});

</script>

</body>
</html>
