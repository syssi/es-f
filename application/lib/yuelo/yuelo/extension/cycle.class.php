<?php
/**
 * Cycle trough a list of (reasonable 2 or more) params
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('CYCLEID', 'trclass');
 *   $template->assign('CLASS1', 'tr1');
 *   $template->assign('CLASS2', 'tr2');
 *
 * Template:
 *   &lt;tr class="{cycle:CYCLEID,CLASS1,CLASS2}"&gt;
 *   &lt;tr class="{cycle:"trclass","tr1","tr2"}"&gt;
 *
 * Output:
 *   First loop : &lt;tr class="tr1"&gt;
 *   Second loop: &lt;tr class="tr2"&gt;
 *   Third loop : &lt;tr class="tr1"&gt;
 *   ... and so on
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Cycle extends Yuelo_Extension {

  /**
   * Cycle trough a list of (reasonable 2 or more) params
   *
   * @param string $id Cycle id
   * @param string $values Values to cycle through
   * @return string
   */
  public static function Process() {
    $params = func_get_args();
    // seperate the id from cycle values
    $id = array_shift($params);
    if (count($params)) {
      if (!isset(self::$ids[$id]) OR
          self::$ids[$id] >= count($params)) self::$ids[$id] = 0;
      return $params[self::$ids[$id]++];
    } else {
      // reset id, return null
      unset(self::$ids[$id]);
    }
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Array to remeber all cycle ids and their actual position
   */
  private static $ids = array();

}