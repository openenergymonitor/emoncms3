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
function SelectLanguageForm($selectedlang) {
	echo '<form class="well form-inline" action="setlang" method="get">';
	echo '<span class="help-block">'._("Select preferred language").'</span>';	
	echo '<select name="lang">';
	
	if ($handle = opendir('locale')) {
		if ($selectedlang=='')
			echo '<option selected value="">'._("AUTODETECTLANGUAGE").'</option>';
		else 
			echo '<option value="">'._("AUTODETECTLANGUAGE").'</option>';
		
	    while (false !== ($entry = readdir($handle))) 
    		if (is_dir('locale/'.$entry) && ($entry !='.') && ($entry!='..'))
			{
				if ($entry == $selectedlang)
					echo '<option selected value="'.$entry.'">'._($entry).'</option>';
				else
        			echo '<option value="'.$entry.'">'._($entry).'</option>';
			}
			         
    	closedir($handle);
		echo '</select>';
		
	} 
	
	echo '<input type="submit" value="Set" class="btn">';
	echo '</form>';
}

?>
