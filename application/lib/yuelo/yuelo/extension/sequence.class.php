<?php
/**
 * Create a sequence of integers
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('SEQUENCE', 'id1');
 *
 * Template:
 *   id="{sequence:SEQUENCE,"0","10"}"
 *   id="{sequence:"seq","0","10"}"
 *
 * Output:
 *   1st loop: id="0"
 *   2nd loop: id="10"
 *   3rd loop: id="20"
 *   ... and so on
 * @endcode
 *
 * Default:
 * @code
 * Template:
 *   id="{sequence:"seq"}"
 *
 * Output:
 *   1st loop: id="1"
 *   2nd loop: id="2"
 *   3rd loop: id="3"
 *   ... and so on
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Sequence extends Yuelo_Extension {

  /**
   * Create a sequence of integers
   *
   * @param string $id Sequence Id
   * @param int $start Sequence start, default 1
   * @param int $step Sequence stepping, default 1
   * @return int
   */
  public static function Process() {
    @list($id, $start, $step) = func_get_args();
    if (!$start) $start = 1;
    if (!$step) $step = 1;

    if (!isset(self::$ids[$id]))
      self::$ids[$id] = $start;
    else
      self::$ids[$id] += $step;

    return self::$ids[$id];
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Remeber all sequence ids and their actual value
   */
  private static $ids = array();

}