<!--
All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org
-->
<?php
global $path;
$_SESSION['editmode'] = TRUE;
?>
<!------------------------------------------------------------------------------------------
Dashboard related javascripts
------------------------------------------------------------------------------------------->
<script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.js"></script>
<script type="application/javascript" src="<?php echo $path; ?>Views/Dashboard/common.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $path; ?>Views/theme/common/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $path; ?>Includes/lib/jquery-ui-1.8.20.custom/css/smoothness/jquery-ui-1.8.20.custom.css" />
<script type="text/javascript" src="<?php echo $path; ?>Includes/lib/jquery-ui-1.8.20.custom/js/jquery-ui-1.8.20.custom.min.js"></script>

<!------------------------------------------------------------------------------------------
Dashboard HTML
------------------------------------------------------------------------------------------->
<div style="text-align:center; width:100%;"></div>
<script type="application/javascript">
  // Global page vars definition
  var path =   "
<?php echo $path; ?>
  ";
  var apikey_read = "
<?php echo $apikey_read; ?>
  ";
  var apikey_write = "
<?php echo $apikey_write; ?>
  ";

  // CKEditor Events and initialization
  $(document).ready(function()
  {
  $(".new-dashboard-button").button();
  $(".delete-dashboard-button").button();
  $(".preview-dashboard-button").button();
  $(".edit-dashboard-button").button();

  $(".new-dashboard-button").click(function(){
  $.ajax({
  type : "POST",
  url :  "
<?php echo $path; ?>
  " + "
  dashboards / new  ",
  data : "",
  dataType : 'json',
  success : location.reload()
  });
  });

  $(".delete-dashboard-button").click(function(){
  $.ajax({
  type : "POST",
  url :  "
<?php echo $path; ?>dashboards/delete",
data : "&content="+this.id,
dataType : 'json',
success : location.reload()
});
});

$(".preview-dashboard-button").click(function(){
window.open( "<?php echo $path; ?>dashboard/view?apikey=<?php echo $apikey; ?>
  &id="+this.id+"&embed=1", null, 'toolbar=yes,location=no,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=' +
  640 + ',height=' + 420 + ',left=' + 80 );
  });

  $(".edit-dashboard-button").click(function(){
  $(window.location).attr('href', '
<?php echo $path; ?>
  dashboard/view&id='+this.id);
  });

  });

</script>

<br>
<div class="new-dashboard-button">
  New dashboard
</div>
<br>

<?php

while ($row = $dashboards -> fetch_array(MYSQLI_ASSOC))
{

  echo '
<div class="dashboard-preview">
  ';
  echo '<img src="' . $path . 'Views/theme/common/ds.png"/>
  <br>
  ';
  echo '
  <br>
  ';
  echo '<span>
    <br>
    <div class="delete-dashboard-button" id="' . $row['id'] . '">
      -
    </div>';
  echo '
    <div class="preview-dashboard-button" id="' . $row['id'] . '">
      preview
    </div>';
  echo '
    <div class="edit-dashboard-button" id="' . $row['id'] . '">
      edit
    </div>
</div></span>';
}
?>
