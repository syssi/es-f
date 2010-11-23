<?php
/**
 * Ajax API
 *
 * @category   Plugin
 * @package    Plugin-API
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
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