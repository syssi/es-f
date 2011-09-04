<?php
/**
 * Module Register plugin
 *
 * @ingroup    Plugin
 * @ingroup    Module-Register
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_Module_Register extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LanguageSet', 'Start');
  }

  /**
   *
   */
  public function Start() {
    if (!Request::check('register') AND
        esf_User::getActual(TRUE) == strtolower(esf_User::$Admin)) {
      $path = np(BASEDIR.'/local/module/register/reg/*');
      if ($cnt = count(glob($path))) {
        $title = Translation::get('Register.RegistrationsPending', $cnt);
        $link = Core::Link(Core::URL(array('module'=>'register', 'action'=>'admin')),
                           Translation::get('Register.RegistrationsEdit'));
        Messages::Error($title.' '.$link, TRUE, FALSE);
      }
    }

    if (Registry::get('esf.Module') == 'login') {
      TplData::add('CONTENT_AFTER', $this->Render('inc.register'));
    }
  }
}

Event::attach(new esf_Plugin_Module_Register);