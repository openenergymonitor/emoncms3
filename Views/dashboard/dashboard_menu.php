<?php global $path, $ckeditor; ?>

    <span style="float:left; color:#888; font: 13px/27px sans-serif; font-weight:bold; ">Dashboards:</span>

    <ul class="greydashmenu">
      <?php echo $menu; ?>
    </ul>

<div align="right" style="padding:4px;">
  <?php if ($type=="view") { ?>
  <a href="<?php echo $path; ?>dashboard/edit?id=<?php echo $id; ?>"><i class="icon-edit"></i></a>
  <?php if ($ckeditor) { ?><a href="<?php echo $path; ?>dashboard/ckeditor?id=<?php echo $id; ?>"><img src="<?php echo $path; ?>/Includes/editors/images/ckicon.png" style="margin-top:-5px;"/></a><?php } ?>
  <?php } ?>

  <?php if ($type=="edit" || $type=="ckeditor") { ?>
  <a href="<?php echo $path; ?>dashboard/view?id=<?php echo $id; ?>"><i class="icon-eye-open"></i></a>
  <?php } ?>
  <a  data-toggle="modal" href="#myModal"><i class="icon-wrench"></i></a>
  <a href="#" onclick="$.ajax({type : 'POST',url :  path + 'dashboard/new.json  ',data : '',dataType : 'json',success : location.reload()});"><i class="icon-plus-sign"></i></a>
  <a href="<?php echo $path; ?>dashboard/thumb"><i class="icon-th-large"></i></a>
  <a href="<?php echo $path; ?>dashboard/list"><i class="icon-th-list"></i></a>     
</div>
