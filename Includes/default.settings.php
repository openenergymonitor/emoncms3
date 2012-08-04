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

/*
 * Database connection settings
 */
  $username = "";
  $password = "";
  $server   = "";
  $database = "";

/*
 * Error processing
 */
  $display_errors = true;
 
/*
 * Use ckeditor as dashboard editor too
 * CKEditor must be installed separately (see Includes/INSTALL CKEDITOR HERE.TXT)
 */
  $useckeditor = false;
 
/*
 * Public registration
 */
 	$allowusersregister = true;
?>
