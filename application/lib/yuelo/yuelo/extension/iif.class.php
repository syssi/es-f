<?php
/**
 * Extension iif
 *
 * Print Parameter depending of condition
 *
 * If you have a "no boolean condition" use Yuelo_Extension_If
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('Admin', TRUE);
 *
 * Template:
 *   User-Level: {iif:ADMIN,"Admin","other"}
 *
 * Result:
 *   User-Level: Admin
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_IIf extends Yuelo_Extension {

  /**
   * Print Parameter depending of condition
   *
   * @param string $check Condition
   * @param string $if Return if check == TRUE
   * @param string $else Return if check == FALSE
   * @return string
   */
  public static function Process() {
    @list($check, $if, $else) = func_get_args();

    if ($check === TRUE)
      $check = 'TRUE';
    elseif (empty($check) OR ($check === FALSE))
      $check = 'FALSE';

    $eval = sprintf('$check = (%s);', $check);

    // handle errors
    ob_start();
    eval($eval);
    if ($err = ob_get_clean()) 
      throw new Yuelo_Exception(__METHOD__.' : ('.$eval.') : '.$err);

    return $check ? $if : $else;
  }

}