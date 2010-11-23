<?php

require_once dirname(__FILE__).'/iif.class.php';

/**
 * Print Parameter depending of condition
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('LEVEL', 'Admin');
 *
 * Template:
 *   User-Level: {if:LEVEL,"=","Admin","Administrator","Other"}
 *
 * Result:
 *   User-Level: Administrator
 * @endcode
 *
 * Call with 2 or 3 arguments assume boolean check
 * In this case you can also use Yuelo_Extension_IIf
 *
 * @code
 * Content:
 *   $tpl->Assign('ACTIVE', TRUE);
 *
 * Template:
 *   Active: {if:ACTIVE,"yes","no"}
 *
 * Result:
 *   Active: yes
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_If extends Yuelo_Extension_IIf {

  /**
   * Print Parameter depending of condition
   *
   * @param string $check1 1st part of condition
   * @param string $op Operator
   * @param string $check2 2nd part of condition
   * @param string $if Return if check == TRUE
   * @param string $else Return if check == FALSE
   * @return string
   */
  public static function Process() {
    @list($check1, $op, $check2, $if, $else) = func_get_args();
    if (!$op)            $op = !$check2 ? '!=' : '==';
    elseif ($op == '=' ) $op = '==';
    elseif ($op == '<>') $op = '!=';
    $check1 = is_numeric($check1)
            ? str_replace(',', '.', $check1)
            : "'".str_replace("'", '"', $check1)."'";
    $check2 = is_numeric($check2)
            ? str_replace(',', '.', $check2)
            : "'".str_replace("'", '"', $check2)."'";
    return parent::Process(sprintf('%s %s %s', $check1, $op, $check2), $if, $else);
  }

}