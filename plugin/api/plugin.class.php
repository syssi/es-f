<?php
/** @defgroup Plugin-API Plugin API

*/

/**
 * Ajax API
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-API
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class esf_Plugin_API extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('AnalyseRequest', 'AuctionsLoaded');
  }

  /**
   *
   */
  public function AnalyseRequest( &$request ) {
    if (array_key_exists('api', $request)) {
      foreach (glob(dirname(__FILE__).'/api/*.php') as $test) {
        if (basename($test, '.php') == $request['api']) {
          $this->Request = $request;
          break;
        }
      }
    }
  }

  /**
   * Process requestet API function and die with the function result converted
   * to JSON
   *
   * @return string JSON formated
   */
  public function AuctionsLoaded() {
    if ($request = $this->Request AND $action = @$request['api']) {
      Loader::Load(dirname(__FILE__).'/api/'.$action.'.php');
      $func = 'API_'.$action;
      $result = array( 'rc'=>0, 'result'=>'', 'msg'=>'' );
      if (function_exists($func)) {
        unset($request['api']);
        $func($request, $result);
      } else {
        $result['rc']  = -1;
        $result['msg'] = 'Missing API function: '.$func;
      }
      die(JSON::encode($result));
    }
  }

}

Event::attach(new esf_Plugin_API);