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
abstract class ErrorHandler {

  /**
   * Last error handler before registration of own handler
   *
   * @var string $LastErrorHandler
   */
  public static $LastErrorHandler;

  /**
   * Register the error handler
   */
  public static function register() {
    self::$LastErrorHandler = set_error_handler(array(__CLASS__, 'HandleError'));
  }

  /**
   * Attach an error handler instance
   *
   * @param ErrorHandlerI $handler
   */
  public static function attach( ErrorHandlerI $handler ) {
    self::$Handlers[] = $handler;
  }

  /**
   * Detach an error handler
   *
   * @param ErrorHandlerI $handler
   */
  public static function detach( ErrorHandlerI $handler ) {
    $id = array_search($handler, self::$Handlers, TRUE);
    // $id can be 0 (zero)!
    if ($id !== FALSE) unset(self::$Handlers[$id]);
  }

  /**
   *
   * @param int    $errno      Contains the level of the error raised
   * @param string $errstr     Contains the error message
   * @param string $errfile    Contains the filename that the error was raised in
   * @param int    $errline    Contains the line number the error was raised at
   * @param int    $errcontext An array that points to the active symbol table at
   *                           the point the error occurred. So will contain an
   *                           array of every variable that existed in the scope
   *                           the error was triggered in.
   *                           User error handler must not modify error context.
   */
  public static function HandleError( $errno, $errstr, $errfile='', $errline=0, $errcontext=array() ) {
    // make $errfile path relative
    $errfile = str_replace(@$_SERVER['DOCUMENT_ROOT'], '', $errfile);
    // $trace[0] holds the error, the additional is the backtrace to this
    $trace = self::analyseError($errno, $errstr, $errfile, $errline);

    foreach (self::$Handlers as $handler)
      $handler->HandleError($errno, $errstr, $errfile, $errline, $errcontext, $trace);

    if (ini_get('log_errors'))
        error_log(sprintf("PHP %s: %s in %s on line %d", $errno, $errstr, $errfile, $errline));
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * String repesentaion of known and unknown error codes
   *
   * @var array $Error2Str
   */
  protected static $Error2Str = array(
    NULL                => 'Unknown error',
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
    E_RECOVERABLE_ERROR => 'Recoverable Error',
  );

  /**
   * Analyse error message from php and build readable error message.
   *
   * idea from http://de3.php.net/manual/de/function.set-error-handler.php,
   * UCN by silkensedai at online dot fr, 02-May-2007 09:37
   *
   * @param int    $errno      Contains the level of the error raised
   * @param string $errstr     Contains the error message
   * @param string $errfile    Contains the filename that the error was raised in
   * @param int    $errline    Contains the line number the error was raised at
   * @return array
   */
  protected static function analyseError( $errno, $errstr, $errfile, $errline ) {

    // Handle this error?!
    if (!$errno = $errno & error_reporting()) return;

    $err = isset(self::$Error2Str[$errno])
         ? self::$Error2Str[$errno]
         : self::$Error2Str[NULL];

    $trace = array(sprintf('%s (%d): %s in %s on line %d', $err, $errno, $errstr, $errfile, $errline));

    $backtrace = debug_backtrace();
    for ($i=0; $i<2; $i++) array_shift($backtrace);

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
              $args .= 'other';
          }
        }
      }

      if (!isset($bt['file']))  $bt['file'] = '[PHP Kernel]';
      if (!isset($bt['line']))  $bt['line'] = '';

      $err = 'call: ';

      if (isset($bt['function'])) {
        if (in_array($bt['function'],array('include','require','include_once','require_once'))) {
          $err .= $bt['function'];
        } else {
          if (isset($bt['class'],$bt['type'])) $err .= $bt['class'].$bt['type'];
          $err .= $bt['function'].'('.$args.')';
        }
      } else {
        $err .= print_r($bt, TRUE);
      }
      $err .= ' in file: '.$bt['file'].' ['.$bt['line'].']';
      $trace[] = $err;
    }

    return $trace;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * @var array $Handlers
   */
  private static $Handlers = array();

}