<?php
/**
 * Module Logout plugin
 *
 * @ingroup    Plugin
 * @ingroup    Module-Logout
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_Module_Logout extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LanguageSet', 'BuildMenu');
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