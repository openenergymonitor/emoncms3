<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">
<h1>Admin panel</h1>
Total feed data disk space use: <?php echo number_format($total_memuse/1024,1); ?> KiB

<h2>Users</h2>
<h3><?php echo sizeof($userlist); ?> registered users</h3>

<table class='catlist'>
<tr><th>id</th><th>Username</th><th>Up hits</th><th>Dn hits</th><th>Memory use</th><th>Admin</th></tr>

<?php $i = 0; foreach ($userlist as $user) { $i++; ?>
  <tr class="<?php echo 'd'.($i & 1); ?> " >
    <td><?php echo $user['userid']; ?></td>
    <td><?php echo $user['name']; ?></td>
    <td><?php echo $user['uphits']; ?></td>
    <td><?php echo $user['dnhits']; ?></td>
    <td><?php echo number_format($user['memuse']/1024,1); ?> KiB</td>
    <td><?php echo $user['admin']; ?></td>
  </tr>
<?php } ?>
</table>

</div>
