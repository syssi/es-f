<?php
/**
 * Set some layout specific data
 *
 * @ingroup    Plugin
 * @ingroup    Module-Snipe
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class esf_Plugin_Layout_Module_Snipe extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('OutputStart');
  }

  /**
   *
   */
  function OutputStart() {
    // for SetGroupCategory(...)
    TplData::Add('HtmlHeader.js', 'module/auction/layout/script.js');
  }

}

Event::attach(new esf_Plugin_Layout_Module_Snipe);