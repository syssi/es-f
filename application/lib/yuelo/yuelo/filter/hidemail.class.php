<?php
/**
 * Protects email address from being scanned by spam bots
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('AUTHOR', 'email@example.com');
 *
 * Template:
 *   Author: {AUTHOR|hidemail}
 *
 * Output:
 *   Author: email [at] example [dot] com
 * @endcode
 *
 * @ingroup  Filters
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Filter_HideMail extends Yuelo_Filter {

  /**
   * Protects email address from being scanned by spam bots
   *
   * @param string $param
   * @return string
   */
  public static function Process( $param ) {
    return str_replace('@', ' [at] ', str_replace('.', ' [dot] ', $param));
  }

}