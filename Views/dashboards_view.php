<?php
/*
All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org
*/

global $path;
$_SESSION['editmode'] = TRUE;
?>
<!------------------------------------------------------------------------------------------
Dashboard related javascripts
------------------------------------------------------------------------------------------->
<!-- <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.js"></script> -->
<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">
<!------------------------------------------------------------------------------------------
Dashboard HTML
------------------------------------------------------------------------------------------->
<div style="text-align:center; width:100%;"></div>
<script type="application/javascript">
  // Global page vars definition
  var path =   "<?php echo $path; ?>";
  var apikey_read = "<?php echo $apikey_read; ?>";
  var apikey_write = "<?php echo $apikey_write; ?>";
</script>

<br>

<p>
  <a href="#" class="btn btn" onclick="$.ajax({type : 'POST',url :  path + 'dashboards / new  ',data : '',dataType : 'json',success : location.reload()});"><?php echo _('New dashboard') ?></a>
</p>

<br>

<ul class="thumbnails">
<?php while ($row = $dashboards -> fetch_array(MYSQLI_ASSOC)) { ?>
  <li class="span3">
    <div class="thumbnail">
      <img src="<?php echo $path ?>./Views/theme/common/ds.png" alt="">
        <div class="caption">
          <h5><?php echo $row['name']; ?></h5>
          <p><?php echo $row['description']; ?></p>
          <p>
            <a href="#" class="btn btn-danger" onclick="$.ajax({type : 'POST', url : path+'dashboards/delete', data : '&content=<?php echo $row['id']; ?>', dataType : 'json', success : location.reload()});"><?php echo _(Delete); ?></a>
            <a href="#" class="btn" onclick="window.open(path+'dash/view?apikey=<?php echo $apikey_read; ?>'+'&id=<?php echo $row['id']; ?>', null, 'toolbar=yes,location=no,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=' + 640 + ',height=' + 420 + ',left=' + 80 );">View</a>
            <a href="#" class="btn" onclick="$(window.location).attr('href',path+'dashboard/view&id=<?php echo $row['id']; ?>')">ckEditor</a>
            <a href="#" class="btn" onclick="$(window.location).attr('href',path+'dash/edit&id=<?php echo $row['id']; ?>')">Draw</a>            
          </p>
        </div>
    </div>
  </li>
  <?php } ?>
</ul>
</div>
