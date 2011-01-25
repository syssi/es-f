<?php
/**
 * Cache class using MemCache server
 *
 * The following settings are supported:
 * - @c token : used to build unique cache ids (optional)
 * - @c host  : @c @<host>[:@<port>] (optional) default: @c localhost:11211
 *
 * @ingroup    Cache
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2010-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class Cache_MemCache extends Cache {

  /**
   * @name Default server parameters
   * @{
   */
  const HOST = 'localhost';
  const PORT = 11211;
  /** @} */

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Class constructor
   *
   * @throws CacheException
   * @param array $settings
   */
  public function __construct( $settings=array() ) {
    if (!self::available()) {
      $this->memcache = new MemCache;
    } else {
      // use gMemCache
      require_once dirname(__FILE__).'/gMemcache.php';
      $this->memcache = new gMemCache;
    }
    parent::__construct($settings);

    @list($host, $port) = explode(':', isset($settings['host']) ? $settings['host'] : self::HOST);
    if (!isset($port)) $port = self::PORT;

    if (!$this->memcache->connect($host, $port))
      throw new CacheException(__CLASS__.'Can\'t connect to MemCache host '.$host.':'.$port, 4);
  }

  /**
   *
   */
  public static function available() {
    return extension_loaded('memcache');
  }

  /**
   *
   */
	public function __destruct(){
		$this->memcache->close();
	}

  /**
   * Function set...
   *
   * @param string $id
   * @param mixed $data
   * @param $ttl int Time to live or timestamp
   *                 - @c 0 - expire never
   *                 - @c >0 - Time to live
   *                 - @c <0 - Timestamp of expiration
   * @return void
   */
  public function set( $id, $data, $ttl=0 ) {
    // optimized for probability Set -> Delete -> Clear
    if ($data !== NULL) {
      // compress data if no own packer is used
      return $this->memcache->set($this->id($id), $this->serialize(array($this->ts, $ttl, $data)), !isset($this->packer));
    } elseif ($id !== NULL) { // AND $data === NULL
      return $this->delete($id);
    } else { // $id === NULL AND $data === NULL
      return $this->clear();
    }
  }

  /**
   * Function get...
   *
   * @param string $id
   * @param $expire int Time to live or timestamp
   *                    0  - expire never
   *                    >0 - Time to live
   *                    <0 - Timestamp of expiration
   * @return mixed
   */
  public function get( $id, $expire=0 ) {
    if (!$cached = $this->unserialize($this->memcache->get($this->id($id)))) return;
    // split into store time, ttl, data
    list($ts, $ttl, $data) = $cached;
    // Data valid?
    if (isset($expire)) {
      // expiration timestamp set
      if ($expire === 0 OR
          $expire > 0 AND $this->ts+$expire >= $ts+$ttl OR
          $expire < 0 AND $ts >= -$expire) return $data;
    } else {
      // expiration timestamp NOT set
      if ($ttl === 0 OR
          $ttl > 0 AND $ts+$ttl >= $this->ts OR
          $ttl < 0 AND -$ttl >= $this->ts) return $data;
    }
    // else drop data for this key
    $this->delete($id);
  }

  /**
   * Function delete...
   *
   * @param string $id
   * @return void
   */
  public function delete( $id ) {
    $this->memcache->delete($this->id($id));
  }

  /**
   * Function flush...
   *
   * @return void
   */
  public function flush() {
    $this->memcache->flush();
  }

  /**
   * Increments value of an item by the specified value.
   *
   * @param string $id
   * @param mixed $value
   * @return num New items value on success or FALSE on failure.
   */
  public function inc( $id, $value=1 ) {
    return $this->memcache->increment($this->id($id), $value);
  }

  /**
   * Decrements value of the item by value.
   *
   * @param string $id
   * @param mixed $value
   * @return num New items value on success or FALSE on failure.
   */
  public function dec( $id, $value=1 ) {
    return $this->memcache->decrement($this->id($id), $value);
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * MemCache instance
   *
   * @var MemCache $memcache
   */
  private $memcache;

}
