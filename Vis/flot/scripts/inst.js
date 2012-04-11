  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

     function vis_feed_data(apikey,feedid,start,end,res)
     {
       var type = 0;
       if(typeof(whw) !== 'undefined' && feedid == whw) type = 1

       $('#loading').show();
       $.ajax({                                       //Using JQuery and AJAX
         url: path+'feed/data.json',                         
         data: "&apikey="+apikey+"&id="+feedid+"&start="+start+"&end="+end+"&res="+res,
         dataType: 'json',                            //and passes it through as a JSON    
         success: function(data) 
         {

           if (data!=0){
           
           paverage = 0;
           npoints = 0;

           for (var z in data)                     //for all variables
           {
             paverage += parseFloat(data[z][1]);
             npoints++;
           }  
             var timeB  = Number(data[0][0])/1000.0;
             var timeA  = Number(data[data.length-1][0])/1000.0;

             var timeWindow = (timeB-timeA);
             var timeWidth = timeWindow / npoints;

             kwhWindow = ((timeWidth * paverage)/3600000).toFixed(1);

           paverage = paverage / npoints;
           
           }
           else
           {
             // no data
           }
           graph_data = [];   
           graph_data = data;
      
           if (type == 0) plotGraph(start, end);
           if (type == 1) plotHistogram(start, end);

           //on_inst_graph_load();
           $("#loading").hide();
         } 
       });
     }

     function plotGraph(start, end)
     {
          $.plot(placeholder,[                    
          {
            color: "#0096ff",
            data: graph_data ,				//data
            lines: { show: true, fill: true }		//style
          }], {
        xaxis: { mode: "time", localTimezone: true,
                  min: ((start)),
		  max: ((end))
        },
        grid: { show: true, hoverable: true, clickable: true },
        selection: { mode: "xy" }
     } ); 
     }

     function plotHistogram(start, end)
     {
    	  barwidth = 50;
          $.plot(placeholder,[                    
          {
            color: "#0096ff",
            data: graph_data ,				//data
            //lines: { show: true, fill: true }		//style
            bars: { show: true,align: "center",barWidth: barwidth,fill: true }
          }], {
        xaxis: { mode: null },
        grid: { show: true, hoverable: true, clickable: true },
        selection: { mode: "xy" }
     } ); 
     }

     function inst_zoomout()
        {
          var time_window = end - start;
          var middle = start + time_window / 2;
          time_window = time_window * 2;					// SCALE
          start = middle - (time_window/2);
          end = middle + (time_window/2);
          var res = getResolution(start, end);
          vis_feed_data(apikey,feedid,start,end,res);			//Get new data and plot graph
        }


        function inst_zoomin()
        {
          var time_window = end - start;
          var middle = start + time_window / 2;
          time_window = time_window * 0.5;					// SCALE
          start = middle - (time_window/2);
          end = middle + (time_window/2);
          var res = getResolution(start, end);
          vis_feed_data(apikey,feedid,start,end,res);			//Get new data and plot graph
        }

        function inst_panright()
        {
          var laststart = start; var lastend = end;
          var timeWindow = (end-start);
          var shiftsize = timeWindow * 0.2;
          start += shiftsize;
          end += shiftsize;
          var res = getResolution(start,end);
          vis_feed_data(apikey,feedid,start,end,res);
        }

        function inst_panleft()
        {
          var laststart = start; var lastend = end;
          var timeWindow = (end-start);
          var shiftsize = timeWindow * 0.2;
          start -= shiftsize;
          end -= shiftsize;
          var res = getResolution(start,end);
          vis_feed_data(apikey,feedid,start,end,res);
        }

        function inst_timewindow(time)
        {
          start = ((new Date()).getTime())-(3600000*24*time);			//Get start time
          end = (new Date()).getTime();					//Get end time
          var res = getResolution(start,end);
          vis_feed_data(apikey,feedid,start,end,res);			//Get new data and plot graph
        }

     function getResolution(start, end)
     {
       var res = Math.round( ((end-start)/8000000) );	//Calculate resolution
       if (res<1) res = 1;
       return res;
     }
