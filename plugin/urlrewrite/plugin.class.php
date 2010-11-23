<?php
/**
 * @category   Plugin
 * @package    Plugin-UrlRewrite
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Rewrite urls
 *
 * @category   Plugin
 * @package    Plugin-UrlRewrite
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_UrlRewrite extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('UrlRewrite');
  }

  /**
   *
   */
  function UrlRewrite( &$args ) {
    if (!empty($args['url'])) return;

    $args['url'] = dirname($_SERVER['PHP_SELF']);
    if (substr($args['url'], -1, 1) == '/')
      $args['url'] = substr($args['url'], 0, -1);

    $args['url'] .= '/'.$args['module'];
    if (!empty($args['action']) AND $args['action'] != 'content')
      $args['url'] .= '-' . $args['action'];
    $args['url'] .= '.html';

    if (count($args['params']))
      $args['url'] .= '?' . http_build_query($args['params']);

    if ($args['anchor']) $args['url'] .= '#' . $args['anchor'];

    return $args['url'];
  }
}

Event::attach(new esf_Plugin_UrlRewrite);