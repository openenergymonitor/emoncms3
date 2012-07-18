<h1><?php echo _("Admin panel"); ?></h1>
<?php echo _("Total feed data disk space use: "); ?><?php echo number_format($total_memuse / 1024, 1); ?> KiB

<h2><?php echo _("Users"); ?></h2>
<h3><?php echo sizeof($userlist); ?> <?php echo _("registered users"); ?></h3>

<table class='catlist'>
<tr><th>id</th><th><?php echo _("Username"); ?></th><th><?php echo _("Up hits"); ?></th><th><?php echo _("Dn hits"); ?></th><th><?php echo _("Memory use"); ?></th><th><?php echo _("Admin"); ?></th></tr>

<?php $i = 0; foreach ($userlist as $user) { $i++; ?>
  <tr class="<?php echo 'd' . ($i & 1); ?> " >
    <td><?php echo $user['userid']; ?></td>
    <td><?php echo $user['name']; ?></td>
    <td><?php echo $user['uphits']; ?></td>
    <td><?php echo $user['dnhits']; ?></td>
    <td><?php echo number_format($user['memuse'] / 1024, 1); ?> KiB</td>
    <td><?php echo $user['admin']; ?></td>
  </tr>
<?php } ?>
</table>
