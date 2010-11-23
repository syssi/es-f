<?php
/**
 * Convert database date time (British Format) or timestamp to local formatted date & time
 *
 * Uses:
 * - Yuelo::get($format) if defined
 * @see Yuelo
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('UPDATE', $result['LAST_UPDATE_DATE_TIME']);
 *   Yuelo::set('TimeStamp', 'YmdHis');
 *
 * Template:
 *   Last update: {dbdate:UPDATE}
 *   Last update: {dbdate:UPDATE,"TimeStamp"}
 *
 * Output:
 *   Last update: 2003-01-30 12:03:23
 *   Last update: 20030130120323
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_DBDate extends Yuelo_Extension {

  /**
   * Convert database date time (British Format) or timestamp to local formatted date & time
   *
   * @param int $timestamp Timestamp to format
   * @param string $format Date format in format for date() or an config. setting name
   * @return string
   */
  public static function Process() {
    @list($timestamp, $format) = func_get_args();
    if (!$format OR !$format = Yuelo::get($format)) $format = 'Y-m-d H:i:s';
    if (preg_match('~\d{2,4}-\d{1,2}-\d{1,2}( \d{1,2}:\d{1,2}:\d{1,2})?~', $timestamp)) {
      return ($timestamp == '0000-00-00') ? NULL : date($format, strtotime($timestamp));
    } elseif (preg_match('/\d{14}/', $timestamp)) {
      return date($format, $timestamp);
    }
    return $timestamp;
  }

}