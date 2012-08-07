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

  function controller($cat)
  {
    $controller = $cat."_controller";
    $controllerScript = "Controllers/".$controller.".php";   
    if (is_file($controllerScript))
    {
      require $controllerScript;
      $content = $controller();
    }

    return $content;
  }


  function view($filepath, array $args)
  {
    extract($args);
    ob_start();       
    include "Views/$filepath";   
    $content = ob_get_clean();
    return $content;
  }

 /* function view_lang($filepath, array $args)
  {
    global $session;
    $lang = $session['lang'];

    extract($args);
    ob_start();       
    include "Views/$lang/$filepath";   
    $content = ob_get_clean();    
    return $content;
  }*/

  function validate_json($json)
  {
    $json = str_replace(chr(92), "", $json);
    $json = str_replace('"', '', $json);		//Remove quote characters "
    $json = str_replace('{', '', $json);		//Remove JSON start characters
    $json = str_replace('}', '', $json);		//Remove JSON end characters
    $datapairs = explode(',', $json);
    return $datapairs;
  }

  function emon_session_start() {
    session_set_cookie_params(
            3600 * 24 * 30, //lifetime, 30 days
            "/", //path
            "", //domain
            false, //secure
            true//http_only
    );
    session_start();
  }
  
  function get_theme_path()
  {
  	return $GLOBALS['path']."/Views/theme/".$GLOBALS['theme'];
  }

?>
