<?php
/**
 * Processes plugin
 *
 * @package    Plugin-Process
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class esf_Plugin_Module_Process extends esf_Plugin {

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
    if (PluginEnabled('Validate') AND Request::check('process')) {
      DefineValidator('group', 'Required');
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

    esf_Menu::addMain( array( 'module' => 'process', 'id' => 70 ) );
  }
}

Event::attach(new esf_Plugin_Module_Process);