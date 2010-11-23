<?php
/**
 * Print Content of Session variables
 *
 * @usage
 * @code
 * Content:
 *   $_SESSION['USERNAME'] will be 'John Doe'
 *
 * Template:
 *   Current User: {session:USERNAME}
 *
 * Result:
 *   Current User: John Doe
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Session extends Yuelo_Extension {

  /**
   * Print Content of Session variables
   *
   * @param string $param Requested variable
   * @return string
   */
  public static function Process() {
    @list($param) = func_get_args();
    return (isset($_SESSION[$param])) ? $_SESSION[$param] : '';
  }

}