<?php
/**
 * Format a number with grouped thousands
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('SUM', 2500000);
 *
 * Template:
 *   Current balance: {number:SUM}
 *
 * Result:
 *   Current balance: 2.500.000,00
 * @endcode
 *
 * For germny e.g. don't forget to set:
 * @code
 *   Yuelo::set('DecimalChar', ',');
 *   Yuelo::set('ThousandsSeparator', '.');
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Number extends Yuelo_Extension {

  /**
   * Format a number with grouped thousands
   *
   * @param float $number Value to format
   * @return string
   */
  public static function Process() {
    @list($number) = func_get_args();
    if (!$sep = Yuelo::get('ThousandsSeparator')) $sep = ',';
    if (!$chr = Yuelo::get('DecimalChar')) $chr = '.';
    if (!$pls = Yuelo::get('DecimalPlaces')) $pls = 2;
    return number_format($number, $pls, $chr, $sep);
  }

}