<?php
/**
 * Backend plugin
 *
 * @ingroup    Module-Backend
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-42-g440d05f - Sun Jan 9 21:40:58 2011 +0100 $
 */
class esf_Plugin_Module_Backend extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('BuildMenu', 'ConfigurationSave');
  }

  /**
   *
   */
  function BuildMenu() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;
    // require valid login
    if (!esf_User::isValid()) return;
    // Set first defined frontend user as admin, if no other defined
    if (!$this->Admins) $this->Admins = esf_User::$Admin;
    // Is logged in user an admin?
    if (!in_array(esf_User::getActual(TRUE),
                  explode('|', strtolower($this->Admins)))) return;

    // add menu entry
    esf_Menu::addSystem(array( 'module' => 'backend', 'id' => 999 ));
  }

  /**
   *
   */
  function ConfigurationSave( &$data ) {
    if (strtolower($data['var']) == 'module.backend.admins') {
      $user = esf_User::getActual();
      if (!in_array($user, explode('|',$data['value']))) {
        $data['value'] = trim($user.'|'.$data['value'], '|');
        Messages::Error('Add yourself to allowed users!');
      }
    }
  }

}

Event::attach(new esf_Plugin_Module_Backend);
