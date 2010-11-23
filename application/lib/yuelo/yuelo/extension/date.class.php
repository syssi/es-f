<?php
/**
 * Format a given or actual date time
 *
 * If blank or zero TIMESTAMP is provided, actual time will be used.
 * If no format given, use Y-m-d
 *
 * Uses:
 * - Yuelo::get($format) if defined
 * @see Yuelo
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('TIMESTAMP', time());
 *   Yuelo::set('DateFormat', 'd.m.Y');
 *   Yuelo::set('TimeStamp', 'YmdHis');
 *
 * Template:
 *   {date:TIMESTAMP}
 *   {date:TIMESTAMP,"d.m.Y H:i:s"}
 *   Save to "file.{date:TIMESTAMP,"TimeStamp"}.log"
 *   Save to "file.{date:,"TimeStamp"}.log" &lt;!-- uses actual time --&gt;
 *   Now: {date:}
 *
 * Result:
 *   16.10.2002
 *   16.10.2002 14:49:05
 *   Save to "file.20021016144905.log"
 *   Now: 2010-02-01 &lt;!-- actual date --&gt;
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Date extends Yuelo_Extension {

  /**
   * Format a given or actual date time
   *
   * @param int $timestamp Timestamp to format
   * @param string $format Date format in format for date() or an config. setting name
   * @return string
   */
  public static function Process() {
    @list($timestamp, $format) = func_get_args();
    if (!$timestamp) $timestamp = time();
    if ($cfgFormat = Yuelo::get($format)) $format = $cfgFormat;
    if (!$format) $format = 'Y-m-d';
    return date($format, $timestamp);
  }

}