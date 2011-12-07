<!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
-->
<div style="margin-left:20px;">

<!--
<pre style="color: #F78623;">  
  ___ _ __ ___   ___  _ __     ___ _ __ ___  ___  
 / _ \ '_ ` _ \ / _ \| '_ \   / __| '_ ` _ \/ __|
|  __/ | | | | | (_) | | | | | (__| | | | | \__ \ 
 \___|_| |_| |_|\___/|_| |_|  \___|_| |_| |_|___/  

Open source energy visualisation
</pre>-->



<div style="max-width:392px; margin-right:20px; padding-top:45px; padding-bottom:15px; color: #888;">
<div style="font-size:48px; font-weight:bold;">Emoncms <span style="color: #888;">v3</span></div>
<div style="font-size:16px;">open source energy visualisation</div>
</div>


<div class="widget-container" style="max-width:350px; margin-right:20px;" >

<div style="text-align:left">
<form action="<?php echo $GLOBALS['path']; ?>" method="post">

<p><b>Email:</b><br/>
<input class="inp01" type="text" name="username" style="width:94%"/></p>
<p><b>Password:</b><br/>
<input class="inp01" type="password" name="password" style="width:94%"/></p>

<input class="button04" type="submit" name="form" value="Login" /> or <input class="button04" type="submit" name="form" value="Register" />
  </table>
  <?php echo $error; ?>
</form>
</div>

</div>

</div>

