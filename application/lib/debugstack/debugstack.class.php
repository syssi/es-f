<?php
/**
 * prior PHP 5.1
 */
if (!isset($_SERVER['REQUEST_TIME'])) $_SERVER['REQUEST_TIME'] = time();

/** @defgroup DebugStack

*/

/**
 * Package DebugStack
 *
 * Use to buffer debugging informations
 *
 * @ingroup    DebugStack
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2006-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class DebugStack {

  /**
   *
   */
  const VERSION = '2.0 - 2010/08/01';

  /**
   *
   */
  const MAXSTRLEN = 100;

  /**
   *
   */
  const SECONDS = 0;

  /**
   *
   */
  const MICROSECONDS = 1;

  /**
   *
   * @var int
   */
  public static $TimeUnit = 0;

  /**
   * File to write the debug stack during each add()
   *
   * @var string
   */
  public static $TraceFile;

  /**
   *
   * @var string
   */
  public static $TraceDelimiter = "\t";

  /**
   *
   * @var string
   */
  public static $TimerStart = '>>';

  /**
   *
   * @var string
   */
  public static $TimerStop = '<<';

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Register error handler
   *
   * @return void
   */
  public static function Register() {
    set_error_handler(array(__CLASS__, 'HandleError'));
  } // function Register()

  /**
   * Set Active/Inactive
   *
   * Default class state: Inactive
   *
   * @param bool $active
   * @return void
   */
  public static function Active( $active=NULL ) {
    self::Trace();
    if (isset($active)) self::$Active = (bool)$active;
    return self::$Active;
  } // function Active()

  /**
   * Set TimeUnit...
   *
   * @param int $TimeUnit (self::SECONDS|self::MIRCOSECONDS)
   * @return void
   */
  public static function TimeUnit( $TimeUnit ) {
    self::$TimeUnit = (int)$TimeUnit;
  } // function TimeUnit()

  /**
   * Add info
   *
   * If more parameters are passed, $message will handeld with printf.
   *
   * @uses add
   * @param string $message
   * @return void
   */
  public static function Info( $message ) {
    if (func_num_args() > 1) {
      $args = func_get_args();
      $message = array_shift($args);
      $message = vsprintf($message, $args);
    }
    self::add($message, 'info');
  } // function Info()

  /**
   * Add code
   *
   * If more parameters are passed, $message will handeld with printf.
   *
   * @uses add
   * @param string $message
   * @return void
   */
  public static function Code( $message ) {
    if (func_num_args() > 1) {
      $args = func_get_args();
      $message = array_shift($args);
      $message = vsprintf($message, $args);
    }
    self::add($message, 'code');
  } // function Code()

  /**
   * Add state
   *
   * If more parameters are passed, $message will handeld with printf.
   *
   * @uses add
   * @param string $message
   * @return void
   */
  public static function State( $message ) {
    if (func_num_args() > 1) {
      $args = func_get_args();
      $message = array_shift($args);
      $message = vsprintf($message, $args);
    }
    self::add($message, 'state');
  } // function State()

  /**
   * Add SQL
   *
   * If more parameters are passed, $message will handeld with printf.
   *
   * @uses add
   * @param string $message
   * @return void
   */
  public static function SQL( $message ) {
    if (func_num_args() > 1) {
      $args = func_get_args();
      $message = array_shift($args);
      $message = vsprintf($message, $args);
    }
    self::add($message, 'sql');
  } // function SQL()

  /**
   * Add debug
   *
   * If more parameters are passed, $message will handeld with printf.
   *
   * @uses add
   * @param string $message
   * @return void
   */
  public static function Debug( $message ) {
    if (func_num_args() > 1) {
      $args = func_get_args();
      $message = array_shift($args);
      $message = vsprintf($message, $args);
    }
    self::add($message, 'debug');
  } // function Debug()

  /**
   * Add a warning
   *
   * If more parameters are passed, $message will handeld with printf.
   *
   * @uses add
   * @param string $message
   * @return void
   */
  public static function Warning( $message ) {
    if (func_num_args() > 1) {
      $args = func_get_args();
      $message = array_shift($args);
      $message = vsprintf($message, $args);
    }
    self::add($message, 'warning');
  } // function Warning()

  /**
   * Add an error
   *
   * If more parameters are passed, $message will handeld with printf.
   *
   * @uses add
   * @param string $message
   * @return void
   */
  public static function Error( $message ) {
    if (func_num_args() > 1) {
      $args = func_get_args();
      $message = array_shift($args);
      $message = vsprintf($message, $args);
    }
    self::add($message, 'error');
  } // function Error()

  /**
   * Add a trace
   *
   * @uses add
   * @param int $level Trace level from here
   * @param bool $full Full trace, all remaining levels
   * @param bool $params Include function parameters
   * @return void
   */
  public static function Trace( $level=1, $full=FALSE, $params=TRUE ) {
    if (!self::$Active) return;

    $trace = debug_backtrace();

    if (!isset($trace[$level])) while (!isset($trace[$level])) $level--;

    for ($i=$level; $i>0; $i--) array_shift($trace);

    $traces = $full ? $trace : array($trace[0]);

    foreach ($traces as $trace) {
      $msg = isset($trace['file'])
           ? str_replace(@$_SERVER['DOCUMENT_ROOT'].'/', '', $trace['file'])
           : '[PHP Kernel]';
      if (isset($trace['line'])) $msg .= ' [' . $trace['line'] . ']';
      $msg .= ' ';

      if (isset($trace['object']))
        $msg .= sprintf('%s[%s]%s%s', get_class($trace['object']), $trace['class'], $trace['type'], $trace['function']);
      elseif (isset($trace['class']))
        $msg .= sprintf('%s%s%s', $trace['class'], $trace['type'], $trace['function']);
      elseif (isset($trace['function']))
        $msg .= $trace['function'];

      if ($params) {
        $args = array();
        if (!empty($trace['args']))
          foreach ($trace['args'] as $arg) $args[] = self::format($arg);
        $msg .= '(' . implode(', ', $args) . ')';
      }
      self::add($msg, 'trace');
    }
  } // function Trace()

  /**
   * Generic add message
   *
   * @param string $message
   * @param string $type
   * @return void
   */
  public static function add( $message='', $type='info' ) {
    if (!self::$Active) return;

    $ts = microtime(TRUE);
    $call = self::called(3);
    if ($message === '' ) $ts = $type = $call[0] = $call[1] = '';
    $data = array( $ts, $type, $call[0], $call[1], $message, self::$TimerLevel);
    self::$Data[] = $data;

    if (self::$TraceFile AND $fh = fopen(self::$TraceFile, 'a')) {
      // overwrite locale settings!!
      $data[0] = sprintf('%.1f ms', ($data[0]-$_SERVER['REQUEST_TIME'])*1000);
      // unset timer level
      unset($data[5]);
      fwrite($fh, str_replace("\n", '\n', implode(self::$TraceDelimiter, $data))."\n");
      fclose($fh);
    }
    return $ts;
  } // function add()

  /**
   * Starts a timer
   *
   * @param string $id
   * @param string $name
   * @return void
   */
  public static function StartTimer( $id, $name='', $avg='' ) {
    if (!self::$Active) return;
    if (isset(self::$Timer[$id]))
      throw new DebugStackException('Error: Timer "'.$id.'" ('.$name.') ist still started!');

    if ($name == '') $name = $id;
    self::add(self::$TimerStart . ' ' . $name, 'timer');
    self::$Timer[$id] = array(microtime(TRUE), $name, $avg);
    self::$TimerLevel++;
  } // function StartTimer()

  /**
   * Stop a timer
   *
   * @param string $id Stop last timer if empty
   * @return void
   */
  public static function StopTimer( $id='' ) {
    if (!count(self::$Timer)) return;

    if ($id == '') {
      // get last id from stack
      $ids = array_keys(self::$Timer);
      $id = end($ids);
    }
    list($start, $name, $avg) = self::$Timer[$id];
    $diff = self::timef(microtime(TRUE)-$start);
    self::$TimerLevel--;
    self::add(sprintf('%s %s: %s', self::$TimerStop, $name, $diff), 'timer');
    unset(self::$Timer[$id]);

    if ($avg != '') {
      if (!isset(self::$AVG[$avg])) self::$AVG[$avg] = array(0,0);
      self::$AVG[$avg][0] += $diff;
      self::$AVG[$avg][1]++;
    }

    return $diff;
  } // function StopTimer()

  /**
   * Get messages
   *
   * @param string $type Get only messages of this type
   * @return array
   */
  public static function get( $type='' ) {
    if ($type != '') {
      $return = array();
      foreach (self::$Data as $data)
        if ($data[1] == $type) $return[] = $data;
    } else {
      $return = self::$Data;
    }
    return $return;
  } // function get()

  /**
   * Finalize all
   *
   * @return void
   */
  public static function Finalize() {
    // stop all open timer
    for ($i=count(self::$Timer); $i>0; $i--) self::StopTimer();

    self::add('Total included files: '.count(get_included_files()), 'debug');

    foreach (self::$AVG as $avg=>$data)
      self::add(sprintf('avg. %1$s = %4$s (%3$d in %2$s)',
                        $avg, self::timef($data[0]), $data[1],
                        self::timef($data[0]/$data[1], self::MICROSECONDS)),
                'debug');

    self::add(count(self::$Data).' Messages', 'debug');
  } // function Finalize()

  /**
   * Get all messages as comma separated data
   *
   * @param bool $delta Show delta time since start, not absolut timestamp
   * @return string
   */
  public static function CSV() {
    while (count(self::$Timer)) self::StopTimer();

    $csv = array('Time'.self::$TraceDelimiter.'Type'.self::$TraceDelimiter.'Class'
                .self::$TraceDelimiter.'Function'.self::$TraceDelimiter.'Message');

    foreach (self::$Data as $data) {
      if (!$data[0]) continue;

      $data[0] -= $_SERVER['REQUEST_TIME'];
      if (is_array($data[4])) $data[4] = self::format($data[4]);

      // remove timer level
      unset($data[5]);

      $fields = array();
      foreach ($data as $value) {
        if (strpos($value, '"') !== FALSE)
          $value = '"' . str_replace('"', '""', $value) . '"';
        if (strpos($value, self::$TraceDelimiter) !== FALSE)
          $value = '"' . $value . '"';
        // remove add. white spaces AND newlines
        $fields[] = preg_replace('~\s+~', ' ', trim($value));
      }
      $csv[] = implode(self::$TraceDelimiter, $fields);
    }
    return implode("\n", $csv)."\n";
  } // function CSV()

  /**
   * Save messages as csv to a file
   *
   * @param string $file File name to save to
   * @param bool $delta Show delta time since start, not absolut timestamp
   * @param bool $append Append to the file, if exists
   * @return void
   */
  public static function Save( $file, $append=FALSE ) {
    $fh = fopen($file, ($append?'a':'w'));
    if ($fh) {
      fwrite($fh, self::CSV());
      fclose($fh);
    } else {
      throw new DebugStackException('Can\'t write to file: '.$file);
    }
  } // function Save()

  /**
   * Output the messages as HTML table
   *
   * @param bool $delta Show delta time since start, not absolut timestamp
   * @return void
   */
  public static function Output( $delta=FALSE, $CssJs=FALSE ) {
    if ($CssJs) echo self::getCSS(), self::getJS();
    echo self::Render($delta);
  } // function Output()

  /**
   * Return messages as HTML table content
   *
   * All rows/cells are taged with classes, so you can format with CSS as you like
   *
   * @param bool $delta Show delta time since start, not absolut timestamp
   * @return string
   */
  public static function Render( $delta=FALSE ) {
    foreach (self::$Data as $row=>$data) $types[$data[1]] = $data[1];
    $sTypes = '';
    $cb = '<input type="checkbox" style="margin-left:1.5em" '
         .'onchange="DebugStackSwitch(\'%s\', this.checked)" checked>%s';
    foreach ($types as $type) if ($type) $sTypes .= sprintf($cb, $type, ucwords($type));
    unset($types);

    $aRows = array();
    // last time stamp
    $lts = $_SERVER['REQUEST_TIME'];
    
    $sRow = file_get_contents(dirname(__FILE__).'/row.html');

    foreach (self::$Data as $row=>$data) {
      @list($time, $type, $class, $func, $msg, $level) = $data;
      $cls = $row%2 ? 'even' : 'odd';

      if ($msg) {
        $ts = $delta
            ? self::timef($time-$_SERVER['REQUEST_TIME'])
            : date('H:i:s', $time) . substr(sprintf('%.5f', $data[0]), -6);
        $dts = self::timef($time-$lts);
        $lts = $time;
        $msg = is_array($msg)
             ? '<pre>' . htmlspecialchars(print_r($msg, TRUE)) . '</pre>'
             : ( ($type != 'handler') ? htmlspecialchars($msg) : $msg );

        $utype = ucwords($type);
        if (!$class) $class = '&nbsp;';
        if (!$func) $func = '&nbsp;';

        $aRows[] = sprintf($sRow, $type, $cls, $ts, $dts, $utype, $class, $func, $msg);
      } else {
        // empty row
        $aRows[] = '<tr class="'.$cls.'"><td colspan="6">&nbsp;</td></tr>';
      }
    }
    return sprintf(file_get_contents(dirname(__FILE__).'/table.html'),
                   $sTypes, implode($aRows));
  } // function Render()

  /**
   * Reset all data
   *
   * @return void
   */
  public static function reset() {
    self::$Data = array();
  } // function reset()

  /**
   * Error handler
   *
   * @param int $errno
   * @param string $errstr
   * @param string $errfile
   * @param int $errline
   * @param array $errcontext
   */
  public static function HandleError( $errno, $errstr, $errfile, $errline, $errcontext ) {

    if (!$errno = $errno & error_reporting()) return;

    $errfile = str_replace(@$_SERVER['DOCUMENT_ROOT'].'/', '', $errfile);

    // from PHP 5
    defined('E_STRICT')            || define('E_STRICT',             2048);
    // from PHP 5.2.0
    defined('E_RECOVERABLE_ERROR') || define('E_RECOVERABLE_ERROR',  4096);
    // from PHP 5.3.0
    defined('E_DEPRECATED')        || define('E_DEPRECATED',         8192);
    defined('E_USER_DEPRECATED')   || define('E_USER_DEPRECATED',   16384);

    static $Err2Str = array(
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

    $str = isset($Err2Str[$errno]) ? $Err2Str[$errno] : 'Unknown error: '.$errno;
    $errmsg = sprintf('[%s] %s in %s (%d)'."\n", $str, $errstr, $errfile, $errline);
    self::add($errmsg, 'handler');
    self::Trace(2, TRUE, FALSE);
    self::Debug($errcontext);
    if ($errno & (E_ERROR|E_CORE_ERROR|E_USER_ERROR)) die(self::getCSS().self::HTML());
  } // function HandleError()

  /**
   * Get a good default CSS
   *
   * @param bool $withTag With style tags around CSS
   * @return string
   */
  public static function getCSS( $withTag=TRUE ) {
    return ( $withTag ? '<style type="text/css">'."\n" : '' )
         . file_get_contents(dirname(__FILE__).'/style.css')
         . ( $withTag ? '</style>' : '' );
  } // function getCSS();

  /**
   * Return JS for type select
   *
   * @param bool $withTag With script tags around script
   * @return string
   */
  public static function getJS( $withTag=TRUE ) {
    return ( $withTag ? '<script type="text/javascript">'."\n" : '' )
         . file_get_contents(dirname(__FILE__).'/script.js')
         . ( $withTag ? '</script>' : '' );
  } // function getJS()

  /**
   * Format an array to a string according to the type of data
   *
   * @param array $value Value to format
   * @return string
   */
  public static function format( $value, $truncate=TRUE ) {
    $args = '';
    switch (gettype($value)) {
      case 'integer':
      case 'double':
        $args .= $value;
        break;
      case 'string':
        if ($truncate) {
          $v = substr($value, 0, self::MAXSTRLEN);
          if ($v != $value) $v .= '...';
          $value = $v;
        }
        $args .= '\'' . $value . '\'';
        break;
      case 'array':
        $aa = array();
        foreach ($value as $key=>$val)
          if ($key != 'GLOBALS') $aa[] = sprintf('\'%s\'=>%s', $key, self::format($val));
        $args .= 'Array(' . implode(', ', $aa) . ')';
        break;
      case 'object':
        $args .= 'Object(' . get_class($value) . ')';
        break;
      case 'resource':
        $args .= 'Resource('. strstr($value, '#') . ')';
        break;
      case 'boolean':
        $args .= $value ? 'TRUE' : 'FALSE';
        break;
      case 'NULL':
        $args .= 'NULL';
        break;
      default:
        $args .= '[Unknown]';
    }
    return $args;
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Data storage
   *
   * @var array
   */
  protected static $Data = array();

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Active flag
   *
   * @var bool
   */
  private static $Active = TRUE;

  /**
   * Active timer data
   *
   * @var array
   */
  private static $Timer = array();

  /**
   * Active timer data
   *
   * @var array
   */
  private static $AVG = array();

  /**
   * Active timer level
   *
   * @var int
   */
  private static $TimerLevel = 0;

  /**
   * Format time according to {@link $TimeUnit}
   *
   * @param int $time Timestamp
   * @return string
   */
  private static function timef( $time, $format=NULL ) {
    if (!isset($format)) $format = self::$TimeUnit;
    return $format == self::MICROSECONDS
         ? sprintf('%.2fms', $time*1000)
         : sprintf('%.5fs', $time);
  }

  /**
   * Return backtrace context, class and function
   *
   * @param int $skip Skip level
   * @return array Array( class, function )
   */
  private static function called( $skip ) {
    $bt = debug_backtrace();
    return array(@$bt[$skip]['class'], @$bt[$skip]['function']);
  }
}

/**
 * Exception used by {link:DebugStack}
 *
 * @ingroup    DebugStack
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2006-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class DebugStackException extends Exception {}

/**
 * Add versions to DebugStack log
 */
if (isset($GLOBALS['DEBUGSTACK_ADD_VERSIONS']) AND
    $GLOBALS['DEBUGSTACK_ADD_VERSIONS'] === TRUE) {
  DebugStack::add(php_uname(), 'version');
  if (isset($_SERVER['SERVER_SOFTWARE']))
    DebugStack::add($_SERVER['SERVER_SOFTWARE'], 'version');
  DebugStack::add('PHP '.PHP_VERSION, 'version');
  DebugStack::add('DebugStack '.DebugStack::VERSION, 'version');
  DebugStack::add();  // empty line
}
