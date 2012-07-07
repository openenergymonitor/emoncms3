<?php global $path; ?>

<script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.js"></script>

	<script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.flot.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/common2.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/dial2.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/led.js"></script>
        <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/cylinder.js"></script>

<style>
.Container-White {
  background: none repeat scroll 0 0 #FFFFFF;
  border: 1px solid #E5E5E5;
  box-shadow: 0 4px 10px -1px rgba(200, 200, 200, 0.7);
  margin: 0px;
  padding: 0px;
}

.Container-Grey {
  background: none repeat scroll 0 0 #ddd;
  border: 1px solid #ccc;
  box-shadow: 0 4px 10px -1px rgba(200, 200, 200, 0.7);
  margin: 0px;
  padding: 0px;
}

.heading-center {
  font-weight:bold;
  font-size:24px;
  padding-top:20px;
  text-align:center;
}

.heading {
  font-weight:bold;
  font-size:24px;
  padding-top:20px;
}
</style>
<div class='lightbox' >
<div style="margin: 0px auto; text-align:left; width:800px;">

<div style="background-color:#ddd; padding:4px;">
  <span id="widget-buttons"></span>
  <span id="when-selected">
  <button id="options-button">Options</button>
  <button id="delete-button">Delete</button>
  </span>
</div>

<div id="page-container" style="height:400px; position:relative;">
  <div id="page"></div>
  <canvas id="can" width="800px" height="400px" style="position:absolute; top:0px; left:0px; margin:0; padding:0;"></canvas>
</div>

<div id="testo" style="position:absolute; top:100px; left:150px; width:300px; height:200px; background-color:#eee; padding:20px; border: 1px solid #ddd;">
  <div id="box-options"></div>
  <input id='options-save' type='button' value='save'/ >
</div>

</div>
</div>

<script type="text/javascript" src="<?php echo $path; ?>Views/dash/designer.js"></script>
<script type="application/javascript">

  var path = "<?php echo $path; ?>";
  var apikey_read = "<?php echo $apikey_read; ?>";
  $("#testo").hide();

  var widgets = {

    "dial": 
    {
      "offsetx":-80,"offsety":-80,"width":160,"height":160,
      "menu":"Widgets",
      "options":["feed","max","scale","units","type"]
    },

    "cylinder": 
    {
      "offsetx":-80,"offsety":-165,"width":160,"height":330,
      "menu":"Widgets",
      "options":["topfeed","botfeed"]
    },

    "paragraph": 
    {
      "offsetx":-50,"offsety":-30,"width":100,"height":60,
      "menu":"Text",
      "options":["html"],"html":
      "Some text"
    },

    "heading": 
    {
      "offsetx":-50,"offsety":-30,"width":100,"height":60,
      "menu":"Text",
      "options":["html"],"html":
      "Title"
    },

    "heading-center": 
    {
      "offsetx":-50,"offsety":-30,"width":100,"height":60,
      "menu":"Text",
      "options":["html"],"html":
      "Title"
    },

    "Container-White": 
    {
      "offsetx":0,"offsety":0,"width":200,"height":200,
      "menu":"Containers",
      "options":["name"],
      "html":""
    },

    "Container-Grey": 
    {
      "offsetx":-100,"offsety":-180,"width":200,"height":360,
      "menu":"Containers",
      "options":["name"],
      "html":""
    },

    "realtime": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"Visualisations",
      "options":["feedid"],
      "html":""
    },

    "rawdata": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"Visualisations",
      "options":["feedid"],
      "html":""
    },

    "bargraph": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"Visualisations",
      "options":["feedid"],
      "html":""
    },

    "zoom": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"Visualisations",
      "options":["power","kwhd"],
      "html":""
    },

    "simplezoom": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"Visualisations",
      "options":["power","kwhd"],
      "html":""
    },

    "histgraph": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"Visualisations",
      "options":["feedid"],
      "html":""
    },

    "threshold": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"Visualisations",
      "options":["feedid","thresholdA","thresholdB"],
      "html":""
    },

    "orderthreshold": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"Visualisations",
      "options":["feedid","power","thresholdA","thresholdB"],
      "html":""
    },

    "orderbars": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"Visualisations",
      "options":["feedid"],
      "html":""
    },

    "stacked": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"Visualisations",
      "options":["kwhdA","kwhdB"],
      "html":""
    },

    "multigraph": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"Visualisations",
      "options":["clear"],
      "html":""
    },

    "smoothie": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"Visualisations",
      "options":["feedid","ufac"],
      "html":""
    }

  };

  var redraw = 0;
  var reloadiframe = 0;

  var grid_size = 20;

  dashboard_designer("#can",grid_size,widgets);

  show_dashboard();
</script>
