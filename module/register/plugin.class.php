<?php
/**
 * @category   Plugin
 * @package    Plugin-ModuleRegister
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Rewrite urls
 *
 * @category   Plugin
 * @package    Plugin-ModuleRegister
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_Module_Register extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('OutputStart', 'OutputFilterContent');
  }

  /**
   * NOT YET ACTIVE
   */
  public function ProcessStart() {
    if (PluginEnabled('Validate') AND Request::check('register', 'index'))
      DefineValidator('user', 'required', array('error'=>'Account is a requiered field!'));
  }

  /**
   *
   */
  public function OutputStart() {
    if (!Request::check('register') AND esf_User::getActual(TRUE) == strtolower(esf_User::$Admin)) {
      $path = Registry::get(esf_Extensions::MODULE.'.Register.Core.LocalPath').'/reg/*';
      if ($cnt = count(glob($path))) {
        $title = Translation::get('Register.RegistrationsPending', $cnt);
        $link = Core::Link(Core::URL(array('module'=>'register', 'action'=>'admin')),
                           Translation::get('Register.RegistrationsEdit'));
        Messages::addSuccess($title.' '.$link, TRUE);
      }
    }
  }

  /**
   *
   */
  public function OutputFilterContent( &$output ) {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    if (Registry::get('esf.module') == 'login') $output .= $this->Render('inc.register');
  }

}

Event::attach(new esf_Plugin_Module_Register);