<?php
/**
 * Encrypt / decrypt data using md5
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 * @revision   $Rev$
 */
class MD5Crypter  {

  /**
   * Class constructor
   *
   * @param string $password Additional application wide default password
   * @param string $passwordlength Password length
   */
  public function __construct( $password='', $passwordlength=2 ) {
    $this->_password = $password;
    $this->_passwordLength = $passwordlength;
  }

  /**
   * Encrypt using MD5 algorithm
   *
   * Source: http://php.net/manual/function.md5.php
   * UCN by Alexander Valyalkin, 30-Jun-2004 10:41
   *
   * @param string $plain Data to encrypt
   * @param string $password Additional password
   * @return string
   */
  public function encrypt( $plain, $password='' ) {
    $password = $this->_password.$password;
    $plain .= "\x13";
    $n = strlen($plain);
    if ($n % 16) $plain .= str_repeat("\0", 16 - ($n % 16));

    $encoded = '';
    $i = $this->_passwordLength;
    while ($i-- > 0) $encoded .= chr(mt_rand() & 0xff);

    $iv = substr($password ^ $encoded, 0, 512);

    $i = 0;
    while ($i < $n) {
      $block = substr($plain, $i, 16) ^ pack('H*', md5($iv));
      $encoded .= $block;
      $iv = substr($block . $iv, 0, 512) ^ $password;
      $i += 16;
    }
    return trim(base64_encode($encoded), '=');
  }

  /**
   * Decrypt using MD5 algorithm
   *
   * Source: http://php.net/manual/function.md5.php
   * UCN by Alexander Valyalkin, 30-Jun-2004 10:41
   *
   * @param string $encoded Data to decrypt
   * @param string $password Additional password
   * @return string
   */
  public function decrypt( $encoded, $password='' ) {
    $password = $this->_password.$password;
    $encoded = base64_decode($encoded);
    $n = strlen($encoded);
    $i = $this->_passwordLength;
    $plain = '';
    $iv = substr($password ^ substr($encoded, 0, $this->_passwordLength), 0, 512);
    while ($i < $n) {
      $block = substr($encoded, $i, 16);
      $plain .= $block ^ pack('H*', md5($iv));
      $iv = substr($block . $iv, 0, 512) ^ $password;
      $i += 16;
    }
    return preg_replace('/\\x13\\x00*$/', '', $plain);
  }

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   * Additional password for encryption
   */
  protected $_password;

  /**
   *
   */
  protected $_passwordLength;

}
