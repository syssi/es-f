<?php
/**
 * Cache class using MemCache server
 *
 * The following settings are supported:
 * - token    : used to build unique cache ids (optional)
 * - host     : <host>[:<port>] (optional)
 *              default: localhost:11211
 *
 * @ingroup  Cache
 * @version  1.0.0
 */
class Cache_MemCache extends Cache {

  const HOST = 'localhost';
  const PORT = 11211;

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   *
   */
  public function __construct( $settings=array() ) {
    parent::__construct($settings);
    if (extension_loaded('memcache')) {
      $this->memcache = new MemCache;
    } else {
      // use gMemCache
      require_once dirname(__FILE__).'/gMemcache.php';
      $this->memcache = new gMemCache;
    }

    @list($host, $port) = explode(':', isset($settings['host']) ? $settings['host'] : self::HOST);
    if (!isset($port)) $port = self::PORT;

    if (!$this->memcache->connect($host, $port))
      throw new Cache_Exception(__CLASS__.'Can\'t connect to MemCache host '.$host.':'.$port, 4);
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
   * @param int $expire Timestamp for expiration, if set to 0, expire never
   * @return void
   */
  public function set( $id, $data, $expire=0 ) {
    // optimized for probability Set -> Delete -> Clear
    if ($data !== NULL) {
      // calculate time to live
      if ($expire) $expire -= $this->ts;
      // compress data if no own packer is used
      return $this->memcache->set($this->id($id), $this->serialize($data), !isset($this->packer), $expire);
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
   * @return mixed
   */
  public function get( $id ) {
    return $this->unserialize($this->memcache->get($this->id($id)));
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
   * @var ressource
   */
  private $memcache;

}