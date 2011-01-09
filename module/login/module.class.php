<?php
/**
 * Login module
 *
 * @ingroup    Module-Login
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class esf_Module_Login extends esf_Module {

  /**
   * Class constructor
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
    Registry::set('esf.contentonly', TRUE);
  }

  /**
   *
   */
  public function IndexAction() {

    $user = $pass = '';

    if ($this->isPost() AND $this->Request('user')) {
      // try to find a legal user, from login form or as auto login user
      $user = esf_User::get($this->Request['user']);
      // check login via form submit
      if ($user) {
        $pass = $this->Request('pass');
      } elseif (!$this->Request('user')) {
        TplData::set('LoginMsg', Translation::get('Login.Failed'));
      }
    }

    if ($autoUsers = $this->AutoLogin) {
      // check auto login
      foreach ($autoUsers as $Data) {
        $autoIP = str_replace(array('.','*'), array('\\.','\d+'), $Data['ip']);
        $user = esf_User::get($Data['user']);
        if ($user AND preg_match('~^'.$autoIP.'$~', $_SERVER['REMOTE_ADDR'])) {
          $pass = $Data['password'];
          break;
        }
      }
    }

    // We found a user, but is it a valid one?
    if ($user AND $pass) {
      $ls = isset($this->Request['cookie']) ? $this->Cookie : 0;
      if (esf_User::isValid($user, $pass, $ls)) {
        $h = date('G');
        if ($h < $this->Morning)
          $msg = Translation::get('Login.GoodMorning');
        elseif ($h < $this->Day)
          $msg = Translation::get('Login.GoodDay');
        elseif ($h < $this->Afternoon)
          $msg = Translation::get('Login.GoodAfternoon');
        else
          $msg = Translation::get('Login.GoodEvening');
        Messages::Success($msg.' '.$user.'!');
        Session::setP('ClearCache', TRUE);
        setcookie(APPID.'_esf_User', $user);
        setcookie(APPID.'_esf_Layout', $this->Request('layout'));
        Session::setP('Layout', $this->Request('layout'));
        $this->Redirect(STARTMODULE);
      } else {
        TplData::set('LoginMsg', Translation::get('Login.Failed'));
      }
    }

    TplData::set('Users', esf_User::getAll());
    $user = APPID.'_esf_User';
    $user = isset($_COOKIE[$user]) ? $_COOKIE[$user] : '';
    TplData::set('User', $user);
  }

}