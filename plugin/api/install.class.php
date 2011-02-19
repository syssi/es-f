<?php
/**
 * Plugin API installer
 *
 * @ingroup    Install
 * @ingroup    Plugin-API
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class esf_Install_Plugin_API extends esf_Install {

  /**
   *
   */
  public function Info() {
    $a = array( 'rc'=>0, 'msg'=>'...', 'result'=>'...' );
    $print = print_r($a, TRUE);
    $json = JSON::encode($a);

    $return = "
      <p>The return of an API call is an array:</p>
      <pre>$print</pre>
      <p>'rc' will be <tt>0</tt> on success.</p>
      <p>To process the result with JavaScript, it will be JSON formated:</p>
      <tt>$json</tt>
      <p>The following API functions are available:</p>
    ";

    foreach (glob(dirname(__FILE__).'/api/*.php') as $api) {
      require_once $api;
      $name = basename($api,'.php');
      $func = 'API_'.$name.'_Info';
      if (function_exists($func) AND $info = $func()) {
        $return .= sprintf('<p class="li"><strong>%s</strong><br>%s</p>', strtoupper($name), $info);
      }
    }
    return $return;
  }

}