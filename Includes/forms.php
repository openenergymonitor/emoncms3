<?php
/*
All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org
*/

// Create combo with available languages
// todo: set selected value from database and set language
function SelectLanguageForm() {
	echo '<form action="setlang" method="get">';
	echo '<select name="lang">';
	
	if ($handle = opendir('locale')) {
	    while (false !== ($entry = readdir($handle))) 
    		if (is_dir('locale/'.$entry) && ($entry !='.') && ($entry!='..'))
        		echo '<option value="'.$entry.'">'.$entry.'</option>';
			
			/*<!-- <option selected value="en"><?php echo _("English"); ?></option>
            <option value="es"><?php echo _("Spanish"); ?></option>*/
         
    closedir($handle);
	echo '</select>';
	} /* endif */
	
	echo '<input type="submit" value='._("Set").' class="button05">';
	echo '</form>';
}

?>
