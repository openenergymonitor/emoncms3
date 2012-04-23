  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */
 		 

		
function slow_update()
{
}

function curveValue(start,end,rate)
{
	if (!start) start = 0;
		return start + ((end-start)*rate);
}

function draw_dials(assoc_curve, assoc)
{
	$('.dial').each(function(index) {
    	var feed = $(this).attr("feed");
		var maxval = $(this).attr("max");
		var units = $(this).attr("units");
		var scale = $(this).attr("scale");
	
		assoc_curve[feed] = curveValue(assoc_curve[feed],parseFloat(assoc[feed]),0.02);
		var val = assoc_curve[feed]*1;

                var id = "can-"+feed+"-"+index;

            //    if (!$(this).html()) {	// Only calling this when its empty saved a lot of memory! over 100Mb
                  $(this).html('<canvas id="'+id+'" width="200px" height="160px"></canvas>');
                  firstdraw = 1;
              //  }

              if ((val*1).toFixed(1)!=(assoc[feed]*1).toFixed(1) || firstdraw == 1){ //Only update graphs when there is a change to update
                var canvas = document.getElementById(id);
                var ctx = canvas.getContext("2d");
                draw_gauge(ctx,200/2,100,80,val*scale,maxval,units); firstdraw = 0;
              }
            });
  }
  