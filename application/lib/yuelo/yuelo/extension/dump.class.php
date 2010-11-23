<?php
/**
 * Prints variable content for debug purpose
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_Dump extends Yuelo_Extension {

  /**
   * Prints variable content for debug purpose
   *
   * @param mixed $var Variable to format
   * @return string
   */
  public static function Process() {
    @list($var) = func_get_args();
    // Extra function for recursive calling
    return self::format($var);
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Format a variable by type
   *
   * @param mixed $var Variable to format
   * @return string
   */
  private static function format( $var ) {
    if (is_array($var)) {
      $return = '<table border="0">';
      foreach ($var as $key => $val) {
        $return .= '<tr>'
                  .'<td style="border:0;padding:0;vertical-align:top"><tt>['.htmlspecialchars($key).']</tt></td>'
                  .'<td style="border:0;padding:0 10px;vertical-align:top"><tt>=</tt></td>'
                  .'<td style="border:0;padding:0">'
                  .(is_array($val) ? '<tt>Array (</tt><div style="padding:0 20px">' : '')
                  .self::format($val)
                  .(is_array($val) ? '</div>)' : '')
                  .'</td>'
                  .'</tr>';
      }
      $var = $return.'</table>';
    } elseif (is_object($var)) {
      $var = print_r($var,TRUE);
    } else {
      switch (TRUE) {
        case is_null($var):      $var = 'NULL';                          break;
        case is_bool($var):      $var = $var ? 'TRUE' : 'FALSE';         break;
        case is_numeric($var):   /* do nothing */                        break;
        default: /* strings */   $var = '"'.htmlspecialchars($var).'"';  break;
      }
      $var = '<tt>'.$var.'</tt>';
    }
    return $var;
  }

}