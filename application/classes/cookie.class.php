<?php
/**
 * Wrapper class to handle cookies
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-65-ge312679 2011-02-05 13:26:34 +0100 $
 */
class Cookie {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Set a cookie
   *
   * @param string $name     The name of the cookie.
   * @param string $value    The value of the cookie. This value is stored on
   *                         the clients computer; do not store sensitive
   *                         information. Assuming the name is 'cookiename',
   *                         this value is retrieved through
   *                         $_COOKIE['cookiename']
   * @param int    $expire   The time the cookie expires. This is a Unix
   *                         timestamp so is in number of seconds since the
   *                         epoch. In other words, you'll most likely set this
   *                         with the time() function plus the number of seconds
   *                         before you want it to expire.
   * @param string $path     The path on the server in which the cookie will be
   *                         available on. If set to '/', the cookie will be
   *                         available within the entire domain.
   * @param string $domain   The domain that the cookie is available to. To make
   *                         the cookie available on all subdomains of
   *                         example.com (including example.com itself) then
   *                         you'd set it to '.example.com'.
   * @param bool   $secure   Indicates that the cookie should only be
   *                         transmitted over a secure HTTPS connection from the
   *                         client. When set to TRUE, the cookie will only be
   *                         set if a secure connection exists.
   * @param bool   $httponly When TRUE the cookie will be made accessible only
   *                         through the HTTP protocol. This means that the
   *                         cookie won't be accessible by scripting languages,
   *                         such as JavaScript. This setting can effectively
   *                         help to reduce identity theft through XSS attacks
   *                         (although it is not supported by all browsers).
   *                         Added in PHP 5.2.0. TRUE or FALSE
   * @return void
   */
  public static function set( $name, $value, $expire=0, $path='/', $domain='', $secure=FALSE, $httponly=FALSE ) {
    if (empty($domain)) $domain = $_SERVER['HTTP_HOST'];
    setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
  } // function set()

  /**
   * Get cookie data
   *
   * @param string $name   The name of the cookie.
   * @param mixed $default The dafuelt value to return, if cookie is not set.
   * @return mixed
   */
  public static function get( $name, $default=NULL ) {
    return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
  } // function get()

}