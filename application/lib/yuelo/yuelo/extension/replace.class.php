<?php
/**
 * String Replace
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('PATH', $path_tranlated);  // C:\Apache\htdocs\php\test.php
 *
 * Template:
 *   Script Name: {replace:PATH,"\\","/"}
 *
 * Result:
 *   Script Name: C:/Apache/htdocs/php/test.php
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Replace extends Yuelo_Extension {

  /**
   * String Replace
   *
   * @param string $param
   * @param string $pattern Search for this in $param
   * @param string $replace Replace $pattern with this
   * @return string
   */
  public static function Process() {
    @list($param, $pattern, $replace) = func_get_args();
  	return str_replace($pattern, $replace, $param);
  }

}