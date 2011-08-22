<?php
/**
 * Module Register plugin
 *
 * @ingroup    Plugin
 * @ingroup    Module-Register
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_Module_Register extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('OutputStart');
  }

  /**
   *
   */
  public function OutputStart() {
    if (!Request::check('register') AND
        esf_User::getActual(TRUE) == strtolower(esf_User::$Admin)) {

      $path = Registry::get(esf_Extensions::MODULE.'.Register.Core.LocalPath').'/reg/*';

      if ($cnt = count(glob($path))) {
        $title = Translation::get('Register.RegistrationsPending', $cnt);
        $link = Core::Link(Core::URL(array('module'=>'register', 'action'=>'admin')),
                           Translation::get('Register.RegistrationsEdit'));
        Messages::Success($title.' '.$link, TRUE);
      }
    }

    if (Registry::get('esf.Module') == 'login') {
      TplData::add('CONTENT_AFTER', $this->Render('inc.register'));
    }
  }
}

Event::attach(new esf_Plugin_Module_Register);