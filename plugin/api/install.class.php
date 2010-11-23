<?php
/**
 *
 */

/**
 * Class for Extension installation
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