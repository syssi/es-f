<?php
/**
 * Print Content of $_REQUEST variables
 *
 * @usage
 * @code
 * Content:
 *   $_REQUEST['text'] will be 'text'
 *
 * Template:
 *   &lt;input type="text" name="text" value="{request:'text'}"&gt;
 *
 * Result:
 *   &lt;input type="text" name="text" value="text"&gt;
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Request extends Yuelo_Extension {

  /**
   * Print Content of $_REQUEST variables
   *
   * @param string $param
   * @return string
   */
  public static function Process() {
    @list($param) = func_get_args();
    return (isset($_REQUEST[$param])) ? $_REQUEST[$param] : '';
  }

}