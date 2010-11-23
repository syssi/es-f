<?php
/**
 * @category   Plugin
 * @package    Plugin-RSSFeed
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Set some layout specific data
 *
 * @category   Plugin
 * @package    Plugin-RSSFeed
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_ModuleRSS_Feed_DBoxTickerShort extends esf_Plugin {

  /**
   *
   */
  function OutputFilter( &$content ) {
    Header('Content-Type: text/html; charset=ISO8859-1');
    $content = Core::fromUTF8($content);
  }

}

Event::attach(new esf_Plugin_ModuleRSS_Feed_DBoxTickerShort);