<?php
/**
 * Package Cache
 *
 * description ...
 *
 * @ingroup  Cache
 * @version  1.0.0
 * @author
 */

/**
 * Class Cache
 *
 * description ...
 *
 * @package    Cache
 * @version
 */
abstract class Cache {

  // -------------------------------------------------------------------------
  // ABSTRACT
  // -------------------------------------------------------------------------

  /**
   * Function set...
   *
   * @param string $key
   * @param mixed $data
   * @return mixed
   */
  abstract public function __destruct();

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Function set...
   *
   * @param string $key
   * @param mixed $data
   * @return mixed
   */
  public function set( $key, $data ) {
    $this->Data[$this->map($key)] = array(time(), $data);
  } // function set()

  /**
   * Function get...
   *
   * @param string $key
   * @param int $timestamp
   * @return mixed
   */
  public function get( $key, $timestamp=0 ) {
    $key = $this->map($key);
    if (!isset($this->Data[$key])) return;

    if ($timestamp <= $this->Data[$key][0]) return $this->Data[$key][1];

    // else drop data for this key
    $this->remove($key);
  } // function get()

  /**
   * Function remove...
   *
   * @param string $key
   * @return void
   */
  public function remove( $key ) {
    $key = $this->map($key);
    if (isset($this->Data[$key])) unset($this->Data[$key]);
  } // function remove()

  /**
   * Function clear...
   *
   * @return void
   */
  public function clear() {
    $this->Data = array();
  } // function clear()

  /**
   * Function Init...
   *
   * @public
   * @param array $Settings
   * @return void
   */
  public static function &Init( $class, $Settings=array() ) {
    if (isset(self::$Instance))
      throw new Cache_Exception(__CLASS__.' is still initialized!');
    $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR
          . str_replace('_', DIRECTORY_SEPARATOR, strtolower($class)) . '.class.php';
    require_once $file;
    $class = 'Cache_'.$class;
    self::$Instance = new $class($Settings);
    return self::$Instance;
  }

  /**
   * Function getInstance...
   *
   * @public
   * @return CacheI
   */
  public static function &getInstance() {
    if (!isset(self::$Instance))
      throw new Cache_Exception(__CLASS__.' is not initialized, call Init() before!');
    return self::$Instance;
  } // function getInstance()

  /**
   * Function save...
   *
   * <code>
   * while (Cache::save($key, $data[, $timestamp])) {
   *   ...
   *   $data = ...;
   * }
   * echo $data;
   * </code>
   *
   * @public
   * @param string $key
   * @param mixed &$data
   * @param int $timestamp
   * @return void
   */
  public function save( $key, &$data, $timestamp=0 ) {
    if ($key == end($this->Stack)) {
      $this->set($key, $data);
      // done, remove id from stack
      array_pop($this->Stack);
      return FALSE;
    } elseif (in_array($key, $this->Stack)) {
      // $key is in stack, but NOT on top
      throw new Cache_Exception('Cache stack problem: '.end($this->Stack).' not properly finished!');
    } else {
      $data = $this->get($key, $timestamp);
      if ($data !== NULL) {
        // Content found in cache, done
        return FALSE;
      } else {
        // not found yet, let's go
        $this->Stack[] = $key;
        return TRUE;
      }
    }
  } // function save()

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   *
   */
  protected $Data = array();

  /**
   * Class constructor
   *
   * @protected
   * @param array $Settings
   * @return void
   */
  protected function __construct( $Settings=array() ) {
    $this->Stack = array();
  } // function __construct()

  /**
   * Function map...
   *
   * @protected
   * @param string $key
   * @return string
   */
  protected function map( $key ) {
    return strtolower($key);
  } // function map()

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   *
   * @private
   * @var CacheI
   */
  private static $Instance = NULL;

  /**
   * @var array
   */
  private $Stack;

}

/**
 * Class Cache_Exception
 *
 * description ...
 *
 * @package    Cache
 * @version
 */
class Cache_Exception extends Exception {}