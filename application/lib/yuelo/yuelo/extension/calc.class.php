<?php
/**
 * Make a calcuation
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('VAR1', 2);
 *   $template->assign('VAR2', 3);
 *
 * Template:
 *   {calc:VAR1,"*",VAR2}
 *
 * Output:
 *    6
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Calc extends Yuelo_Extension {

  /**
   * Make a calcuation
   *
   * @param numeric $param1
   * @param char $op Operator: + - * /
   * @param numeric $param2
   * @return string Return NULL on for defined operators
   * @throws Yuelo_Exception Throw exception in case of division by zero
   */
  public static function Process() {
    @list($param1, $op, $param2) = func_get_args();
    switch ($op) {
      case '+': return $param1 + $param2;
      case '-': return $param1 - $param2;
      case '*': return $param1 * $param2;
      case '/':
        if ($param2 != 0) return $param1 / $param2;
        else throw new Yuelo_Exception('Yuelo_Extension_Calc: Division by zero!');
    }
  }

}