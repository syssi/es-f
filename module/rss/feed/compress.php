<?php
/**
 * Compress RSS before delivery
 *
 * @ingroup    Plugin
 * @ingroup    Module-RSS
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class esf_Plugin_ModuleRSS_Compress extends esf_Plugin {

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
    $content = preg_replace('~ +~', ' ', $content);
    $content = preg_replace('~> +<~', '><', $content);
  }

}

Event::attach(new esf_Plugin_ModuleRSS_Compress, 99);