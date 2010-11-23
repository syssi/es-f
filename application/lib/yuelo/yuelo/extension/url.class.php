<?php
/**
 * Build url from paramter pairs
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('ACTION', 'delete');
 *   $template->assign('ID', 1);
 *
 * Template:
 *   href="{url:"index.php",ACTION,ID}"
 *   href="{url:,ACTION,ID,"confirm",TRUE}"
 *
 * Output:
 *   href="index.php?delete=1"
 *   href="index.php?delete=1&amp;confirm=1"
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_URL extends Yuelo_Extension {

  /**
   * Build url from paramter pairs
   *
   * @param string $script File name, if empty use "index.php"
   * @param string $params Parameter=Value pairs
   * @return string
   */
  public static function Process() {
    $params = func_get_args();
    $script = array_shift($params);
    if (!$script) $script = 'index.php';

    $count = count($params);
    if (count($params)%2) $params[] = '';

    $p = array();
    while (count($params)) {
      $key = array_shift($params);
      $val = array_shift($params);
      $p[] = htmlentities($key).'='.rawurlencode($val);
    }
    return $script.'?'.implode('&amp;',$p);
  }

}