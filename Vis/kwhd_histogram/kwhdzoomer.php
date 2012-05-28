
<html>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
 <!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  -->
  <?php 

  $path = dirname("http://".$_SERVER['HTTP_HOST'].str_replace('Vis/kwhd_histogram', '', $_SERVER['SCRIPT_NAME']))."/";

  $power = $_GET['power'];
  $kwhd = $_GET['kwhd'];
  if (isset($_GET['whw'])) {$whw = $_GET['whw'];} else {$whw = 0;}	// Histogram feed
  $apikey = $_GET['apikey'];
  ?>

  <head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--[if IE]><script language="javascript" type="text/javascript" src="<?php echo $path; ?>Vis/flot/excanvas.min.js"></script><![endif]-->
    <script language="javascript" type="text/javascript" src="<?php echo $path; ?>Vis/flot/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $path; ?>Vis/flot/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $path;?>Vis/flot/jquery.flot.selection.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $path; ?>Vis/flot/date.format.js"></script>
    <script language="javascript" type="text/javascript" src="kwhd_functions.js"></script>
    <script language="javascript" type="text/javascript" src="view.js"></script>
    <script language="javascript" type="text/javascript" src="graphs.js"></script>
    <script language="javascript" type="text/javascript" src="info.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $path; ?>Vis/flot/scripts/inst.js"></script>

  </head>
  <body style="margin: 0px; padding:0px; font-family: arial;">

    
    <div id="test" style="height:100%; width:100%; position:relative; ">
      <div id="placeholder" style="font-family: arial; position:absolute; top: 40px; left:60px;"></div>

      <div id="loading" style="position:absolute; top:40px; left:60px; width:100%; height:100%; background-color: rgba(255,255,255,0.5);"></div>

      <div style="position:absolute; top:0px; left:65px; font-size:18px;"><b><span id="out2"></span>
      </b><span style="font-size:12px;"> (Hover for info, press to zoom in. Histogram: 
      <input id="enableHistogram" type="checkbox" checked>)
      </span></div>
      <h2 style="position:absolute; top:40px; left:80px;"><span id="out"></span></h2>
     
      <p id="bot_out" style="position:absolute; bottom:-10px; left:65px; font-size:18px; font-weight:bold;"></p>

      <b><p style="position:absolute; top: 200px; left:0px;"><span id="axislabely"></span></p>
     <p style="position:absolute; bottom: 40px; left:450px;"><span id="axislabelx">Date / Time</span></p></b>
  
    <div id="return_ctr" style="position:absolute; top:0px; right:10px;">
        <input id="return" type="button" value="Back" style="font-size:18px; height:40px;"/>
   </div>

      <div id="inst-buttons" style="position:absolute; top:55px; right:20px;">
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
      var kwhd = <?php echo $kwhd; ?>;   
      var whw = <?php echo $whw; ?>;    
      var power = <?php echo $power; ?>;    
      var path = "<?php echo $path; ?>";  
      var apikey = "<?php echo $apikey; ?>";  

      $('#placeholder').width($('#test').width()-60);
      $('#placeholder').height($('#test').height()-120);
      $('#loading').hide();
      $('#inst-buttons').hide();

      var data = [];
      var days = [];
      var months = [];
      var years = [];

      var view = 0;
      var last_view = -1;

      // Global instantaneous graph variables
      var start, end;
      var feedid = power;
      var paverage = 0, kwhWindow = 0;

      var price =0.14;

      var bot_kwhd_text = "";
      //--------------------------------------------------------------
      // 1) GET ALL KWHD DATA
      //--------------------------------------------------------------
      $.ajax({                                      
        url: path+"feed/data.json",                         
        data: "&apikey="+apikey+"&id="+kwhd+"&start=0&end=0&res=1",
        dataType: 'json',                           
        success: function(data_in) 
        {
          data = data_in;
          tkwh = 0;
          ndays=0;
          for (z in data)
          {
            tkwh += parseFloat(data[z][1]);
            ndays++;
          }

          bot_kwhd_text = "Total: "+(tkwh).toFixed(0)+" kWh : £"+(tkwh*price).toFixed(0) + " | Average: "+(tkwh/ndays).toFixed(1)+ " kWh : £"+((tkwh/ndays)*price).toFixed(2)+" | £"+((tkwh/ndays)*price*7).toFixed(0)+" a week, £"+((tkwh/ndays)*price*365).toFixed(0)+" a year | Unit price: £"+price;

          years = get_years(data);
          //set_annual_view();

          months = get_months_year(data,2012);
          //set_monthly_view();

          days = get_last_30days(data);
          set_last30days_view();
        }
      });

        //--------------------------------------------------------------
        // Zoom in on bar click
        //--------------------------------------------------------------
        $("#placeholder").bind("plotclick", function (event, pos, item)
        {
          if (item!=null)
          {
       		if (($("#enableHistogram:checked").length < 1) || (whw == 0)) {
	            if (view==2) set_inst_view(item.datapoint[0]);
	
	            if (view==1)
	            {
	              var d = new Date(); d.setTime(item.datapoint[0]);
	              days = get_days_month(data,d.getMonth(),d.getFullYear());
	              set_daily_view();
              //        $('#enableHistogram').attr('checked'); 
	            }
	
	            if (view==0)
	            {
	              var d = new Date(); d.setTime(item.datapoint[0]);
	              months = get_months_year(data,d.getFullYear());
	              set_monthly_view();
	            }
			} else {  
				// Histogram view can be of any date range.
	            if (view==2)
	            {
	              	start = item.datapoint[0];
              		end = item.datapoint[0] + 3600000 * 24;
	            }
	
	            if (view==1)
	            {
	              var d = new Date(); d.setTime(item.datapoint[0]);
	              start = Date.UTC(d.getFullYear(),d.getMonth(),0);
	              end = Date.UTC(d.getFullYear(),d.getMonth()+1,0);
	            }
	
	            if (view==0)
	            {
	              var d = new Date(); d.setTime(item.datapoint[0]);
	              start = Date.UTC(d.getFullYear(),0,1);
	              end = Date.UTC(d.getFullYear()+1,0,1);
	            }
	            set_histogram_view(start,end);
			}
          }
        });

        //--------------------------------------------------------------
        // Return button click
        //--------------------------------------------------------------
        $("#return").click(function (){
          if (last_view != -1) {view = last_view+1; last_view = -1;}
          if (view==1) set_annual_view();
          if (view==2) set_monthly_view();
          if (view==3) set_daily_view();
          if (view==4) set_daily_view();
        });
    
        //--------------------------------------------------------------
        // Info label
        //--------------------------------------------------------------
        $("#placeholder").bind("plothover", function (event, pos, item)
        {
          if (item!=null)
          {
            var d = new Date();
            d.setTime(item.datapoint[0]);
            var mdate = new Date(item.datapoint[0]);

            if (view==0) $("#out").html(item.datapoint[1].toFixed(0)+" kWh | "+mdate.format("yyyy")+" | "+(item.datapoint[1]/years.days[item.dataIndex]).toFixed(1)+" kWh/d");
            if (view==1) $("#out").html(item.datapoint[1].toFixed(0)+" kWh | "+mdate.format("mmm yyyy")+" | "+(item.datapoint[1]/months.days[item.dataIndex]).toFixed(1)+" kWh/d ");
            if (view==2) $("#out").html(item.datapoint[1].toFixed(1)+" kWh | £"+(item.datapoint[1]*price).toFixed(2)+" | £"+(item.datapoint[1]*price*365).toFixed(0)+"/y | "+mdate.format("dS mmm yyyy"));
            if (view==3) $("#out").html(item.datapoint[1].toFixed(0)+" W");
            if (view==5) $("#out").html(item.datapoint[1].toFixed(1)+" kWh | £"+(item.datapoint[1]*price).toFixed(2)+" | "+item.datapoint[0]+" W");
          }
        });


     //--------------------------------------------------------------------------------------
     // Graph zooming
     //--------------------------------------------------------------------------------------
     $("#placeholder").bind("plotselected", function (event, ranges) 
     {
       // clamp the zooming to prevent eternal zoom
       if (ranges.xaxis.to - ranges.xaxis.from < 0.00001) ranges.xaxis.to = ranges.xaxis.from + 0.00001;
       if (ranges.yaxis.to - ranges.yaxis.from < 0.00001) ranges.yaxis.to = ranges.yaxis.from + 0.00001;
       start = ranges.xaxis.from;					//covert into usable time values
       end = ranges.xaxis.to;						//covert into usable time values
       var res = getResolution(start, end);
       vis_feed_data(apikey,power,start,end,res);			//Get new data and plot graph
     });

     //----------------------------------------------------------------------------------------------
     // Operate buttons
     //----------------------------------------------------------------------------------------------
     $("#zoomout").click(function () {inst_zoomout();});
     $("#zoomin").click(function () {inst_zoomin();});
     $('#right').click(function () {inst_panright();});
     $('#left').click(function () {inst_panleft();});
     $('.time').click(function () {inst_timewindow($(this).attr("time"));});

	 $("#enableHistogram").click(function() 
	 {
		if (whw == 0)
		{
			alert("The whw parameter must be set in the dashboard to a histogram feed id.");
            $('#enableHistogram').removeAttr('checked'); 
		}
	 });
	 
     function on_inst_graph_load()
     {
       $('#loading').hide();
       $("#out").html("");

       var datetext = "";
       if ((end-start)<3600000*25) {var mdate = new Date(start); datetext = " | "+mdate.format("dS mmm yyyy")}
            
       $("#bot_out").html(kwhWindow+"kWh | £"+(kwhWindow*price).toFixed(2)+datetext)
     }



    </script>
  </body>
</html>

