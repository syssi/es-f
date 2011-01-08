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
    return array('Start', 'PageStart', 'BuildMenu');
  }

  /**
   *
   */
  function Start() {
    // Check only on 1st display of login-index
    if (Session::get('language')) return;

    $ESFlanguages = Registry::get('esf.Languages');

    // Check full language Ids
    if ($HTTPlanguages = HTTPlanguage::get()) {
      foreach ($HTTPlanguages as $lang=>$name) {
        if (array_key_exists($lang, $ESFlanguages)) {
          Session::set('language', $lang);
          return;
        }
      }
    }
    // Check primary language Ids if not found yet
    if ($HTTPlanguages = HTTPlanguage::get(FALSE)) {
      foreach ($HTTPlanguages as $lang=>$name) {
        if (array_key_exists($lang, $ESFlanguages)) {
          Session::set('language', $lang);
          return;
        }
      }
    }
    // Fallback to english
    Session::set('language', 'en');
  }

  /**
   *
   */
  function PageStart() {
    if (!Request::check('login')) return;

    Session::setP('Layout', 'default');
    TplData::set('LastLayout', (isset($_COOKIE[APPID.'_esf_Layout']) ? $_COOKIE[APPID.'_esf_Layout'] : 'default'));
  }

  /**
   *
   */
  function BuildMenu() {
    if (!esf_User::isValid())
      esf_Menu::addMain( array( 'module' => 'login', 'id' => 10000 ) );
  }
}

Event::attach(new esf_Plugin_Module_Login);