<?php
/** @defgroup ErrorHandler Error handling

*/

/**
 * Abstract error handler
 *
 * @ingroup    ErrorHandler
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
abstract class ErrorHandler implements ErrorHandlerI {

  /**
   * Return with HTML tags
   */
  public static $HTML = TRUE;

  /**
   * @param string $class Errorhandler class to register
   */
  public static final function Register( $class ) {
    $file = dirname(__FILE__).'/errorhandler/'.$class.'.class.php';
    if (file_exists($file)) {
      require_once $file;
      set_error_handler(array('ErrorHandler_'.$class, 'HandleError'));
    } else {
      throw new Exception('Error: Missing file: '.$file);
    }
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Analyse error message from php and build readable error message.
   *
   * idea from http://de3.php.net/manual/de/function.set-error-handler.php,
   * UCN by silkensedai at online dot fr, 02-May-2007 09:37
   *
   * @param string $errno Error number
   * @param string $errstr Error message
   * @param string $errfile File where the error occoured
   * @param integer $errline Line where the error occoured
   * @return string
   */
  protected static function analyseError( $errno, $errstr, $errfile, $errline ) {
    if (!$errno = $errno & error_reporting()) return;

    defined('E_STRICT') || define('E_STRICT', 2048);
    defined('E_RECOVERABLE_ERROR') || define('E_RECOVERABLE_ERROR', 4096);

    $err_str = array(
      E_ERROR             => 'Error',
      E_WARNING           => 'Warning',
      E_PARSE             => 'Parse Error',
      E_NOTICE            => 'Notice',
      E_CORE_ERROR        => 'Core Error',
      E_CORE_WARNING      => 'Core Warning',
      E_COMPILE_ERROR     => 'Compile Error',
      E_COMPILE_WARNING   => 'Compile Warning',
      E_USER_ERROR        => 'User Error',
      E_USER_WARNING      => 'User Warning',
      E_USER_NOTICE       => 'User Notice',
      E_STRICT            => 'Strict Notice',
      E_RECOVERABLE_ERROR => 'Recoverable Error'
    );

    $err = isset($err_str[$errno]) ? $err_str[$errno] : 'Unknown error ('.$errno.')';
    $err .= self::$HTML
          ? sprintf(': <b>%s</b> in <b>%s</b> on line <b>%d</b></tt>'."\n", $errstr, $errfile, $errline)
          : sprintf(': %s in %s on line %d'."\n", $errstr, $errfile, $errline);;
    $err .= self::backtrace(3);
    if (self::$HTML) $err = '<tt>'.$err.'</tt>';
    return $err;
  }

  /**
   * Format output of debug_backtrace() into some readable.
   *
   * Idea from http://php.net/manual/function.debug-backtrace.php
   * UCN by http://synergy8.com, 14-Dec-2005 07:37,
   * diz at ysagoon dot com, 23-Nov-2004 11:40
   *
   * @param integer $skip Skip last x trace steps
   * @return string
   */
  protected static function backtrace( $skip=1 ) {
    $output = '';
    $backtrace = debug_backtrace();

    for ($i=0; $i<$skip; $i++) array_shift($backtrace);

    foreach ($backtrace as $bt) {
      $args = '';
      if (isset($bt['args'])) {
        foreach ((array)$bt['args'] as $a) {
          if (!empty($args)) $args .= ', ';
          switch (gettype($a)) {
            case 'integer':
            case 'double':
              $args .= $a;
              break;
            case 'string':
              $a = substr($a, 0, 64);
              if (self::$HTML) $a = htmlspecialchars($a);
              // string was trimed
              if (strlen($a) > 64) $a .= '...';
              $args .= '"'.$a.'"';
              break;
            case 'array':
              $args .= 'Array('.count($a).')';
              break;
            case 'object':
              $args .= 'Object('.get_class($a).')';
              break;
            case 'resource':
              $args .= 'Resource('.strstr($a, '#').')';
              break;
            case 'boolean':
              $args .= $a ? 'TRUE' : 'FALSE';
              break;
            case 'NULL':
              $args .= 'NULL';
              break;
            default:
              $args .= 'Unknown';
          }
        }
      }

      if (!isset($bt['file']))  $bt['file'] = '[PHP Kernel]';
      if (!isset($bt['line']))  $bt['line'] = '';

      $output .= self::$HTML ? '<b>call:</b> ' : 'call: ';

      if (isset($bt['function'])) {
        if (in_array($bt['function'],array('include','require','include_once','require_once'))) {
          $output .= $bt['function'].self::NL();
        } else {
          if (isset($bt['class'],$bt['type'])) $output .= $bt['class'].$bt['type'];
          $output .= $bt['function'].'('.$args.')'.self::NL();
        }
      } else {
        $output .= print_r($bt, TRUE);
      }
      $output .= self::$HTML
               ? '<b>file:</b> '.$bt['file'].' ['.$bt['line'].']'
               : 'file: '.$bt['file'].' ['.$bt['line'].']';
      $output .= self::NL();
    }
    if (self::$HTML)
      $output = '<div class="error_handler" style="font-family:monospace">'.$output.'</div>';
    return $output;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   *
   */
  private static function NL() {
    return ( self::$HTML ? '<br>' : '' ) . "\n";
  }
}