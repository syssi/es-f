<?php
/** @defgroup Plugin-UrlRewriteHash Plugin UrlRewriteHash

*/

/**
 * Plugin UrlRewriteHash
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-UrlRewriteHash
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class esf_Plugin_UrlRewriteHash extends esf_Plugin {

  /**
   * URL Parameter
   */
  const PARAM = 'go';

  /**
   * Parameter delimiter
   */
  const DELIM = "\x01";

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('UrlRewrite', 'UrlUnRewrite');
  }

  /**
   * URL rewrite function
   *
   * @param array &$args URL parameters
   * @return string URL
   */
  public function UrlRewrite( &$args ) {
    # return, module, action, params, anchor
    $url = $module = $action = $anchor = NULL;
    $params = array();
    @list($url, $module, $action, $params, $anchor) = $args;

    if (empty($url)) {
      $url = $_SERVER['PHP_SELF'];
      $params['module'] = $module;
      $params['action'] = $action;
      $p = array();
      foreach ($params as $name => $val) {
        if (is_array($val)) continue;
        if (is_numeric($name)) {
          $name = $val;
          $val = FALSE;
        }
        $pp = $name;
        if ($val !== '') {
          $pp .= '='.urlencode($val);
        }
        $p[] = $pp;
      }
      $url .= '?' . self::PARAM.trim(base64_encode(implode(self::DELIM,$p)), '=');
      if ($anchor) {
        $url .= '#' . $anchor;
      }
    }
    $args[0] = $url;
  }

  /**
   * URL un-rewrite function
   *
   * @param array &$args Request parameters
   */
  public function UrlUnRewrite( &$args ) {
    $hlen = strlen(self::PARAM);

    foreach (array_keys($args) as $param) {
      if (substr($param, 0, $hlen) === self::PARAM) {
        $p = base64_decode(substr($param, $hlen));
        unset($args[$param]);
        $pp = explode(self::DELIM, $p);
        foreach ($pp as $p) {
          $p = explode('=', $p, 2);
          $args[$p[0]] = isset($p[1]) ? $p[1] : '';
        }
        break;
      }
    }
  }

}

Event::attach(new esf_Plugin_UrlRewriteHash);