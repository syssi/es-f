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
 * @version    $Id$
 */
class esf_Plugin_ModuleRSS_Feed_DBoxTicker extends esf_Plugin {

  /**
   *
   */
  function OutputFilter( &$content ) {
    Header('Content-Type: text/html; charset=ISO8859-1');
    $content = Core::fromUTF8($content);
  }

}

Event::attach(new esf_Plugin_ModuleRSS_Feed_DBoxTicker);