/*
 All Emoncms code is released under the GNU Affero General Public License.
 See COPYRIGHT.txt and LICENSE.txt.

 ---------------------------------------------------------------------
 Emoncms - open source energy visualisation
 Part of the OpenEnergyMonitor project:
 http://openenergymonitor.org
 */

function slow_update() {
}

function curveValue(start, end, rate) {
	if(!start)
		start = 0;
	return start + ((end - start) * rate);
}

function draw_dials(assoc_curve, assoc, firstdraw) {
	$('.dial').each(function(index) {
		var feed = $(this).attr("feed");
		var maxval = $(this).attr("max");
		var units = $(this).attr("units");
		var scale = $(this).attr("scale");

		assoc_curve[feed] = curveValue(assoc_curve[feed], parseFloat(assoc[feed]), 0.02);
		var val = assoc_curve[feed] * 1;

		var id = "can-" + feed + "-" + index;

		if(!$(this).html()) {// Only calling this when its empty saved a lot of memory! over 100Mb
			$(this).html('<canvas id="' + id + '" width="200px" height="160px"></canvas>');
			// firstdraw = 1;
		}

		if((val * 1).toFixed(1) != (assoc[feed] * 1).toFixed(1) || firstdraw == 1) {//Only update graphs when there is a change to update
			var canvas = document.getElementById(id);
			var ctx = canvas.getContext("2d");
			draw_gauge(ctx, 200 / 2, 100, 80, val * scale, maxval, units);
			firstdraw = 0;
		}
	});
}

function draw_leds(assoc, firstdraw) {
	$('.led').each(function(index) {
		var feed = $(this).attr("feed");
		var val = assoc[feed];
		var id = "canled-" + feed + "-" + index;
		if(!$(this).html()) {// Only calling this when its empty saved a lot of memory! over 100Mb
			$(this).html('<canvas id="' + id + '" width="50px" height="50px"></canvas>');
			firstdraw = 1;
		}

		//  if ( firstdraw == 1){ //Only update graphs when there is a change to update
		var canvas = document.getElementById(id);
		var circle = canvas.getContext("2d");
		draw_led(circle, val);
		firstdraw = 0;
		//  }
	});
}

function draw_graphs(feedids,path,apikey_read)
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
		