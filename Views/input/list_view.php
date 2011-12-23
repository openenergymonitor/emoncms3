<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->

<div class='lightbox' style="margin-bottom:20px; margin-left:3%; margin-right:3%;">
  <h2>Inputs</h2>

  <?php if ($inputs) { ?>
    <table class='catlist'>
    <tr><th>Name</th><th>Updated</th><th>Value</th></tr>
    <?php $i=0; foreach ($inputs as $input) { $i++;


      $timenow = time();
      $time = strtotime($input[2]);
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
    ?>

    <tr class="<?php echo 'd'.($i & 1); ?> " >

      <td>
 <form action="../process/list.html" method="get">
   <input type="hidden" name="inputid" value="<?php echo $input[0]; ?>">
   <input type="submit" class="button05" style="width:150px" value="<?php echo $input[1]; ?>"></input>
 </form>
</td>
      <td style="color:<?php echo $color; ?>"><?php echo $updated; ?></td>     
      <td><?php echo $input[3]; ?></td>      

    </tr>
  <?php } echo "</table>"; } else { ?>
    <p>You have no inputs, to get started connect up your monitoring hardware</p>
  <?php } ?>
</div>


