<?php
/** @defgroup Cache Caching classes

*/

/**
 * Abstract class Cache
 *
 * The following settings are supported:
 * - token    : used to build unique cache ids (general)
 *
 * @ingroup    Cache
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license
 * @version    $Id$
 */
abstract class Cache {

  /**
   * Mark all cached data with this prefix to check consistency
   */
  const MARKER = 'CACHE // ';

  /**
   * Take first ID_LENGTH characters from generated MD5 hash
   */
  const ID_LENGTH = 8;

  // -------------------------------------------------------------------------
  // ABSTRACT
  // -------------------------------------------------------------------------

  /**
   * Function set...
   *
   * @param string $id
   * @param mixed $data
   * @param $ttl int Time to live or timestamp
   *                 0  - expire never
   *                 >0 - Time to live
   *                 <0 - Timestamp of expiration
   * @return bool
   */
  abstract public function set( $id, $data, $ttl=0 );

  /**
   * Function get...
   *
   * @param $id string
   * @param $expire int Time to live or timestamp
   *                    0  - expire never
   *                    >0 - Time to live
   *                    <0 - Timestamp of expiration
   * @return mixed
   */
  abstract public function get( $id, $expire=0 );

  /**
   * Function delete...
   *
   * @param string $id
   * @return bool
   */
  abstract public function delete( $id );

  /**
   * Function flush...
   *
   * @return bool
   */
  abstract public function flush();

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Cache availability, rewrite if required
   */
  public static function available() {
    return TRUE;
  }

  /**
   * Function factory...
   *
   * @param $caches array Caches to test for availability
   *                      If empty, the follwing caches are tested (in this order):
   *                      - APC
   *                      - EAccelerator
   *                      - XCache
   *                      - MemCache
   *                      - File (always available and prefered obverse Files)
   *                      - Files (always available)
   *                      - Session (always available)
   *                      - Mock (always available)
   * @return string|array If $all is FALSE, the first possible cache from
   *                      $caches is returned, if $all is TRUE, an array of
   *                      all available caches will be returned
   */
  public static final function test( $caches=array(), $all=FALSE ) {
    if (empty($caches)) {
      $caches = array('APC', 'EAccelerator', 'XCache', 'MemCache');
      $testall = TRUE;
    } else {
      if (!is_array($caches)) $caches = array($caches);
      $testall = FALSE;
    }
    $available = array();
    foreach ($caches as $cache) {
      self::load($cache);
      switch (strtolower($cache)) {
        case 'apc':
          if (Cache_APC::available()) $available[] = $cache;
          break;
        case 'eaccelerator':
          if (Cache_EAccelerator::available()) $available[] = $cache;
          break;
        case 'xcache':
          if (Cache_XCache::available()) $available[] = $cache;
          break;
        case 'memcache':
          if (Cache_MemCache::available()) $available[] = $cache;
          break;
        case 'file':
        case 'files':
        case 'session':
        case 'mock':
          $available[] = $cache;
          break;
      } // switch
    }
    if ($testall) {
      $available[] = 'File';
      $available[] = 'Files';
      $available[] = 'Session';
      $available[] = 'Mock';
    }
    return $all ? $available : (isset($available[0]) ? $available[0] : FALSE);
  }

  /**
   * Function factory...
   *
   * @param string $class Cache class to create
   * @param array $settings
   * @return void
   */
  public static final function factory( $class, $settings=array() ) {
    self::load($class);
    $class = 'Cache_'.$class;
    return new $class($settings);
  }

  /**
   * Function save...
   *
   * <code>
   * $cache = Cache::factory('...');
   * while ($cache->save($id, $data[, $ttl])) {
   *   ...
   *   $data = ...;
   * }
   * echo $data;
   * </code>
   *
   * @param $id string
   * @param &$data mixed
   * @param $ttl int Time to live, if set to 0, expire never
   * @return bool
   */
  public final function save( $id, &$data, $ttl=0 ) {
    if ($id == end($this->stack)) {
      $this->set($id, $data, $ttl);
      // done, remove id from stack
      array_pop($this->stack);
      return FALSE;
    } elseif (in_array($id, $this->stack)) {
      // $id is in stack, but NOT on top
      throw new Cache_Exception(__CLASS__.': Stack problem - '.end($this->stack).' not properly finished!', 99);
    } else {
      $data = $this->get($id, $ttl);
      if ($data !== NULL) {
        // Content found in cache, done
        return FALSE;
      } else {
        // not found yet, let's go
        $this->stack[] = $id;
        return TRUE;
      }
    }
  } // function save()

  /**
   * Increments value of an item by the specified value.
   *
   * If item specified by key was not numeric and cannot be converted to a
   * number, it will change its value to value.
   * increment() does not create an item if it doesn't already exist.
   *
   * @param string $id
   * @param mixed $value
   * @return num New items value on success or FALSE on failure.
   */
  public function inc( $id, $value=1 ) {
    return $this->modify($id, $value);
  }

  /**
   * Decrements value of the item by value.
   *
   * If item specified by key was not numeric and cannot be converted to a
   * number, it will change its value to value.
   * increment() does not create an item if it doesn't already exist.
   *
   * Similarly to increment(), current value of the item is being converted to
   * numerical and after that value is substracted.
   *
   * @param string $id
   * @param mixed $value
   * @return num New items value on success or FALSE on failure.
   */
  public function dec( $id, $value=1 ) {
    return $this->modify($id, -$value);
  }

  /**
   * Function __set
   *
   * Use implicit $ttl == 0
   *
   * @param string $name
   * @param mixed $value
   * @return mixed
   */
  public final function __set( $name, $value ) {
    $this->set($name, $value);
  }

  /**
   * Function __get...
   *
   * @param string $name
   * @return mixed
   */
  public final function __get( $name ) {
    return $this->get($name);
  }

  /**
   * Function __isset...
   *
   * @param string $name
   * @return mixed
   */
  public function __isset( $name ) {
    return ($this->get($name) !== NULL);
  }

  /**
   * Function __unset...
   *
   * @param string $name
   * @return mixed
   */
  public final function __unset( $name ) {
    return $this->delete($name);
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   *
   * @var string
   */
  protected $token;

  /**
   * Master timestamp
   *
   * @var int
   */
  protected $ts;

  /**
   * Class constructor
   *
   * @param array $settings
   * @return void
   */
  protected function __construct( $settings=array() ) {
    $this->ts = time();
    $this->token = !empty($settings['token']) ? $settings['token'] : md5(__FILE__);
    if (isset($settings['packer'])) {
      $this->packer = $settings['packer'];
      if (!is_object($this->packer) OR !($this->packer instanceof Cache_PackerI))
        throw new Cache_Exception(__CLASS__.': $settings[\'packer\'] is no valid packler instance.', 3);
    }
    $this->stack = array();
  } // function __construct()

  /**
   * Function id...
   *
   * @protected
   * @param string $id
   * @return string
   */
  protected function id( $id ) {
    return substr(md5($this->token.strtolower($id)), 0, self::ID_LENGTH);
  } // function id()

  /**
   * Function serialize...
   *
   * @param $data mixed
   * @return string
   */
  protected function serialize( $data ) {
    if (isset($this->packer))
      $this->packer->pack($data);
    else
      $data = serialize($data);
    return self::MARKER . $data;
  } // function serialize()

  /**
   * Function serialize...
   *
   * @param $data string
   * @return mixed
   */
  protected function unserialize( $data ) {
    if (strpos($data, self::MARKER) !== 0) return;
    $data = substr($data, strlen(self::MARKER));
    if (isset($this->packer))
      $this->packer->unpack($data);
    else
      $data = unserialize($data);
    return $data;
  } // function unserialize()

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * @var ressource
   */
  protected $packer;

  /**
   * @var bool
   */
  protected $serialize = TRUE;

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * @var array
   */
  private $stack;

  /**
   * Function factory...
   *
   * @param array $settings
   * @return void
   */
  private static final function load( $class ) {
    $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR
          . strtolower($class) . '.class.php';
    $class = 'Cache_'.ucwords(strtolower($class));

    if (!file_exists($file))
      throw new Cache_Exception(__CLASS__.': Missing file ['.$file.'] for class '.$class, 1);

    require_once $file;
  }

  /**
   * Increments / decrements value of the item by value.
   *
   * @param string $id
   * @param mixed $value
   * @return num New items value on success or FALSE on failure.
   */
  private function modify( $id, $value ) {
    $id = $this->id($id);
    $data = $this->get($id);
    if ($data !== NULL) {
      $data += $value;
      if ($this->set($id, $data) === TRUE) return $data;
    } else {
      return FALSE;
    }
  }

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
