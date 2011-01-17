<?php
/**
 * Login module
 *
 * @ingroup    Module-Login
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-51-gfeddc24 - Sun Jan 16 21:09:59 2011 +0100 $
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
   * @return array Array of actions handled by the module
   */
  public function handles() {
    return array('index');
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
      if (esf_User::isValid($user, $pass)) {
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
        Session::setP('Layout', $this->Request('layout'));
        Session::setP('ClearCache', TRUE);
        $ts = time()+60*60*24*365;
        Cookie::set('LastUser', $user, $ts);
        Cookie::set('LastLayout', $this->Request('layout'), $ts);
        if ($this->Request('cookie'))
          Cookie::set('ttl', 60*60*24*$this->Cookie, $ts);
        else
          Cookie::set('ttl');
        $this->Redirect(STARTMODULE);
      } else {
        TplData::set('LoginMsg', Translation::get('Login.Failed'));
      }
    }

    Session::setP('Layout', 'default');
    TplData::set('Users', esf_User::getAll());
  }

}