<?php
/**
 * Print file content
 *
 * @usage
 * @code
 * External Content Source (counter.txt): 1234
 *
 * Template:
 *   You are visitor No: {loadfile:"counter.txt"}
 *
 * Output:
 *   You are visitor No: 1234
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_LoadFile extends Yuelo_Extension {

  /**
   * Print file content
   *
   * @param string $file File to load, absolute or relative from document root
   * @return string
   */
  public static function Process() {
    @list($file) = func_get_args();
    if (substr($file, 0, 1) != DIRECTORY_SEPARATOR)
      $file = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $file;
    if (file_exists($file)) return @file_get_contents($file);
  }

}