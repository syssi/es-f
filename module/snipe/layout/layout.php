<?php
/**
 * @category   Plugin
 * @package    Plugin-SnipeLayout
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Set some layout specific data
 *
 * @category   Plugin
 * @package    Plugin-SnipeLayout
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
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