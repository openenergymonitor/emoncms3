
  <h2><?php echo _("Notify "); ?><?php echo $feedid; ?></h2>
  <p>
    <?php echo _("Setup feed notifications"); ?>
  </p>
  <form action="../notify/set" method="get">
    <input type="hidden" name="feedid" value="<?php echo $feedid; ?>">
    <p>
      <?php echo _("Notify when feed = value: "); ?>
      <input type="text" name="onvalue" style="width:50px;" value="<?php echo $notify['onvalue']; ?>" />
    </p>
    <p>
      <?php echo _("Notify when feed is inactive "); ?>
      <input type="checkbox" name="oninactive" value=1 <?php if ($notify['oninactive']) echo "checked" ?>/>
    </p>
    <p>
      <?php echo _("Notify feed status periodically "); ?>
      <input type="checkbox" name="periodic" value=1 <?php if ($notify['periodic']) echo "checked" ?> />
    </p>
    <input type="submit" value="Save" class="button05"/>
  </form>
  <br/>
  <a href="../notify/settings"><?php echo _("Edit mail settings"); ?></a>

