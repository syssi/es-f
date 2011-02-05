<?php
/**
 * Cache class using MemCache server
 *
 * The following settings are supported:
 * - @c token : used to build unique cache ids (optional)
 * - @c host  : @c @<host>[:@<port>] (optional) default: @c localhost:11211
 *
 * If MemCache is not installed, gMemCache is used,
 * a purely implementation of a MemCache client in PHP.
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
   * @name Implemented abstract functions
   * @{
   */
  public static function available() {
    return extension_loaded('memcache');
  }

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

  public function get( $id, $expire=0 ) {
    if (!$cached = $this->unserialize($this->memcache->get($this->id($id)))) return;
    // split into store time, ttl, data
    list($ts, $ttl, $data) = $cached;
    // Data valid?
    if ($this->valid($ts, $ttl, $expire)) return $data;
    // else drop data for this key
    $this->delete($id);
  }

  public function delete( $id ) {
    $this->memcache->delete($this->id($id));
  }

  public function flush() {
    $this->memcache->flush();
  }
  /** @} */

  /**
   * @name Overloaded functions
   * Use MemCache own functions
   * @{
   */
  public function inc( $id, $step=1 ) {
    return $this->memcache->increment($this->id($id), $step);
  } // function inc()

  public function dec( $id, $step=1 ) {
    return $this->memcache->decrement($this->id($id), $step);
  } // function dec()
  /** @} */

  /**
   * Close connection
   */
	public function __destruct(){
		$this->memcache->close();
	}

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * The following additional settings are supported:
   * - @c host : MemCache host:port (optional)
   *
   * @copydoc Cache::__construct()
   */
  protected function __construct( $settings=array() ) {
    if (!self::available()) {
      $this->memcache = new MemCache;
    } else {
      // use gMemCache
      require_once dirname(__FILE__).'/gMemCache.php';
      $this->memcache = new gMemCache;
    }
    parent::__construct($settings);

    @list($host, $port) = explode(':', isset($settings['host']) ? $settings['host'] : self::HOST);
    if (!isset($port)) $port = self::PORT;

    if (!$this->memcache->connect($host, $port))
      throw new CacheException(__CLASS__.'Can\'t connect to MemCache host '.$host.':'.$port, 4);
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
