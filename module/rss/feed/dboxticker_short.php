<?php
/**
 * Set some layout specific data
 *
 * @ingroup    Plugin
 * @ingroup    Module-RSS
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-74-ga09c8e3 2011-02-11 21:40:20 +0100 $
 */
class esf_Plugin_ModuleRSS_Feed_DBoxTickerShort extends esf_Plugin {

  /**
   *
   */
  function handles() {
    return array('OutputFilter');
  }

  /**
   *
   */
  function OutputFilter( &$content ) {
    Header('Content-Type: text/html; charset=ISO8859-1');
    $content = Core::fromUTF8($content);
  }

}

Event::attach(new esf_Plugin_ModuleRSS_Feed_DBoxTickerShort);