<?php
/*
 All Emoncms code is released under the GNU Affero General Public License.
 See COPYRIGHT.txt and LICENSE.txt.

 ---------------------------------------------------------------------
 Emoncms - open source energy visualisation
 Part of the OpenEnergyMonitor project:
 http://openenergymonitor.org
 */

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

?>

<h2><?php echo _('Nodes'); ?></h2>
 
<table class='catlist'>
    <tr>
        <th>
          <?php echo _('Node Identification'); ?>
        </th>    
    </tr>
    
  <?php    
    foreach ($nodes as $node) {
      echo '<tr><td>'.$node['nodeid'].'</td></tr>';      
    }
  ?>
</table>

  

