<?php
/**
 * Encrypt / decrypt data using md5
 */

/**
 *
 */
abstract class MD5Encryptor  {

  /**
   * Additional password for encryption
   */
  public static $Password = '';

  /**
   *
   */
  public static $PasswordLength = 2;

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
  public static function encrypt( $plain, $password='' ) {
    $password = self::$Password.$password;
    $plain .= "\x13";
    $n = strlen($plain);
    if ($n % 16) $plain .= str_repeat("\0", 16 - ($n % 16));

    $encoded = '';
    $i = self::$PasswordLength;
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
  public static function decrypt( $encoded, $password='' ) {
    $password = self::$Password.$password;
    $encoded = base64_decode($encoded);
    $n = strlen($encoded);
    $i = self::$PasswordLength;
    $plain = '';
    $iv = substr($password ^ substr($encoded, 0, self::$PasswordLength), 0, 512);
    while ($i < $n) {
      $block = substr($encoded, $i, 16);
      $plain .= $block ^ pack('H*', md5($iv));
      $iv = substr($block . $iv, 0, 512) ^ $password;
      $i += 16;
    }
    return preg_replace('/\\x13\\x00*$/', '', $plain);
  }
}
