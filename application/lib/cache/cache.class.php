<?php
/** @defgroup Cache Caching classes

*/

/**
 * Abstract class Cache
 *
 * The following settings are supported:
 * - @c token : Used to build unique cache ids (general)
 * - @c packer : Instance of Cache_PackerI (general)
 *
 * @ingroup    Cache
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2010-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.2.0
 * @version    $Id: v2.4.1-77-gc4bf735 2011-02-13 21:51:53 +0100 $
 *
 * @changelog
 * - v1.1.0
 *   - Add test to find supported caches
 * - v1.2.0
 *   - Move validation check against timestamps into here
 *
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
   * @name Abstract functions
   * @{
   */

  /**
   * Cache availability
   *
   * Returns TRUE by default, reimplement if required
   *
   * @return bool
   */
  abstract public function isAvailable();

  /**
   * Store data in cache
   *
   * @param string $id Unique cache Id
   * @param mixed $data
   * @param int $ttl Time to live or timestamp
   *                 - = 0 - expire never
   *                 - > 0 - Time to live
   *                 - < 0 - Timestamp of expiration
   * @return bool
   */
  abstract public function set( $id, $data, $ttl=0 );

  /**
   * Retrieve data from cache
   *
   * @param string $id Unique cache Id
   * @param int $expire Time to live or timestamp
   *                    - = 0 - expire never
   *                    - > 0 - Time to live
   *                    - < 0 - Timestamp of expiration
   * @return mixed
   */
  abstract public function get( $id, $expire=0 );

  /**
   * Delete data from cache
   *
   * @param string $id Unique cache Id
   * @return bool
   */
  abstract public function delete( $id );

  /**
   * Clear cache
   *
   * @return bool
   */
  abstract public function flush();
  /** @} */

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Some infos about the cache
   *
   * @return array
   */
  public function info() {
    return array('class' => get_class($this));
  }

  /**
   * Create/find a cache instance
   *
   * The following settings are supported:
   * - @c token : Used to build unique cache ids (general)
   * - @c packer : Instance of Cache_PackerI (general)
   *
   * @param array $settings
   * @param string $class Force cache class to create
   * @return Cache
   */
  public static final function create( $settings=array(), $class='' ) {
    $caches = empty($class) ? self::$Caches : array($class);
    foreach ($caches as $class) {
      $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR
            . strtolower($class) . '.class.php';
      $class = 'Cache_'.ucwords(strtolower($class));

      if (!file_exists($file))
        throw new CacheException(__CLASS__.': Missing file ['.$file.'] for class '.$class, 1);

      require_once $file;

      $cache = new $class($settings);
      if ($cache instanceof Cache AND $cache->isAvailable()) {
        return $cache;
      }
    }
  } // function create()

  /**
   * Get data from cache, if not yet exists, save to cache
   *
   * Nested calls of save() will be handled correctly.
   *
   * @par Scenarios:
   * - Data not cached yet @b or not more valid
   *   - On 1st call: Return TRUE and go 1 times through the loop to build
   *     the data
   *   - On 2nd call: Store the data to the cache and return FALSE
   * - Data cached @b and valid
   *   - On 1st call: Retrieve the data from cache and return FALSE
   *
   * @usage
   * @code
   * $cache = Cache::create('...');
   * while ($cache->save($id, $data[, $ttl])) {
   *   ...
   *   $data = ...;
   * }
   * echo $data;
   * @endcode
   *
   * @throws CacheException
   * @param string $id Unique cache Id
   * @param mixed &$data Data to store / retrieve
   * @param int $ttl Time to live, if set to 0, expire never
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
      throw new CacheException(__CLASS__.': Stack problem - '.end($this->stack).' not properly finished!', 99);
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
   *
   * inc() does not create an item if it doesn't already exist.
   *
   * @param string $id Unique cache Id
   * @param numeric $step
   * @return numeric|bool New items value on success or FALSE on failure.
   */
  public function inc( $id, $step=1 ) {
    return $this->modify($id, $step);
  } // function inc()

  /**
   * Decrements value of the item by value.
   *
   * If item specified by key was not numeric and cannot be converted to a
   * number, it will change its value to value.
   *
   * dec() does not create an item if it doesn't already exist.
   *
   * Similarly to inc(), current value of the item is being converted to
   * numerical and after that value is substracted.
   *
   * @param string $id Unique cache Id
   * @param numeric $step
   * @return numeric|bool New items value on success or FALSE on failure.
   */
  public function dec( $id, $step=1 ) {
    return $this->modify($id, -$step);
  } // function dec()

  /**
   * Magic method to set cache data
   *
   * Use implicit $ttl == NULL
   *
   * @usage
   * @code
   * $cache = Cache::create('...');
   * // Set data
   * $cache->Key = '...';
   * // Retrieve data
   * $data = $cache->Key;
   * @endcode
   *
   * @param string $name
   * @param mixed $value
   * @return mixed
   */
  public final function __set( $name, $value ) {
    $this->set($name, $value);
  }

  /**
   * Magic method to get cached data
   *
   * Use implicit $expire == NULL
   *
   * @usage
   * @code
   * $cache = Cache::create('...');
   * // Set data
   * $cache->Key = '...';
   * // Retrieve data
   * $data = $cache->Key;
   * @endcode
   *
   * @param string $name
   * @return mixed
   */
  public final function __get( $name ) {
    return $this->get($name);
  }

  /**
   * Magic method to check existence and validity of cached data
   *
   * @usage
   * @code
   * $cache = Cache::create('...');
   * if (!isset($cache->Key)) {
   *   $cache->Key = '...';
   * }
   * $data = $cache->Key;
   * @endcode
   *
   * @param string $name
   * @return mixed
   */
  public function __isset( $name ) {
    return ($this->get($name) !== NULL);
  }

  /**
   * Magic method to unset cached data
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
   * Available caching methods
   *
   * @todo Test 'EAccelerator', 'XCache', 'MemCache'
   * @var array $Caches
   */
  protected static $Caches = array(
    // Tested methods
    'APC',
    # not fully tested yet...
    # 'EAccelerator', 'XCache', 'MemCache',
    // Only avail. with a writeable directory
    'File', 'Files',
    // Only avail. if compiled in
    'Session',
    // Always avail.
    'Mock'
  );

  /**
   * Unique cache token
   *
   * @var string $token
   */
  protected $token;

  /**
   * Master timestamp
   *
   * @var int $ts
   */
  protected $ts;

  /**
   * Class constructor
   *
   * The following settings are supported:
   * - @c token : Used to build unique cache ids (general)
   * - @c packer : Instance of Cache_PackerI (general)
   *
   * @throws CacheException
   * @param array $settings
   * @return void
   */
  protected function __construct( $settings=array() ) {
    $this->ts = time();
    $this->token = !empty($settings['token']) ? $settings['token'] : md5(__FILE__);
    if (isset($settings['packer'])) {
      $this->packer = $settings['packer'];
      if (!is_object($this->packer) OR !($this->packer instanceof Cache_PackerI))
        throw new CacheException(__CLASS__.': $settings[\'packer\'] is no valid packler instance.', 3);
    }
    $this->stack = array();
  } // function __construct()

  /**
   * Check data validity according to the timestamps
   *
   * @see set()
   * @see get()
   * @param int $ts Timestamp when data was last saved
   * @param int $ttl Time to live of data to check against
   * @param int $expire Time to live or timestamp
   *                    - = 0 - expire never
   *                    - > 0 - Time to live
   *                    - < 0 - Timestamp of expiration
   * @return bool
   */
  protected function valid( $ts, $ttl, $expire ) {
    if (isset($expire)) {
      // expiration timestamp set
      if ($expire === 0 OR
          $expire > 0 AND $this->ts+$expire >= $ts+$ttl OR
          $expire < 0 AND $ts >= -$expire) return TRUE;
    } else {
      // expiration timestamp NOT set
      if ($ttl === 0 OR
          $ttl > 0 AND $ts+$ttl >= $this->ts OR
          $ttl < 0 AND -$ttl >= $this->ts) return TRUE;
    }
    return FALSE;
  } // function valid()

  /**
   * Build internal Id from external Id and the cache token
   *
   * @param string $id Unique cache Id
   * @return string
   */
  protected function id( $id ) {
    return substr(md5($this->token.strtolower($id)), 0, self::ID_LENGTH);
  } // function id()

  /**
   * Serialize data, using potentially defined packer
   *
   * @param mixed $data
   * @return string
   */
  protected function serialize( $data ) {
    if (isset($this->packer))
      $this->packer->pack($data);
    else
      $data = serialize($data);
    // Mark cached data
    return self::MARKER . $data;
  } // function serialize()

  /**
   * Unserialize data, using potentially defined packer
   *
   * @param string $data
   * @return mixed
   */
  protected function unserialize( $data ) {
    // Cached data correctly marked?
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
   * Instance of Cache_PackerI to pack data before storing into cache
   *
   * Set it during {@link create() creation} of class by setting parameter
   * 'packer'.
   *
   * @var Cache_PackerI $packer
   */
  protected $packer;

  /**
   * Serialize data
   *
   * @var bool $serialize
   */
  protected $serialize = TRUE;

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Stack of save() calls
   *
   * @var array $stack
   */
  private $stack;

  /**
   * Increments / decrements value of the item by value.
   *
   * @param string $id Unique cache Id
   * @param int $step
   * @return num New items value on success or FALSE on failure.
   */
  private function modify( $id, $step ) {
    $id = $this->id($id);
    $data = $this->get($id);
    if ($data !== NULL) {
      $data += $step;
      if ($this->set($id, $data) === TRUE) return $data;
    } else {
      return FALSE;
    }
  }

}

/**
 * Class CacheException
 *
 * @ingroup Cache
 */
class CacheException extends Exception {}
