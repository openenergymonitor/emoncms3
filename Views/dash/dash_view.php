<?php global $path; ?>

<script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.js"></script>

	<script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.flot.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/common2.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/dial2.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/led.js"></script>
        <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/cylinder.js"></script>

<style>
.whitecontainer {
  background: none repeat scroll 0 0 #FFFFFF;
  border: 1px solid #E5E5E5;
  box-shadow: 0 4px 10px -1px rgba(200, 200, 200, 0.7);
  margin: 0px;
  padding: 0px;
}

.greycontainer {
  background: none repeat scroll 0 0 #ccc;
  border: 1px solid #E5E5E5;
  box-shadow: 0 4px 10px -1px rgba(200, 200, 200, 0.7);
  margin: 0px;
  padding: 0px;
}

.title {
  text-align:center;
}
</style>
<div class='lightbox' >
<div style="margin: 0px auto; text-align:left; width:800px;">

<div style="background-color:#ddd; width:792px; padding:4px;">
  <span id="widget-buttons"></span>
  <button id="options-button">Options</button>
  <button id="delete-button">Delete</button>
</div>

<div style="width:800px; height:400px; position:relative;">
  <div id="page"></div>
  <canvas id="can" width="800px" height="400" style="position:absolute; top:0px; left:0px; margin:0; padding:0;"></canvas>
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
      "options":["feed","max","scale","units","type"]
    },

    "cylinder": 
    {
      "offsetx":-80,"offsety":-165,"width":160,"height":330,
      "options":["topfeed","botfeed"]
    },

    "whitecontainer": 
    {
      "offsetx":0,"offsety":0,"width":200,"height":200,
      "options":["name"],
      "html":""
    },

    "greycontainer": 
    {
      "offsetx":-100,"offsety":-180,"width":200,"height":360,
      "options":["name"],
      "html":""
    },

    "rawdata": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"vis",
      "options":["feedid"],
      "html":""
    },

    "bargraph": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"vis",
      "options":["feedid"],
      "html":""
    },

    "zoom": 
    {
      "offsetx":0,"offsety":0,"width":400,"height":300,
      "menu":"vis",
      "options":["power","kwhd"],
      "html":""
    },

    "title": 
    {
      "offsetx":-50,"offsety":-30,"width":100,"height":60,
      "options":["html"],"html":
      "<h2>Title</h2>"
    }

  };

  var redraw = 0;
  var reloadiframe = 0;

  var grid_size = 20;

  dashboard_designer("#can",grid_size,widgets);

  show_dashboard();
</script>
