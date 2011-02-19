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
 * @version    $Id: v2.4.1-77-gc4bf735 2011-02-13 21:51:53 +0100 $
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
  public function isAvailable() {
    return $this->memcache->connect($this->host, $this->port);
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
    parent::__construct($settings);

    @list($this->host, $this->port) = explode(':', isset($settings['host']) ? $settings['host'] : self::HOST);
    if (!isset($this->port)) $this->port = self::PORT;

    if (extension_loaded('memcache')) {
      $this->memcache = new MemCache;
    } else {
      // use gMemCache
      require_once dirname(__FILE__).'/gMemCache.php';
      $this->memcache = new gMemCache;
    }
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * MemCache instance
   *
   * @var string $host
   */
  private $host;

  /**
   * MemCache instance
   *
   * @var int $port
   */
  private $port;

  /**
   * MemCache instance
   *
   * @var MemCache $memcache
   */
  private $memcache;

}
