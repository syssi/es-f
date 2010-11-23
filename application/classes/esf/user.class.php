<?php
/**
 *
 */

/**
 *
 */
abstract class esf_User {

  /**
   * Session variables
   */
  const COOKIE = 'esf_cookie';

  /**
   * User details
   */
  const USER_NAME = 1;
  const USER_PASS = 2;

  /**
   * Admin user name
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

    if (empty(self::$Admin))
      self::$Admin = $user;
  }

  /**
   *
   */
  public static function get( $user, $detail=self::USER_NAME ) {
    $user = strtolower($user);
    if (!isset(self::$Users[$user]))
      return FALSE;

    return self::$Users[$user][$detail];
  }

  /**
   * Get session user name
   *
   * @param boolean $lowercase
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
   * @return string|boolean FALSE in case of not defined user
   * @global array
   */
  public static function getPass( $frontend=FALSE, $user=NULL, $password=NULL ) {
    if (!isset($user, $password)) {
      // use actual user
      $user = self::getActual();
      $password = Session::get(self::getToken());
    }

    $pass = self::get($user, self::USER_PASS);
    if (!$pass)
      return FALSE;

    $pass = explode("\x01", @MD5Encryptor::decrypt($pass, $password));
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
   * @param boolean $CookieLs Cookie life span
   * @return boolean
   */
  public static function isValid( $user=NULL, $password=NULL, $CookieLs=0 ) {
    // work inside this function only with hashed password!
    if (!is_null($password)) $password = md5($password);

    $token = self::getToken();
    $relogin = FALSE;

    if (!$user AND !$password) {
      // 1. check session first
      // check session: user & password and user token
      if ($user = MD5Encryptor::decrypt(Session::get(APPID)) AND
          $pass = Session::get($token) AND
          $pass == self::getPass(TRUE, $user, $pass)) {
        self::InitUser($user);
        return $user;
      }

      // 2. try to relogin via cookie
      if (!self::$LastUser AND isset($_COOKIE[self::COOKIE])) {
        $cookie   = @unserialize(@MD5Encryptor::decrypt($_COOKIE[self::COOKIE]));
        $user     = @$cookie[0];
        $password = @$cookie[1];
        $relogin = TRUE;
      }
    }

    // 3. login with user ans password
    if ($user AND $password) {
      // Login: check user/password and store in session
      self::$LastUser = NULL;
      if (!($pass = self::get($user, self::USER_PASS))) return FALSE;

      $pass = explode("\x01", MD5Encryptor::decrypt($pass, $password));
      $pass = @$pass[0];

      if ($pass == $password) {

        Core::StartSession(!$relogin, $relogin);

        Session::set(APPID, MD5Encryptor::encrypt($user));
        Session::set($token, $password);

        if ($CookieLs)
          setCookie(self::COOKIE,
                    MD5Encryptor::encrypt(serialize(array($user, $password))),
                    $_SERVER['REQUEST_TIME']+$CookieLs*24*60*60); // $CookieLs days

        if ($relogin) {
          Event::ProcessInform('Log', 'Login: '.$user.' from '.$_SERVER['REMOTE_ADDR'].' (Cookie)');
          // >> Debug
          DebugStack::Info('Login from cookie: '.$user);
          // << Debug
        } else {
          Event::ProcessInform('Log', 'Login: '.$user.' from '.$_SERVER['REMOTE_ADDR']);
        }
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
#    checkUserConfig($UserDir.'/config.xml');
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
   * @var array
   */
  private static $Users = array();

  /**
   * Last successfully logged in user
   *
   * @var string
   */
  private static $LastUser;

  /**
   * User specific token, build hash from
   * - user agent
   * - remote address
   * - configured net mask
   */
  private static $Token = FALSE;

  /**
   * Get user token, build from users remote address and users browser
   * 
   * @param boolean $plain Return plain / not encrypted token (USE ONLY DURING DEVELOPMENT)
   */
  public static function getToken() {
    // protect session against hijacking using hash of user agent and remote address
    if (!self::$Token) 
      self::$Token = md5($_SERVER['HTTP_USER_AGENT']
                        .(ip2long($_SERVER['REMOTE_ADDR']) & ip2long(Registry::get('NetMask'))));
    return self::$Token;
  }

  // >> Debug
  /**
   * Get user token, build from users remote address and users browser
   * 
   * USE ONLY DURING DEVELOPMENT
   */
  public static function getTokenPlain() {
    return array( $_SERVER['HTTP_USER_AGENT'], 
                  $_SERVER['REMOTE_ADDR'], 
                  Registry::get('NetMask'), 
                  md5($_SERVER['HTTP_USER_AGENT']
                     .(ip2long($_SERVER['REMOTE_ADDR']) & ip2long(Registry::get('NetMask'))))
                );
  }
  // << Debug

}
