<!----------------------------------------------------------------------------------------------------
Thanks to Shervin for contributing this visualisation, see forum thread here:
http://openenergymonitor.org/emon/node/600
----------------------------------------------------------------------------------------------------->
<html>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

    <?php
	$feedid2 = $_GET["feedid2"];
	error_reporting(E_ALL);
	ini_set('display_errors','On');

	$feedid = $_GET["feedid"];
	$ufac = $_GET["ufac"];
	$path = dirname("http://".$_SERVER['HTTP_HOST'].str_replace(
	    'Vis/smoothie', '', $_SERVER['SCRIPT_NAME']))."/";

	$apikey = $_GET["apikey"];

    ?>

    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Graph</title>
	<meta name="viewport" content="width=device-width; 
	    initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta names="apple-mobile-web-app-status-bar-style" 
	    content="black-translucent" />
	
	<style type="text/css">
	    html, body { margin: 0; padding: 0; height: 100%; 
			width:100%; background-color:#000000;}
	    bar { height: 32px; background: red; }
	</style>

	<script language="javascript" type="text/javascript" 
	    src="<?php echo $path;?>Vis/flot/jquery.js"></script>
	<script type="text/javascript" src="smoothie.js"></script>
    </head>
    <body>
	<canvas id="mycanvas" style="position: absolute; width: 100%; 
	    height: 100%; overflow: hidden"></canvas>

	<script id="source" language="javascript" type="text/javascript">
	    var feedid = <?php echo $feedid; ?>;
	    var path = "<?php echo $path; ?>";
	    var apikey = "<?php echo $apikey; ?>";	
	    var ufac = "<?php echo $ufac; ?>";
	    var feedid2 = "<?php echo $feedid2; ?>"; 

	    var smoothie = new SmoothieChart();
	    smoothie.streamTo(document.getElementById("mycanvas"), ufac);

   
   
	    var start = ((new Date()).getTime()) - 10000;
	    var end = (new Date()).getTime();

	    var line1 = new TimeSeries();

	    if (feedid2 != "")
	      var line2 = new TimeSeries();

	    // Used to filter out repeated data (Might be bad)
	    var old	= 0;
	    var old1	= 0;

	    var canvas = document.getElementById('mycanvas'),
			   context = canvas.getContext('2d');
	    window.addEventListener('resize', resizeCanvas, false);

	    function resizeCanvas() {
		canvas.width = window.innerWidth;
		canvas.height = window.innerHeight;
	    }
	    resizeCanvas();

	    $(function () {

		doSome();
		setInterval ( doSome, 2000 );

		function doSome()
		{	
		    start = ((new Date()).getTime()) - 10000;   
		    end = (new Date()).getTime();
		    var res = Math.round( ((end-start)/10000000) );
		    if (res<1) 
			res = 1;

		    vis_feed_data(apikey,feedid,start,end,res,line1,0);
		    if (feedid2 != "")
			vis_feed_data(apikey,feedid2,start,end,res,line2,1);
		}

		function vis_feed_data(apikey,feedid,start,end,res,line,oldref)
		{
		    $.ajax({                                      
			url: path+'feed/data.json',                         
			    data: "&apikey="+apikey+"&id="+feedid+"&start="
			    +start+"&end="+end+"&res="+res,
			dataType: 'json',                           
			success: function(data) 
			{
			    var prev;
			    if (oldref == 0)
				prev = old;
			    else
				prev = old1;

			    if (data[1][1] != prev)
			    {
				line.append(new Date().getTime(), data[1][1]);
				if (oldref == 0)
				    old = data[1][1];
				else
				    old1 = data[1][1];
			    }
			} 
		    });
		}
		
		smoothie.addTimeSeries(line1, 
		{ strokeStyle:'rgb(0, 255, 0)', 
		fillStyle:'rgba(0, 255, 0, 0.4)', lineWidth:3 });
		
		if (feedid2 != "")
		{
		    smoothie.addTimeSeries(line2, 
		    { strokeStyle:'rgb(255, 0, 0)', 
		    fillStyle:'rgba(255, 0, 0, 0.4)', lineWidth:3 });
		}

	    });
	</script>
    
    </body>
</html>  
