<?php
/** @defgroup exec Shell functions wrapper

*/

/**
 * Factory and base class for system command execution
 *
 * @ingroup    exec
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license
 * @version    $Id: v2.4.1-46-gfa6b976 - Sat Jan 15 13:42:37 2011 +0100 $
 */
abstract class Exec {

  //---------------------------------------------------------------------------
  // ABSTRACT
  //---------------------------------------------------------------------------

  /**
   * Wrapper for make directory
   *
   * @param $dir string Directory
   * @param &$result mixed Return from system command
   * @param $sudo string Run as another user
   * @return int Return code from system command
   */
  abstract function MkDir( $dir, &$result, $sudo='' );

  /**
   * Wrapper for change mode
   *
   * @param $filemask string
   * @param $mode string
   * @param $rec string Recursive
   * @param &$result mixed Return from system command
   * @param $sudo string Run as another user, if possible
   * @return int Return code from system command
   */
  abstract function ChMod( $filemask, $mode, $rec, &$result, $sudo='' );

  /**
   * Wrapper for copy
   *
   * @param $source string From
   * @param $dest string To
   * @param &$result mixed Return from system command
   * @param $sudo string Run as another user, if possible
   * @return int Return code from system command
   */
  abstract function Copy( $source, $dest, &$result, $sudo='' );

  /**
   * Wrapper for move
   *
   * @param $source string From
   * @param $dest string To
   * @param &$result mixed Return from system command
   * @param $sudo string Run as another user, if possible
   * @return int Return code from system command
   */
  abstract function Move( $source, $dest, &$result, $sudo='' );

  /**
   * Wrapper for remove
   *
   * @param $filemask string
   * @param &$result mixed Return from system command
   * @param $sudo string Run as another user, if possible
   * @return int Return code from system command
   */
  abstract function Remove( $filemask, &$result, $sudo='' );

  /**
   * Execute system command
   *
   * Call the protected final function
   * $this->_Exec($cmd)
   * to perform the call
   *
   * @param $cmd string Command
   * @param &$result mixed Return from system command
   * @param $sudo string Run as another user, if possible
   * @return int Return code from system command
   */
  abstract function Execute( $cmd, &$result, $sudo='' );

  //---------------------------------------------------------------------------
  // PUBLIC
  //---------------------------------------------------------------------------

  /**
   * @var string
   */
  public $LastCmd;

  /**
   * Returns instance of requested class
   *
   * @throws ExecException If intialized before
   * @param $class string Name of requested class
   * @param $cache Cache
   * @param $shell string Shell binary for the instance
   */
  public static final function InitInstance( $class, Cache $cache, $shell='' ) {
    if (self::$Instance != NULL)
      throw new ExecException('Exec has been instantiated before!');

    $file = dirname(__FILE__).DIRECTORY_SEPARATOR.'exec'.DIRECTORY_SEPARATOR.$class.'.class.php';
    if (file_exists($file)) {
      require_once $file;
      $class = 'Exec_'.$class;
      self::$Instance = new $class($cache, $shell);
      return self::$Instance;
    }
    throw new ExecException('Exec: Missing file: '.$file);
  }

  /**
   * Returns instance of requested class
   *
   * @throws ExecException If not intialized yet
   */
  public static final function getInstance() {
    if(self::$Instance == NULL)
      throw new ExecException('Exec: has not been instantiated yet, use Exec::InitInstance(<class name>)');
    return self::$Instance;
  }

  /**
   * Set commands for a namespace
   *
   * @param $cmds array Commands
   * @param $namespace string
   */
  public final function setCommands( $cmds, $namespace='Core' ) {
    if (empty($cmds)) return;

    $namespace = strtoupper($namespace);
    $cmds = array_change_key_case($cmds, CASE_UPPER);

    $this->Commands[$namespace] = !isset($this->Commands[$namespace])
                                ? $cmds
                                : array_merge($this->Commands[$namespace], $cmds);
  }

  /**
   * Set commands from a INI file
   *
   * @param $file string
   * @param $required bool Log an error message if file is not valid
   */
  public final function setCommandsFromINIFile( $file, $required=TRUE ) {
    $file = str_replace('/', DIRECTORY_SEPARATOR, $file);
    if (Inifile::Parse($file, TRUE)) {
      foreach (IniFile::$Data as $namespace => $cmd) {
        if (is_array($cmd)) {
          $this->setCommands($cmd, $namespace);
        } else {
          $this->setCommands($cmd);
        }
      }
    } elseif ($required) {
      Messages::Error(Inifile::$Error);
    }
  }

  /**
   * Set commands from a XML file
   *
   * @param $file string
   * @param $required bool Log an error message if file is not valid
   */
  public final function setCommandsFromXMLFile( $file, $required=TRUE ) {
    $file = str_replace('/', DIRECTORY_SEPARATOR, $file);
    $xml = new XML_Array_Exec($this->Cache);
    $xml->Key2Lower = FALSE;
    if ($data = $xml->ParseXMLFile($file)) {
      foreach ($data as $namespace => $cmd) {
        if (is_array($cmd)) {
          $this->setCommands($cmd, $namespace);
        } else {
          $this->setCommands($cmd);
        }
      }
    } elseif ($required) {
      Messages::Error($xml->Error);
    }
  }

  /**
   * Execute a predefined system command
   *
   * @param $cmd string Command to execute
   * @param &$result array Return of executed command
   * @param $sudo string Run as defined sudo user
   * @return integer Return code of system command
   */
  public final function ExecuteCmd( $cmd, &$result, $sudo='' ) {
    $cmd1 = $cmd;
    if ($this->getCommand($cmd)){
      return $this->Execute($cmd, $result, $sudo);
    } else {
      throw new Exception('Exec: ERROR: Unknown command ['.implode(', ', (array)$cmd1).']!');
    }
  }

  //---------------------------------------------------------------------------
  // PROTECTED
  //---------------------------------------------------------------------------

  /**
   * Cache
   *
   * @var instance
   */
  protected $Cache;

  /**
   * Shell binary / EXE
   *
   * @var string
   */
  protected $Shell;

  /**
   * Class constructor
   *
   * @param $cache Cache
   * @param $shell string Shell binary for the instance
   */
  protected final function __construct( Cache $cache, $shell ) {
    $this->Cache = $cache;
    if (!empty($shell)) $this->Shell = $shell;
  }

  /**
   * Get real command for $cmd id
   *
   * @param $cmd string|array Plain command or array of command and parameters
   */
  protected final function getCommand( &$cmd ) {
    $cmdArray = is_array($cmd);
    $cmd1 = $cmdArray ? array_shift($cmd) : $cmd;
    @list($namespace, $cmd1) = explode('::', $cmd1, 2);
    if ($cmd1 == '') {
      $cmd1 = $namespace;
      $namespace = '';
    }

    $namespace = strtoupper($namespace);
    $cmd1 = strtoupper($cmd1);
    if (isset($this->Commands[$namespace][$cmd1])) {
      if ($cmdArray) {
        array_unshift($cmd, $this->Commands[$namespace][$cmd1]);
      } else {
        $cmd = $this->Commands[$namespace][$cmd1];
      }
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Execute finaly a command
   *
   * @param $cmd string
   * @param &$result array Return of executed command
   * @return int Return code
   */
  protected final function _exec( $cmd, &$result ) {
    /// Yryie::Trace(3);

    exec($cmd, $result, $rc);

    // >> Debug
    $dbg = sprintf('%s (%d)', $cmd, $rc);
    foreach ((array)$result as $v) $dbg .= "\n- " . Core::toUTF8($v);
    Yryie::Debug($dbg);
    // << Debug

    $this->LastCmd = $cmd;
    return $rc;
  }

  //---------------------------------------------------------------------------
  // PRIVATE
  //---------------------------------------------------------------------------

  /**
   *
   */
  private static $Instance;

  /**
   *
   */
  private $Commands = array();

  /**
   * Can't clone singletons
   */
  private final function __clone() {}

}

/**
 *
 */
class ExecException extends Exception {}
