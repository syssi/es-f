<?php
/**
 * Module Logout plugin
 *
 * @ingroup    Plugin
 * @ingroup    Module-Logout
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class esf_Plugin_Module_Logout extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('BuildMenu');
  }

  /**
   *
   */
  function BuildMenu() {
    if (esf_User::isValid())
      esf_Menu::addMain( array( 'module' => 'logout', 'id' => 100000 ) );
  }
}

Event::attach(new esf_Plugin_Module_Logout);