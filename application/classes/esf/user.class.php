<?php
/**
 * User class
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
abstract class esf_User {

  /** @{
   * Shortcuts for user details
   */
  const USER_NAME = 1;
  const USER_PASS = 2;
  /** @} */

  /**
   * Admin user name
   *
   * @var string $Admin
   */
  public static $Admin;

  /**
   *
   */
  public static function set( $user, $passwords ) {
    self::$Users[strtolower($user)] = array( 
      self::USER_NAME => $user, 
      self::USER_PASS => $passwords
    );
    if (empty(self::$Admin)) self::$Admin = $user;
  }

  /**
   *
   */
  public static function get( $user, $detail=self::USER_NAME ) {
    $user = strtolower($user);
    return isset(self::$Users[$user]) ? self::$Users[$user][$detail] : FALSE;
  }

  /**
   * Get session user name
   *
   * @param boolean $lowercase
   * @param boolean $RegEx Format user name for reg. ex. usage, e.g. for grep
   * @return string
   */
  public static function getActual( $lowercase=FALSE, $RegEx=FALSE ) {
    if ($user = self::$LastUser) {
      if ($lowercase) $user = strtolower($user);
      if ($RegEx) {
        // mask special chars e.g. for usage with grep
        $user = preg_replace('~[*?.$]~', '[$0]', $user);
        $user = preg_replace('~[\^]~', '[\\\$0]', $user);
      }
    }
    return $user;
  }

  /**
   * Get session users esniper password
   * 
   * @param boolean $frontend Return frontend password
   * @param string $user If no user specified, actual user is used
   * @param string $password
   * @return string|boolean FALSE in case of not defined user
   */
  public static function getPass( $frontend=FALSE, $user=NULL, $password=NULL ) {
    if (!isset($user, $password)) {
      // use actual user
      $data = Session::get(self::getToken());
      if (count($data) == 2) list($user, $password) = $data;
    }

    $pass = self::get($user, self::USER_PASS);
    if (!$pass) return FALSE;

    $pass = explode("\x01", Core::$Crypter->decrypt($pass, $password));
    // convert a bool to 0|1
    return @$pass[(int)!$frontend];
  }

  /**
   * Get admin user
   *
   * @param boolean $lowercase
   * @return string|FALSE
   */
  public static function getAll( $lowercase=FALSE ) {
    $users = array_keys(self::$Users);
    if ($lowercase) $users = array_map('strtolower', $users);
    return $users;
  }

  /**
   * Check for valid session user
   * 
   * Dual use, with user/password log user in, without check for correct user
   * 
   * @param string $user
   * @param string $password
   * @param string $hashed Is the password plain or still hashed
   * @return boolean
   */
  public static function isValid( $user=NULL, $password=NULL, $hashed=FALSE ) {
    // work inside this function only with hashed password!
    if (!is_null($password) AND !$hashed) $password = md5($password);

    $token = self::getToken();

    if (is_null($user) OR is_null($password)) {
      // 1. check session first
      // check session: user & password and user token
      if (count($data = Session::get($token)) == 2) {
        list($user, $pass) = $data;
      } else {
        $pass = '';
      }
      if (#$user = Core::$Crypter->decrypt(Session::get(APPID)) AND
          #$pass = Session::get($token) AND
          $pass == self::getPass(TRUE, $user, $pass)) {
        self::InitUser($user);
        return $user;
      }
    } else {
      // 2. login with user and password
      // Login: check user/password and store in session
      self::$LastUser = NULL;
      if (!($pass = self::get($user, self::USER_PASS))) return FALSE;

      $pass = explode("\x01", Core::$Crypter->decrypt($pass, $password));
      if (isset($pass[0])) $pass = $pass[0];

      if ($pass == $password) {
        Core::StartSession(TRUE);
        Session::set($token, array($user, $password));
        Event::ProcessInform('Log', 'Login: '.$user.' from '.$_SERVER['REMOTE_ADDR']);
        self::InitUser($user);
        return $user;
      } else {
        Event::ProcessInform('Log', 'Login failed: '.$user.' / '.$pass.' from '.$_SERVER['REMOTE_ADDR']);
      }
    }
    return FALSE;
  }

  /**
   * Complete user settings after login
   *
   * @param string $user
   */
  public static function InitUser( $user ) {
    // Only once per script run...
    if (self::$LastUser == $user) return;

    self::$LastUser = $user;
    $UserDir = self::UserDir();
    is_dir($UserDir) || mkdir($UserDir);

    Event::ProcessReturn('getLastUpdate') || Event::ProcessInform('setLastUpdate');
  }

  /**
   *
   */
  public static function UserDir() {
    if (self::$LastUser)
      return BASEDIR.'/local/data/.'.strtolower(Secure4fs(self::$LastUser));
    throw new Exception(__CLASS__.': User is not set yet!');
  }

  // ---------------------------------------------------------------------------
  // PRIVATE
  // ---------------------------------------------------------------------------

  /**
   * Array ( User => encrypted passwords )
   *
   * @var array $Users
   */
  private static $Users = array();

  /**
   * Last successfully logged in user
   *
   * @var string $LastUser
   */
  private static $LastUser;

  /**
   * User specific token, build hash from
   * - user agent
   * - remote address
   * - configured net mask
   *
   * @var string $Token
   */
  private static $Token = FALSE;

  /**
   * Get user token, build from users remote address and users browser
   * 
   * @return string
   */
  public static function getToken() {
    // protect session against hijacking using hash of user agent and remote address
    if (!self::$Token) 
      self::$Token = md5($_SERVER['HTTP_USER_AGENT']
                        .(ip2long($_SERVER['REMOTE_ADDR']) & ip2long(Registry::get('NetMask'))));
    return self::$Token;
  }

  /**
   * Get user token, build from users remote address and users browser
   * 
   * USE ONLY DURING DEVELOPMENT
   *
   * @return string
   */
  /* ///
  public static function getTokenPlain() {
    return array( $_SERVER['HTTP_USER_AGENT'], 
                  $_SERVER['REMOTE_ADDR'], 
                  Registry::get('NetMask'), 
                  md5($_SERVER['HTTP_USER_AGENT']
                     .(ip2long($_SERVER['REMOTE_ADDR']) & ip2long(Registry::get('NetMask'))))
                );
  }
  /// */

}
