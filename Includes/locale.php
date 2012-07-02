<?php
/*
All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org
*/

function lang_http_accept()
{
	$langs = array();
	
	foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $lang) {
		$pattern = '/^(?P<primarytag>[a-zA-Z]{2,8})'.
    	'(?:-(?P<subtag>[a-zA-Z]{2,8}))?(?:(?:;q=)'.
    	'(?P<quantifier>\d\.\d))?$/';

    	$splits = array();

		if (preg_match($pattern, $lang, $splits)) {
			// print_r($splits);
			$a = $splits["primarytag"];
			if ($splits["subtag"]<> "") $a = $a."_".$splits["subtag"];
				$langs[]=$a;
    		} else {
        		echo "\nno match\n";
    	}
	}
	return $langs;
}

function set_lang($language)
{
	$lang = $language[0];
	putenv("LC_ALL=$lang");
	setlocale(LC_ALL, $lang);
	bindtextdomain("app", "./locale");
	textdomain("app");	
}

?>
