<?php
/**
 * Find out if and regular expression matches or get a match by id
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('NAME', '0.1.1beta');
 *
 * Template:
 *   {regex:NAME,"~beta\s*$~i"}
 *   &lt;!-- or with filters --&gt;
 *   {regex:NAME|trim|lowercase,"~beta$~"}
 *   {regex:NAME,"~(.*?)beta\s*$~i","1"}
 *
 * Result:
 *   1
 *   0.1.1
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_RegEx extends Yuelo_Extension {

  /**
   * Find out if and regular expression matches or get a match by id
   *
   * @static
   * @param string $param
   * @param string $pattern Regular expression
   * @return bool
   */
  public static function Process() {
    @list($param, $pattern, $arg) = func_get_args();
    $match = preg_match( $pattern, $param, $args);
    return ($arg AND isset($args[$arg])) ? $args[$arg] : $match;
  }

}