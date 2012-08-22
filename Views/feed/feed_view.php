<?php
/*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
*/

$id = $feed[0];
$name = $feed[1];
$tag = $feed[2];
$type = $feed[6];
$status = $feed[7];
?>

  <h2><?php echo $name; ?></h2>

    <div class="well">
      <h3><?php echo _('Select graph type:'); ?></h3>
      <?php if ($type==DataType::REALTIME || $type==DataType::UNDEFINED) { ?>
      <form action="../vis/realtime" method="get" style="margin-bottom:5px;">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
        <input type="submit" class="button06" style="width:150px;" value="<?php echo _('Real-time'); ?>"></input>
      </form>

      <form action="../vis/rawdata" method="get" style="margin-bottom:5px;">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
        <input type="submit" class="button06" style="width:150px;" value="<?php echo _('Raw data'); ?>"></input>
      </form>
      <?php } ?>

      <?php if ($type==DataType::DAILY || $type==UNDEFINED) { ?>
      <form action="../vis/bargraph" method="get" style="margin-bottom:5px;">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
        <input type="submit" class="button06" style="width:150px;" value="<?php echo _('Bar graph'); ?>"></input>
      </form>
      <?php } ?>

      <?php if ($type==DataType::HISTOGRAM || $type==DataType::UNDEFINED) { ?>
      <form action="../vis/histgraph" method="get" style="margin-bottom:5px;">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
        <input type="submit" class="button06" style="width:150px;" value="<?php echo _('Histogram'); ?>"></input>
      </form>
      <?php } ?>

      <?php if ($type!=DataType::HISTOGRAM) { ?>
      <form action="../vis/edit" method="get" style="margin-bottom:5px;">
        <input type="hidden" name="feedid" value="<?php echo $id; ?>">
        <input type="submit" class="button06" style="width:150px;" value="<?php echo _('Datapoint Editor'); ?>"</input>
      </form>
      <?php } ?>
	</div>

	<?php if ($status==0) { ?>
		<div class="well">		
      <h3><?php echo _('Feed type'); ?></h3>

      <form action="type" method="get">
        <select name="type" class="span3">
          <option value="<?php echo DataType::UNDEFINED.'" '; if ($type == DataType::UNDEFINED) echo 'selected'; ?> ><?php echo _('Undefined'); ?></option>
          <option value="<?php echo DataType::REALTIME.'" '; if ($type == DataType::REALTIME) echo 'selected'; ?> ><?php echo _('Real-time data'); ?></option>
          <option value="<?php echo DataType::DAILY.'" '; if ($type == DataType::DAILY) echo 'selected'; ?> ><?php echo _('Daily data'); ?></option>
          <option value="<?php echo DataType::HISTOGRAM.'" '; if ($type == DataType::HISTOGRAM) echo 'selected'; ?> ><?php echo _('Histogram data'); ?></option>
        </select>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" value="<?php echo _('Save'); ?>" class="button05"/>
      </form>

      <h3><?php echo _('Tag feed'); ?></h3>
      <form action="tag" method="get" style="margin:0px;">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="text" name="tag" class="span3" value="<?php echo $tag; ?>" />
        <input type="submit" value="<?php echo _('Save'); ?>" class="button05"/>
      </form>
      <h3><?php echo _('Rename feed'); ?></h3>
      <form action="rename" method="get" style="margin:0px;">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="text" name="name" class="span3" value="<?php echo $name; ?>" />
        <input type="submit" value="<?php echo _('Rename'); ?>" class="button05"/>
      </form>
  	</div>
  
  	<div class="well">
      <h3><?php echo _("Notify"); ?></h3>
      <form action="../notify/view" method="get">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" value="<?php echo _('Set notifications'); ?>" class="button05"/>
        <p>
          <span class="label label-warning"><?php echo _('Set notification behavior for this feed'); ?></span>
     
        </p>          
      </form>
		</div>

  	<div class="well">
			<h3><?php echo _('Delete feed?'); ?></h3>
      	<?php $message = "<h2>" . _('Are you sure you want to delete feed: ') . $name . "?</h2>"; ?>
      	<form action="../confirm" method="post">
        	<input type="hidden" name="message" value="<?php echo $message; ?>">
        	<input type="hidden" name="action" value="<?php echo _('feed/delete'); ?>">
        	<input type="hidden" name="id" value="<?php echo $id; ?>">
        	<input type="submit" value="<?php echo _('Delete'); ?>" class="btn btn-danger"/>
    	  </form>
  	</div>
      
	<?php } else { ?>
	
	<div class="well">
  	<h3>Restore feed:
    	<form action="restore" method="get">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" value="restore" class="button05"/>
      </form>
      </h3>
      
  <?php } ?>
	
