<?php
/*
All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org
*/

global $session, $path;
?>

<!------------------------------------------------------------------------------------------
Dashboard related javascripts
------------------------------------------------------------------------------------------->
<script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.js"></script>
<!------------------------------------------------------------------------------------------
Dashboard HTML
------------------------------------------------------------------------------------------->

<script type="application/javascript">
  // Global page vars definition
  var path =   "<?php echo $path; ?>";
</script>

<!-- tool menu TODO:is the same at dashboard_thumb_view so it could be include from one place to share code -->
<div align="right">
  <a href="#" onclick="$.ajax({type : 'POST',url :  path + 'dashboard/new.json  ',data : '',dataType : 'json',success : location.reload()});"><i class="icon-plus-sign"></i></a>
  <a href="<?php echo $path; ?>dashboard/thumb"><i class="icon-th-large"></i></a>
  <a href="<?php echo $path; ?>dashboard/list"><i class="icon-th-list"></i></a>
</div>

<!--
<ul class="thumbnails">
<?php foreach ($dashboards as $dashboard) { ?>
  <li class="span3">
    <div class="thumbnail">
      <img src="<?php echo $path ?>./Views/theme/common/ds.png" alt="">
        <div class="caption">
          <h5><?php echo $dashboard['name']; ?></h5>
          <p><?php echo $dashboard['description']; ?></p>
          <p>
            <a href="#" class="btn btn-danger" onclick="$(window.location).attr('href',path+'dashboard/delete&id=<?php echo $dashboard['id']; ?>')"><?php echo _(Delete); ?></a>
            <a href="#" class="btn" onclick="$(window.location).attr('href',path+'dashboard/view&id=<?php echo $dashboard['id']; ?>')">View</a>
            <a href="#" class="btn" onclick="$(window.location).attr('href',path+'dashboard/ckeditor&id=<?php echo $dashboard['id']; ?>')">ckEditor</a>
            <a href="#" class="btn" onclick="$(window.location).attr('href',path+'dashboard/edit&id=<?php echo $dashboard['id']; ?>')">Draw</a>            
          </p>
        </div>
    </div>
  </li>
  <?php } ?>
</ul>
-->

