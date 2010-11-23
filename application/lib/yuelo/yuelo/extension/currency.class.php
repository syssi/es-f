<?php
/**
 * Format value with %.2f and locale settings for currency
 *
 * Uses:
 * - Yuelo::get('SuppressCurrency') if defined
 *
 * Usage Examples:
 * @code
 * Content:
 *   $Template->Assign('VARIABLE', 1);
 *   $Template->Assign('VARIABLE', 0);
 *
 * Template:
 *   {currency:VARIABLE,"--"}
 *   {currency:VARIABLE,"--"}
 *
 * Output:
 *   1.00
 *   --
 *
 * Content:
 *   $Template->Assign('VARIABLE', 1);
 *   Yuelo::set('SuppressCurrency', 'EUR');
 *
 * Template:
 *   {currency:VARIABLE,"--","EUR"}
 *   {currency:VARIABLE,"--","$"}
 *
 * Output:
 *   1.00
 *   $ 1.00
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Currency extends Yuelo_Extension {

  /**
   * Format value with %.2f and locale settings for currency
   *
   * @param numeric $value Amount
   * @param bool $zero Show zero amount as ...
   * @param string $curr Suppress currency
   * @return string
   */
  public static function Process() {
    @list($value, $zero, $curr) = func_get_args();
    if (($value != 0) OR ($zero === TRUE)) {
      $value = str_replace(',', '.', $value);
      $return = self::displayLocales($value);
      if ($curr AND Yuelo::get('SuppressCurrency') != trim($curr)) 
        $return = $curr.' '.$return;
    } else
      $return = $zero;

    return $return;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Diaplay a amount correct for a locale
   *
   * Idea from http://php.net/manual/function.localeconv.php,
   * UCN by PixEye dot DELETE at bigfoot dot com, 24-Nov-2007 10:15
   *
   * @param numeric $number
   * @param bool $asMoney Currency format
   * @param bool $int_curr Show international currency symbol (i.e. USD) instead of local currency symbol (i.e. $)
   */
  private static function displayLocales( $number, $asMoney=FALSE, $int_curr=FALSE ) {
    $LocaleConfig = localeConv();
    extract($LocaleConfig);

    // Sign specifications:
    if ($int_curr_symbol) {
      // correct locale found
      if ($number >= 0) {
        $sign = $positive_sign;
        $sign_posn = $p_sign_posn;
        $sep_by_space = $p_sep_by_space;
        $cs_precedes = $p_cs_precedes;
      } else {
        $sign = $negative_sign;
        $sign_posn = $n_sign_posn;
        $sep_by_space = $n_sep_by_space;
        $cs_precedes = $n_cs_precedes;
      }
    } else {
      $sign = ($number >= 0) ? '' : '-';
      $frac_digits = $int_frac_digits = 2;
      $decimal_point = $mon_decimal_point = '.';
      $thousands_sep = $mon_thousands_sep = ',';
      $sign_posn = 1;
      $sep_by_space = 1;
      $cs_precedes = 0;
    }

    if (!$asMoney) {
      // Number format:
      $n = number_format(abs($number), $frac_digits, $decimal_point, $thousands_sep);
      switch($sign_posn) {
        case 0:  $n = "($n)";    break;
        case 1:  $n = "$sign$n"; break;
        case 2:  $n = "$n$sign"; break;
        case 3:  $n = "$sign$n"; break;
        case 4:  $n = "$n$sign"; break;
        default: $n = "$n [error sign_posn=$sign_posn]";
      }
    } else {
      // Currency format:
      $n = number_format(abs($number), $frac_digits, $mon_decimal_point, $mon_thousands_sep);
      $space = $sep_by_space ? ' ' : '';
      $curr = $int_curr ? $int_curr_symbol : $currency_symbol;
      $n = $cs_precedes ? "$currency_symbol$space$n" : "$n$space$currency_symbol";
      switch($sign_posn) {
        case 0:  $n = "($n)";    break;
        case 1:  $n = "$sign$n"; break;
        case 2:  $n = "$n$sign"; break;
        case 3:  $n = "$sign$n"; break;
        case 4:  $n = "$n$sign"; break;
        default: $n = "$n [error sign_posn=$sign_posn]";
      }
    }
    $n = str_replace(' ', '&nbsp;', trim($n));
    return $n;
  }

}
