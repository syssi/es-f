<?php
/**
 * Session handling class
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
abstract class Session {

  /**
   *
   * @var bool $Debug
   */
  public static $Debug = FALSE;

  /**
   *
   * @var array $Messages
   */
  public static $Messages = array();

  /**
   *
   * @var mixed $NVL
   */
  public static $NVL = NULL;

  /**
   * Set session save path
   *
   * @param string $path
   * @return void
   */
  public static function setSavePath( $path ) {
    self::__dbg('Set save path to "%s"', $path);
    session_save_path($path);
  }

  /**
   * Set a signer for session data
   *
   * @param ISigner $signer
   * @return void
   */
  public static function setSigner( ISigner $signer ) {
    self::__dbg('Set signer to a instance of "%s"', get_class($signer));
    self::$__signer = $signer;
  }

  /**
   * Set functions to handle e.g. session file access
   *
   * @param string $open Function on open session
   * @param string $close Function on close session
   * @param string $read Function on read session data
   * @param string $write Function on write session data
   * @param string $destroy Function on destroying session
   * @param string $gc Function on garbage collection
   * @return void
   */
  public static function SetHandler( $open, $close, $read, $write, $destroy, $gc) {
    session_set_save_handler($open, $close, $read, $write, $destroy, $gc);
  }

  /**
   * Is a session active
   *
   * @return bool
   */
  public static function active() {
    return (session_id() != '');
  }

  /**
   * Start session
   *
   * @param string $name New session name
   * @param int $ttl Time to live for session cookie
   * @param string $path Used to restrict where the browser sends the cookie
   * @param string $domain Used to allow subdomains access to the cookie
   * @param bool $secure If TRUE the browser only sends the cookie over https
   * @return void
   */
  public static function start( $name, $ttl=0, $path='/', $domain=NULL, $secure=NULL ) {
    self::__dbg('Set name to "%s"', $name);
    $name = session_name($name);
    self::__dbg('Old name was "%s"', $name);

    // Set SSL level
    $https = isset($secure) ? $secure : isset($_SERVER['HTTPS']);

    session_set_cookie_params($ttl, $path, $domain, $https, TRUE);

    self::__start();

    // Make sure the session hasn't expired and is valid ...
    if (self::validate()) {
      // Give a 5% chance of the session id changing on any request
      if (rand(1, 100) <= 5) {
        self::regenerate();
      }
    } else {
      // ... and destroy it if it has
      $_SESSION = array();
      self::destroy();
      self::__start();
    }

    self::__dbg('Started "%s" = "%s"', session_name(), session_id());

    if (count(self::$__buffer)) {
      foreach(self::$__buffer as $key=>$value) {
        $key = strtolower($key);
        if (isset($_SESSION[$key]) AND is_array($_SESSION[$key])) {
          $_SESSION[$key] = array_merge($_SESSION[$key], $value);
        } else {
          $_SESSION[$key] = $value;
        }
      }
      self::$__buffer = array();
    }
  }

  /**
   * Update the current session id with a newly generated one
   *
   * @param bool $clear Clear all session data
   * @return bool Success
   */
  public static function regenerate( $clear=FALSE ) {
    // If this session is obsolete it means there already is a new id
    if (isset($_SESSION['_OBSOLETE']) && $_SESSION['_OBSOLETE']) return;

    self::__dbg('Regenerate ID: was "%s"', session_id());

    // Set current session to expire in 10 seconds
    $_SESSION['_OBSOLETE'] = TRUE;

    if ($ok = session_regenerate_id(TRUE)) {
      // hang on to the new session id
      $sid = session_id();
      // close the old and new sessions
      self::close();
      // re-open the new session
      session_id($sid);
      self::__start();
      self::__dbg('Regenerate ID: now "%s"', session_id());
    } else {
      self::__dbg('Regenerate ID: FAILED');
    }
    // Now we unset the obsolete and expiration values for the session we want to keep
    unset($_SESSION['_OBSOLETE']);

    if ($clear) $_SESSION = array();

    return $ok;
  }

  /**
   * Remove all session cookies
   *
   * idea from http://php.net/manual/function.session-get-cookie-params.php
   * UCN from powerlord at spamless dot vgmusic dot com, 19-Nov-2002 08:35
   *
   * @return void
   */
  public static function RemoveCookies() {
    self::__dbg('Remove cookies');

    $CookieInfo = session_get_cookie_params();

    if (empty($CookieInfo['domain']) AND empty($CookieInfo['secure'])) {
      setCookie(session_name(), session_id(), 1, $CookieInfo['path']);
    } elseif (empty($CookieInfo['secure'])) {
      setCookie(session_name(), session_id(), 1, $CookieInfo['path'], $CookieInfo['domain']);
    } else {
      setCookie(session_name(), session_id(), 1, $CookieInfo['path'], $CookieInfo['domain'], $CookieInfo['secure']);
    }
  }

  /**
   * Close the session
   *
   * Write the session data
   *
   * @see removeCookies()
   * @return void
   */
  public static function close() {
    @session_write_close();
  }

  /**
   * Destroy the session
   *
   * @see removeCookies()
   * @see close()
   * @param bool $removeCookies Remove also all session cookies
   * @return void
   */
  public static function destroy( $removeCookies=TRUE ) {
    self::__dbg('Destroy "%s" = "%s"', session_name(), session_id());
    if ($removeCookies) self::removeCookies();
    $_SESSION = array();
    self::close();
    @session_destroy();
  }

  /**
   * checkRequest, set session var to requested value or to a default
   *
   * Check if $param is member of $_REQUEST, if not, set to $default and
   * save this param to $_SESSION
   *
   * @param string $param Request parameter
   * @param mixed $default Default value
   * @return void
   */
  public static function checkRequest( $param, $default=FALSE ) {
    if (isset($_REQUEST[$param])) self::set($param, $_REQUEST[$param]);
    if (!self::is_set($param))    self::set($param, $default);
  }

  /**
   * Set a variable value into $_SESSION
   *
   * Deletes variable from session if value is NULL
   *
   * @see add()
   * @see get()
   * @param string $key Varibale name
   * @param mixed $val Varibale value
   * @return void
   */
  public static function set( $key, $val=NULL ) {
    $key = self::__mapKey($key);
    $_val = isset(self::$__signer) ? self::$__signer->sign($val) : $val;
    if (!self::active()) {
      self::$__buffer[$key] = $_val;
    } else {
      if (is_null($val)) {
        unset($_SESSION[$key]);
      } else {
        $_SESSION[$key] = $_val;
      }
    }
  }

  /**
   * Set a bunch of variables at once into $_SESSION
   *
   * Deletes variable from session if value is NULL
   *
   * @see set()
   * @param array $array Array of Variable => Value
   * @return void
   */
  public static function setA( $array ) {
    foreach ((array)$array as $key => $value) self::set($key, $value);
  }

  /**
   * Add a value to $_SESSION
   *
   * @param string $key Varibale name
   * @param mixed $val Varibale value
   * @return void
   */
  public static function add( $key, $val ) {
    $key = self::__mapKey($key);
    if (isset(self::$__signer)) $val = self::$__signer->sign($val);
    if (!self::active()) {
      self::$__buffer[$key][] = $val;
    } else {
      if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = array();
      } elseif (!is_array($_SESSION[$key])) {
        $_SESSION[$key] = array($_SESSION[$key]);
      }
      $_SESSION[$key][] = $val;
    }
  }

  /**
   * Get a value from a $_SESSION variable, return $default if not set
   *
   * @see set()
   * @param string $key Variable name
   * @param mixed $default Return if $var not set
   * @param bool $clear Remove data
   * @return mixed
   */
  public static function get( $key, $default=NULL, $clear=FALSE ) {
    $key = self::__mapKey($key);
    if (isset($_SESSION[$key])) {
      $val = $_SESSION[$key];
      if (isset(self::$__signer)) $val = self::$__signer->get($val);
    } elseif (isset($default)) {
      $val = $default;
    } else {
      $val = self::$NVL;
    }
    if ($clear) unset($_SESSION[$key]);
    return $val;
  }

  /**
   * Get a value from $_SESSION and deletes it
   *
   * @param string $key Varibale name
   * @return mixed
   */
  public static function takeout( $key ) {
    $val = self::get($key);
    self::delete($key);
    return $val;
  }

  /**
   * Remove a value from $_SESSION
   *
   * @param string $var Varibale name
   * @return void
   */
  public static function delete( $var ) {
    self::set($var);
  }

  /**
   * Chck if a $_SESSION variable is set
   *
   * @param string $key Varibale name
   * @return bool
   */
  public static function is_set( $key ) {
    return isset($_SESSION[self::__mapKey($key)]);
  }

  //---------------------------------------------------------------------------
  // PROTECTED
  //---------------------------------------------------------------------------

  /**
   * This function is used to see if a session has expired or not.
   *
   * @return bool
   */
  protected static function validate() {
    if (isset($_SESSION['_OBSOLETE'])) {
      return FALSE;
    }

    if (!isset($_SESSION['_HTTP_USER_AGENT']) OR
              $_SESSION['_HTTP_USER_AGENT'] !== md5($_SERVER['HTTP_USER_AGENT'].__FILE__)) {
      return FALSE;
    }
    return TRUE;
  }

  //---------------------------------------------------------------------------
  // PRIVATE
  //---------------------------------------------------------------------------

  /**
   * Data container
   *
   * @var array $__buffer
   */
  private static $__buffer = array();

  /**
   * Data signer
   *
   * @var array $__signer
   */
  private static $__signer = NULL;

  /**
   * Transform $key for common use
   *
   * @param string $key
   */
  private static function __mapKey( $key ) {
    return strtolower($key);
  }

  /**
   * starts session and some statements to fix bugs in IE and PHP < 4.3.3
   */
  private static function __start() {
    session_start();
    // to overcome/fix a bug in IE 6.x
    Header('Cache-control: private');
    // from http://php.net/manual/function.session-regenerate-id.php
    // UCN from Gant at BleachEatingFreaks dot com, 24-Jan-2006 09:57
    if (version_compare(PHP_VERSION, '4.3.3', '<')) {
      setCookie( session_name(), session_id(), ini_get('session.cookie_lifetime'));
    }
    // random suffix: file location
    $_SESSION['_HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT'].__FILE__);
  }

  /**
   * Collect debug infos
   */
  private static function __dbg() {
    if (!self::$Debug) return;

    $params = func_get_args();
    $msg = array_shift($params);
    self::$Messages[] = vsprintf($msg, $params);
  }
}