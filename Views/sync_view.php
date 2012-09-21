<?php global $path; ?>

<h2>Account Sync</h2>
<p>Sync multiple instances of emoncms together. Keep a local copy of data stored on a remote server.</p>

<form action="" method="get">
Remote Url: <input type="edit" name="url" value="<?php echo $url; ?>" />
Remote apikey: <input type="edit" name="remotekey" value="<?php echo $remotekey; ?>" />
<input type="submit" value="<?php echo _('Ok'); ?>" class="btn">
</form>

<table class='catlist'>
<tr><th>Feed name</th><th>Status</th><th>Sync</th></tr>
<?php foreach ($feeds as $items) {  $i++;  ?>
<tr class="<?php echo 'd' . ($i & 1); ?> "><td><?php echo $items[1]; ?></td><td><?php echo $items['inque']; ?></td><td><a href="<?php echo $path; ?>sync/feed?url=<?php echo urlencode($url); ?>&remotekey=<?php echo $remotekey; ?>&id=<?php echo $items[0]; ?>&name=<?php echo $items[1]; ?>">></a></td></tr>
<?php } ?>
</table>
