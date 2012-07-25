<?php global $path, $ckeditor; ?>

    <span style="float:left; color:#888; font: 13px/27px sans-serif; font-weight:bold; ">Dashboards:</span>
    <ul class="greydashmenu">
      <?php echo $menu; ?>
      <li><a href="new"><b>+ New</b></a></li>
    </ul>

<?php if ($type=="edit" || $type=="ckeditor") { ?>
    <ul class="greydashmenu" style="float:right; padding-right:5px;">
      <?php if ($type=="ckeditor") { ?> <li><a href="edit?id=<?php echo $id; ?>">Draw</a></li><?php } ?>
      <?php if ($type=="edit" && $ckeditor==true) { ?> <li><a href="<?php echo $path; ?>dashboard/ckeditor?id=<?php echo $id; ?>">CKEditor</a></li><?php } ?>
      <li><a href="<?php echo $path; ?>dashboard/source?id=<?php echo $id; ?>">Source</a></li>
    </ul>
    <span style="float:right; color:#888; font: 13px/27px sans-serif; font-weight:bold; ">Edit:</span>

    <ul class="greydashmenu" style="float:right; padding-right:5px;">
      <li><a href="<?php echo $path; ?>dashboard/view?id=<?php echo $id; ?>">View</a></li>
    </ul>
<?php } ?>


<?php if ($type=="view") { ?>
    <ul class="greydashmenu" style="float:right; padding-right:5px;">
      <li><a href="thumb">Thumb</a></li>
      <li><a href="list">List</a></li>
    </ul>

    <span style="float:right; color:#888; font: 13px/27px sans-serif; font-weight:bold; ">View:</span>

    <ul class="greydashmenu" style="float:right; padding-right:5px;">
      <li><a href="<?php echo $path; ?>dashboard/edit?id=<?php echo $id; ?>">Draw</a></li>
      <?php if ($ckeditor==true) { ?><li><a href="<?php echo $path; ?>dashboard/ckeditor?id=<?php echo $id; ?>">CKEditor</a></li><?php } ?>
      <li><a href="<?php echo $path; ?>dashboard/source?id=<?php echo $id; ?>">Source</a></li>
    </ul>
    <span style="float:right; color:#888; font: 13px/27px sans-serif; font-weight:bold; ">Edit:</span>
<?php } ?>
