<?php
/**
 * URL-encodes string for usage in links
 *
 * @usage
 * @code
 * Content:
 *    $template->assign('PARAM', 'Delete User!');
 *
 * Template:
 *   go.php?param={PARAM|urlencode}
 *
 * Output:
 *   go.php?param=Delete+User%21
 * @endcode
 *
 * @ingroup  Filter
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_URLEncode extends Yuelo_Filter {

  /**
   * URL-encodes string for usage in links
   *
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    return rawurlencode($param);
  }

}