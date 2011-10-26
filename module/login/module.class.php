<?php
/**
 * Login module
 *
 * @ingroup    Module-Login
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-54-g83ea36d 2011-01-17 20:17:17 +0100 $
 * @revision   $Rev$
 */
class esf_Module_Login extends esf_Module {

  /**
   * Class constructor
   *
   * @return void
   * /
  public function __construct() {
    parent::__construct();
  }

  /**
   *
   */
  public function IndexAction() {

    $user = $pass = $layout = '';
    $hashed = FALSE;

    if ($this->isPost() AND $this->Request('user')) {
      // try to find a legal user, from login form or as auto login user
      $user = esf_User::get($this->Request['user']);
      // check login via form submit
      if ($user) {
        $pass = $this->Request('pass');
        $layout = $this->Request('layout');
      } elseif (!$this->Request('user')) {
        TplData::set('LoginMsg', Translation::get('Login.Failed'));
      }
    }

    if ($token = $this->Request('token')) {
      // login via an URL with a token
      $token = Core::$Crypter->decrypt($token, APPID);
      $token = explode("\x00", $token);
      if (count($token) == 4 AND $token[0] == esf_User::getToken()) {
        $user   = $token[1];
        // this is the still HASHED password
        $pass   = $token[2];
        $hashed = TRUE;
        $layout = $token[3];
      }
    }

    // We found a user, but is it a valid one?
    if ($user AND $pass) {
      if (esf_User::isValid($user, $pass, $hashed)) {
        $h = date('G');
        switch (TRUE) {
          case ($h < $this->Morning):
            $msg = Translation::get('Login.GoodMorning');
            break;
          case ($h < $this->Day):
            $msg = Translation::get('Login.GoodDay');
            break;
          case ($h < $this->Afternoon):
            $msg = Translation::get('Login.GoodAfternoon');
            break;
          default:
            $msg = Translation::get('Login.GoodEvening');
            break;
        }
        Messages::Success($msg.' '.$user.'!');
        Session::set('Layout', $layout);
        Session::set('ClearCache', TRUE);
        $ts = time()+60*60*24*365;
        Cookie::set('LastUser', $user, $ts);
        Cookie::set('LastLayout', $layout, $ts);
        if ($this->Request('cookie'))
          Cookie::set('ttl', 60*60*24*$this->Cookie, $ts);
        else
          Cookie::delete('ttl');
        $this->Redirect(STARTMODULE);
      } else {
        TplData::set('LoginMsg', Translation::get('Login.Failed'));
      }
    }

    Session::set('Layout', 'default');
    TplData::set('Users', esf_User::getAll());
    TplData::set('User', Cookie::get('LastUser', $user));
  }

}