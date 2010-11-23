<?php
/**
 * Convert all HTML entities to their applicable characters
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('MESSAGE', 'Nicht m&ouml;glich!');
 *
 * Template:
 *   <a href="alert('{MESSAGE|entitydecode}');">Alert</a>
 *
 * Output:
 *   <a href="alert('Nicht möglich!');">Alert</a>
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_EntityDecode extends Yuelo_Filter {

  /**
   * Calculate the size of a given array
   *
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    $param = strtr($param, array_flip(get_html_translation_table(HTML_ENTITIES)));
    return preg_replace('~&#([0-9]+);~me', "chr('\\1')", $param);
  }

}