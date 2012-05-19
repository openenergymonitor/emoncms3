<div style="padding:20px;">
<h2>Notify <?php echo $feedid; ?></h2>
<p>Setup feed notifications</p>
<form action="../notify/set" method="get">
<input type="hidden" name="feedid" value="<?php echo $feedid; ?>">
<p>Notify when feed = value: <input type="text" name="onvalue" style="width:50px;" value="<?php echo $notify['onvalue']; ?>" /></p>
<p>Notify when feed is inactive <input type="checkbox" name="oninactive" value=1 <?php if ($notify['oninactive']) echo "checked" ?>/></p>
<p>Notify feed status periodically <input type="checkbox" name="periodic" value=1 <?php if ($notify['periodic']) echo "checked" ?> /></p>
<input type="submit" value="Save" class="button05"/>
</form>
<br/>
<a href="../notify/settings">Edit mail settings</a>
</div>
