<?php
/**
 * Login plugin
 *
 * @ingroup    Plugin-Login
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @par License:
 * <a href="http://www.gnu.org/licenses/gpl.txt">GNU General Public License</a>
 * @version    $Id$
 */
class esf_Plugin_Module_Login extends esf_Plugin {

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
    if (!esf_User::isValid())
      esf_Menu::addMain( array( 'module' => 'login', 'id' => 10000 ) );
    Event::Dettach($this);
  }
}

Event::attach(new esf_Plugin_Module_Login);