<?php
/**
@defgroup Extensions Extensions classes

Extensions acts like functions and can handle more than one parameter.

*/

// --------------------------------------------------------------------------

/**
 * Extension base class
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
abstract class Yuelo_Extension {

  /**
   * Abstract processing method
   *
   * @param mixed $params As many as you like
   * @return string
   */
  public static function Process() {}

}