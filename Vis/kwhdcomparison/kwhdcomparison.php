<!DOCTYPE html>
<html>
 <!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  -->
  <?php
		$path = dirname("http://".$_SERVER['HTTP_HOST'].str_replace('Vis/kwhdcomparison', '', $_SERVER['SCRIPT_NAME']))."/";
		
		$power = $_GET['power'];
		$kwhd = $_GET['kwhd'];
		$apikey = $_GET['apikey'];
		$currency = $_GET['currency']?$_GET['currency']:'&pound;';
		$pricekwh = $_GET['pricekwh']?$_GET['pricekwh']:0.12;
  ?>
	
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="<?php echo $path; ?>Vis/flot/jquery.js"></script>
		<script type="text/javascript" src="<?php echo $path; ?>Vis/kwhdcomparison/kwhdcomparison_functions.js"></script>
		<script type="text/javascript" src="<?php echo $path; ?>Vis/d3.v2.js"></script>
	</head>
	
	<style type='text/css'>

	.chart {
	  margin-left: 42px;
	  font: 10px sans-serif;
	  shape-rendering: crispEdges;
	}

	.chart div {
	  background-color: #0096ff;
	  text-align: right;
	  padding: 3px;
	  margin: 1px;
	  color: white;
	}

	.chart rect {
	  stroke: #0095ff;
	  fill: #0095ff;
	  stroke-width: 2;
	  fill-opacity: 0.4;
	  stroke-linejoin : round;
	}

	.chart text.bar {
	  fill: white;
	}
	
	body {
	  font: 10px sans-serif;
	}

	.rule line {
	  stroke: #eee;
	  shape-rendering: crispEdges;
	}

	.rule line.axis {
	  stroke: #000;
	}
	
	.slider line {
		stroke: white;
		stroke-width: 10;
		cursor: pointer;
	}
	
	.comparison {
		border-bottom: 1px solid #DFDFDF;
		border-top: 1px solid #DFDFDF;
		text-align: center;
  }

	</style>
	
	<body>
		<script type="text/javascript">
			var kwhd = <?php echo $kwhd; ?>;
			var power = <?php echo $power; ?>;
			var path = "<?php echo $path; ?>";  
			var apikey = "<?php echo $apikey; ?>";
			var price = <?php echo $pricekwh ?>;
			var currency = "<?php echo $currency ?>";
			
			var today = new Date();
			var month = today.getMonth();
			var year = today.getFullYear();
			
			var kwhd1 = 0,
				kwhd2 = 0;
				
			d3.select("body")
				.append("div")
				.attr("id", "container");
				
			var container = d3.select("#container")
							.append("div")
							.attr("id", "charts")
							.attr("style", "float : left; width : 615px; border-right: 1px solid #DFDFDF");
							
			d3.select("#container")
				.append("div")
				.attr("id", "placeholder")
				.attr("style", "float : left; height : 526px; width : 300px;");
				
			var container1 = container
				.append("div")
				.attr("id", "#container1")
				.attr("style", "width : 600px; height : 264px;");
				
			var container2 = container
				.append("div")
				.attr("id", "#container2")
				.attr("style", "width : 600px; height : 264px;");
				
			d3.select("#placeholder")
				.append("div")
				.attr("id", "day1")
				.attr("style", "width : 100%; height : 176px;");
				
			d3.select("#placeholder")
				.append("div")
				.attr("id", "comparisonbox")
				.attr("style", "width : 100%; height : 176px;");
			
			d3.select("#placeholder")
				.append("div")
				.attr("id", "day2")
				.attr("style", "width : 100%; height : 176px;");
			
			plotChart(container1, 1, month-1);
			plotChart(container2, 2, month);
			
			
    </script>
	</body>
	
</html> 
