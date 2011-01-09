<?php
/**
 * Rewrite urls
 *
 * @package    Plugin-ModuleConfiguration
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class esf_Plugin_Module_Configuration extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('BuildMenu', 'ModuleConfigsLoaded', 'PluginConfigsLoaded', 'ConfigurationSave');
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

    // add menu entry, if module is configurable
    if (!Request::check('configuration') AND
        esf_Extensions::isConfigurable(esf_Extensions::MODULE, Registry::get('esf.Module'))) {

      esf_Menu::addModule( array(
        'module' => 'configuration',
        'action' => 'edit',
        'params' => array(
                      'ext'      => 'module-'.Registry::get('esf.Module'),
                      'returnto' => encodeReturnTo( array (
                                                      'module' => Registry::get('esf.Module'),
                                                      'action' => Registry::get('esf.Action')
                                                    )
                                                  )
                    ),
        'id'     => 999
      ));
    }

    esf_Menu::addSystem(array( 'module' => 'configuration', 'id' => 500 ));
  }

  /**
   *
   */
  public function ModuleConfigsLoaded() {
    // load user configs
    Core::ReadConfigs('local/module/*');
  }

  /**
   *
   */
  public function PluginConfigsLoaded() {
    // load user configs
    Core::ReadConfigs('local/plugin/*');
  }

  /**
   *
   */
  public function ConfigurationSave( &$data ) {
    if (strtolower($data['var']) == 'module.configuration.admins') {
      $user = esf_User::getActual();
      if (!in_array($user, explode('|',$data['value']))) {
        $data['value'] = trim($user.'|'.$data['value'], '|');
        Messages::Error('Add yourself to allowed users!');
      }
    }
  }

}

Event::attach(new esf_Plugin_Module_Configuration);
