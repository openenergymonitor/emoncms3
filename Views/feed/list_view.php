<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->


<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">
  <h2>Feeds</h2>

  <?php if ($feeds) { ?>
  <table class='catlist'><tr><th>id</th><th>Name</th><th>Tag</th><th>Updated</th><th>Value</th></tr>
  <?php 
    $i = 0;
    foreach ($feeds as $feed)
    {
      $timenow = time();
      $time = strtotime($feed[3]);
      $sec = ($timenow - $time);
      $min = number_format($sec/60,0);
      $hour = number_format($sec/3600,0);

      $updated = $sec."s ago";
      if ($sec>180) $updated = $min." mins ago";
      if ($sec>(3600*2)) $updated = $hour." hours ago";
      if ($hour>24) $updated = "inactive";


      $color = "rgb(255,125,20)";
      if ($sec<60) $color = "rgb(240,180,20)";
      if ($sec<25) $color = "rgb(50,200,50)";

      $i++;
      ?>
      <?php 
        if ($feed[2] != $lastfeed) echo "<tr><td></td></tr>";
        $lastfeed = $feed[2];
      ?>
      <tr class="<?php echo 'd'.($i & 1); ?> " >
      <td><?php echo $feed[0]; ?></td>
      <td>
      <form action="view" method="get">
        <input type="hidden" name="id" value="<?php echo $feed[0]; ?>">
        <input type="submit" value="<?php echo $feed[1]; ?>" class="button05" style="width:150px"/>
      </form>

      </td>
      
      <td><?php echo $feed[2]; ?></td>
      <td style="color:<?php echo $color; ?>"><?php echo $updated; ?></td>
      <td>
      <?php 
        if ($feed[4]>10) $val = number_format($feed[4],1); 
        if ($feed[4]>100) $val = number_format($feed[4],0); 
        if ($feed[4]<10) $val = number_format($feed[4],2); 
        echo $val;
      ?>
      </td>
      </tr>


    <?php } ?>
    </table>
    <?php } else { ?>
      <p>You have no feeds</p>
    <?php } ?>
    </div>




