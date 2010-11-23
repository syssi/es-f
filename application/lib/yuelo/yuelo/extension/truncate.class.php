<?php
/**
 * Restricts a String to a specific number of characters and cut to last whitespace if requested
 *
 * Prints suffix only if string was not empty.
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('TEASER', 'Version 1.0.0rc1 has been released. This is the first release candidate');
 *
 * Template:
 *   News: {truncate:TEASER,"50"} ... [more]
 *   News: {truncate:TEASER,"50"," ..."}[more]
 *
 * Output:
 *   News: Version 1.0.0rc1 has been released. This ... [more]
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Truncate extends Yuelo_Extension {

  /**
   * Restricts a String to a specific number of characters and cut to last whitespace if requested
   *
   * Prints suffix only if string was not empty.
   *
   * @param string $param Text to truncate
   * @param int $length
   * @param bool $whitespace Cut to last whitespace before $length
   * @param string $suffix Add this suffix, if string was truncated
   * @return string
   */
  public static function Process() {
    @list($param, $length, $whitespace, $suffix) = func_get_args();
    if (is_null($whitespace)) $whitespace = TRUE;
    $trunc = trim(substr($param, 0, $length+1));
    if ($whitespace AND strlen($trunc) > $length) {
      $w = strrchr($trunc, ' ');
      if ($w != '') $trunc = substr($trunc, 0, -strlen($w));
    }
    if ($trunc AND $trunc != $param) $trunc .= $suffix;
    return $trunc;
  }

}