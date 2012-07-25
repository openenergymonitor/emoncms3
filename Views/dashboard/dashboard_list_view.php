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

require_once "Includes/messages.php";
?>

<script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.min.js"></script>

<script type="application/javascript">
  // Global page vars definition
  var path =   "<?php echo $path; ?>";
</script>

<!-- tool menu TODO:is the same at dashboard_thumb_view so it could be include from one place to share code -->
<div align="right">
  <a href="#" onclick="$.ajax({type : 'POST',url :  path + 'dashboard/new.json  ',data : '',dataType : 'json',success : location.reload()});"><i class="icon-plus-sign"></i></a>
  <a href="<?php echo $path; ?>dashboard/thumb"><i class="icon-th-large"></i></a>
  <!--<a href="<?php echo $path; ?>dashboard/list"><i class="icon-th-list"></i></a>-->
</div>

<?php 
  if (isset($dashboards) && count($dashboards)) { ?>
  <table class='catlist'>
    <tr>
        <th>
          <?php echo _("Id"); ?>
        </th>    
        <th>
          <?php echo _("Dashboard"); ?>
        </th>
        <th>
          <?php echo _("Main"); ?> 
        </th>
        <th>
          <?php echo _("Published"); ?> 
        </th>
        <th>
          <?php echo _("Public"); ?> 
        </th>
        <th>
          <?php echo _("Actions"); ?> 
        </th>      
    </tr>
  
    <?php foreach ($dashboards as $dashboard) { ?>
    <tr class="d0">
      <td>
        <?php echo $dashboard['id']; ?>
      </td>
      <td>
        <div align="left">
          <?php echo $dashboard['name']; ?>       
          <h5><?php echo $dashboard['description']; ?></h5>
        </div>
       </td>
      <td>
        <?php
          if ($dashboard['main']) { ?>             
              <i class='icon-star'></i>
          <?php } else { ?>          
          <a href="#" onclick="$.ajax({type : 'POST',url :  path + 'dashboard/setconf  ',data : 'main=1&id=<?php echo $dashboard['id'] ?>',dataType : 'json',success : location.reload()});"><i class='icon-star-empty'></i></a>
        <?php } ?> 
      </td>
      <td>
        <?php
          if ($dashboard['published']) { ?>           
            <a href="#" onclick="$.ajax({type : 'POST',url :  path + 'dashboard/setconf  ',data : 'published=0&id=<?php echo $dashboard['id'] ?>',dataType : 'json',success : location.reload()});"><i class='icon-ok'></i></a>
          <?php } else { ?>
            <a href="#" onclick="$.ajax({type : 'POST',url :  path + 'dashboard/setconf  ',data : 'published=1&id=<?php echo $dashboard['id'] ?>',dataType : 'json',success : location.reload()});"><i class='icon-remove'></i></a>
          <?php } ?>
      </td>
      <td>
        <?php 
          if ($dashboard['public']) { ?>           
            <a href="#" onclick="$.ajax({type : 'POST',url :  path + 'dashboard/setconf  ',data : 'public=0&id=<?php echo $dashboard['id'] ?>',dataType : 'json',success : location.reload()});"><i class='icon-globe'></i></a>
          <?php } else { ?>
            <a href="#" onclick="$.ajax({type : 'POST',url :  path + 'dashboard/setconf  ',data : 'public=1&id=<?php echo $dashboard['id'] ?>',dataType : 'json',success : location.reload()});"><i class='icon-lock'></i></a>
          <?php } ?>
      </td>  
      <td>
        <div>       
          <a href="#" class="btn btn-danger" onclick="$.ajax({type : 'POST',url :  path + 'dashboard/delete',data : '&id=<?php echo $dashboard['id']; ?>',dataType : 'json',success : location.reload()});"><?php echo _(Delete); ?></a>        
          <a href="#" class="btn" onclick="$(window.location).attr('href',path+'dashboard/view&id=<?php echo $dashboard['id']; ?>')"><?php echo _(View); ?></a>
          <a href="#" class="btn" onclick="$(window.location).attr('href',path+'dashboard/edit&id=<?php echo $dashboard['id']; ?>')"><?php echo _(Draw); ?></a>
<?php if ($ckeditor) { ?><a href="#" class="btn" onclick="$(window.location).attr('href',path+'dashboard/ckeditor&id=<?php echo $dashboard['id']; ?>')"><?php echo _(CKEditor); ?></a><?php } ?>           
        </div>            
      </td>    
    </tr>
    <?php } // end foreach 
  } // endif  
  else show_nodashboards_message(); ?>
</table>


