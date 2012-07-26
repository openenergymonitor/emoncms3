<?php global $session,$path; ?>
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/jquery.js"></script>
<script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-modal.js"></script>
<script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-transition.js"></script>

<!-- TODO put in separated unit -->
<div class="modal hide fade" id="myModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Dashboard Configuration</h3>
  </div>
  <div class="modal-body">
    <form id="confform" action="">
      <label><?php echo _("Dashboard name: "); ?></label>
      <input type="text" name="name" value="<?php echo $dashboard['name']; ?>" />
      <label><?php echo _("Menu name: (lowercase a-z only)"); ?></label>
      <input type="text" name="alias" value="<?php echo $dashboard['alias']; ?>" />
      <label><?php echo _("Description: "); ?></label>           
      <textarea name="description"><?php echo $dashboard['description']; ?></textarea>
 	</form>
      <table>
        <tr>
          <td width="112"><?php echo _("Main: "); ?></td>
          <td><input type="checkbox" name="main" id="chk_main" value="1" <?php
            if ($dashboard['main'] == true)
              echo "checked";
            ?> /></td>
        </tr>
        <tr>
          <td><?php echo _("Published: "); ?></td>
          <td><input type="checkbox" name="published" id="chk_published" value="1" <?php
            if ($dashboard['published'] == true)
              echo "checked";
            ?> /></td>
          </tr>
        <tr>
          <td><?php echo _("Public: "); ?></td>
          <td><input type="checkbox" name="public" id="chk_public" value="1" <?php
            if ($dashboard['public'] == true)
              echo "checked";
            ?> /></td>
          </tr>
      </table>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <a href="#" id="configure-save" class="btn btn-primary">Save changes</a>
  </div>
</div>

<script type="application/javascript">
  var dashid = <?php echo $dashboard['id']; ?>;
  var path = "<?php echo $path; ?>";
  var apikey_read = "<?php echo $apikey_read; ?>";


  $("#configure-save").click(function (){
  	// serialize doesnt return unchecked checkboxes so manual url must be built
  	$main = '0';  
  	$public = '0';
  	$published = '0';
  	
  	if ($("#chk_main").is(":checked")) $main = '1';
	if ($("#chk_public").is(":checked")) $public = '1';
  	if ($("#chk_published").is(":checked")) $published = '1';
  	//
  	
    $.ajax({
      type : "POST",
      url :  path+"dashboard/setconf",
      //data : $('#confform').serialize()+"&id="+dashid,   // serialize doesnt return unchecked checkboxes
      data : $('#confform').serialize()+"&id="+dashid+"&main="+$main+"&public="+$public+"&published="+$published,      
      dataType : 'json',
      success : function() {}
      //success : location.reload()    //// if reload, the editor content not saved is lost!! what to do?
    });
    $('#myModal').modal('hide');
  });
</script>
