<?php
/**
 * Base 64 encode given parameters
 *
 * Use this e.g. to get a unique hash
 * Trailing "=" will be removed
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Encode64 extends Yuelo_Extension {

  /**
   * Base 64 encode given parameters
   *
   * @param string $params As many parameters as you like
   * @return string
   */
  public static function Process() {
    $params = func_get_args();
    return trim(base64_encode(implode($params)), '=');
  }

}