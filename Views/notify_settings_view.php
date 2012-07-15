
  <h2><?php echo _("Notify settings"); ?></h2>
  <form action="../notify/setrecipients" method="get">
    <p>
      <?php echo _("Notification recipiants (To): "); ?>
      <input type="text" name="recipients" style="width:150px;" value="<?php echo $recipients; ?>" />
    </p>
    <input type="submit" value="Save" class="button05"/>
  </form>
