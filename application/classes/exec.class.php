<?php
/**
 * Factory and base class for system command execution
 *
 * @package exec
 */
abstract class Exec {

  //---------------------------------------------------------------------------
  // ABSTRACT
  //---------------------------------------------------------------------------

  /**
   * Wrapper for make directory
   *
   * @param string $dir Directory
   * @param mixed &$result Return from system command
   * @param string $sudo Run as another user
   * @return int Return code from system command
   */
  abstract function MkDir( $dir, &$result, $sudo='' );

  /**
   * Wrapper for change mode
   *
   * @param string $filemask
   * @param string $mode
   * @param string $rec Recursive
   * @param mixed &$result Return from system command
   * @param string $sudo Run as another user, if possible
   * @return int Return code from system command
   */
  abstract function ChMod( $filemask, $mode, $rec, &$result, $sudo='' );

  /**
   * Wrapper for copy
   *
   * @param string $source From
   * @param string $dest To
   * @param mixed &$result Return from system command
   * @param string $sudo Run as another user, if possible
   * @return int Return code from system command
   */
  abstract function Copy( $source, $dest, &$result, $sudo='' );

  /**
   * Wrapper for move
   *
   * @param string $source From
   * @param string $dest To
   * @param mixed &$result Return from system command
   * @param string $sudo Run as another user, if possible
   * @return int Return code from system command
   */
  abstract function Move( $source, $dest, &$result, $sudo='' );

  /**
   * Wrapper for remove
   *
   * @param string $filemask
   * @param mixed &$result Return from system command
   * @param string $sudo Run as another user, if possible
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
   * @param string $cmd Command
   * @param mixed &$result Return from system command
   * @param string $sudo Run as another user, if possible
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
   * @throws Exec_Exception If intialized before
   * @param string $class Name of requested class
   */
  public static final function InitInstance( $class ) {
    if (self::$Instance != NULL)
      throw new Exec_Exception('Exec has been instantiated before!');

    $file = dirname(__FILE__).DIRECTORY_SEPARATOR.'exec'.DIRECTORY_SEPARATOR.$class.'.class.php';
    if (file_exists($file)) {
      require_once $file;
      $class = 'Exec_'.$class;
      self::$Instance = new $class;
      return self::$Instance;
    }
    throw new Exec_Exception('Exec: Missing file: '.$file);
  }

  /**
   * Returns instance of requested class
   *
   * @throws Exec_Exception If not intialized yet
   */
  public static final function getInstance() {
    if(self::$Instance == NULL)
      throw new Exec_Exception('Exec: has not been instantiated yet, use Exec::InitInstance(<class name>)');
    return self::$Instance;
  }

  /**
   *
   * @param $cmds Commands
   * @param $NS string
   */
  public final function setCommands( $cmds, $NS='Core' ) {
    if (empty($cmds)) return;

    $NS = strtoupper($NS);
    $cmds = array_change_key_case($cmds, CASE_UPPER);

    $this->Commands[$NS] = !isset($this->Commands[$NS])
                         ? $cmds
                         : array_merge($this->Commands[$NS], $cmds);
  }

  /**
   *
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
      Messages::addError(Inifile::$Error);
    }
  }

  /**
   *
   */
  public final function setCommandsFromXMLFile( $file, $required=TRUE ) {
    $file = str_replace('/', DIRECTORY_SEPARATOR, $file);
    $xml = new XML_Array_Exec(Registry::get('Cache'));
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
      Messages::addError($xml->Error);
    }
  }

  /**
   * Execute a predefined system command with some *nix specific settings
   *
   * @param string $cmd Command to execute
   * @param array &$result Return of executed command
   * @param string $sudo Run as defined sudo user
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
   *
   */
  protected final function __construct() {}

  /**
   *
   */
  protected final function getCommand( &$cmd ) {
    $cmdArray = is_array($cmd);
    if ($cmdArray) {
      $cmd1 = array_shift($cmd);
    } else {
      $cmd1 = $cmd;
    }
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
   *
   */
  protected final function _exec( $cmd, &$result ) {
    /// DebugStack::Trace(3);

    exec($cmd, $result, $rc);

    // >> Debug
    $dbg = sprintf('%s (%d)', $cmd, $rc);
    foreach ((array)$result as $v) $dbg .= "\n- " . Core::toUTF8($v);
    DebugStack::Debug($dbg);
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
class Exec_Exception extends Exception {}
