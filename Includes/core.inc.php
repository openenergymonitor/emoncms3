<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */
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
    include "Views/".$filepath;   
    $content = ob_get_clean();    
    return $content;
  }

  function validate_json($json)
  {
    $json = str_replace(chr(92), "", $json);
    $json = str_replace('"', '', $json);		//Remove quote characters "
    $json = str_replace('{', '', $json);		//Remove JSON start characters
    $json = str_replace('}', '', $json);		//Remove JSON end characters
    $datapairs = explode(',', $json);
    return $datapairs;
  }

?>
