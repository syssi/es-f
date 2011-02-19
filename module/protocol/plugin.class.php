<?php
/**
 * Module esniper protocols plugin
 *
 * @ingroup    Plugin
 * @ingroup    Module-Protocol
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class esf_Plugin_Module_Protocol extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('BuildMenu');
  }

  /**
   * NOT YET ACTIVE
   */
  public function ProcessStart() {
    if (PluginEnabled('Validate') AND Request::check('protocol')) {
      DefineValidator('group', 'required');
    }
  }

  /**
   *
   */
  public function BuildMenu() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    // require valid login
    if (!esf_User::isValid()) return;

    esf_Menu::addMain( array( 'module' => 'protocol', 'id' => 30 ) );
  }
}

Event::attach(new esf_Plugin_Module_Protocol);