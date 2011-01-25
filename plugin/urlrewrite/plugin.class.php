<?php
/** @defgroup Plugin-UrlRewrite Plugin UrlRewrite

*/

/**
 * Plugin UrlRewrite
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-UrlRewrite
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
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