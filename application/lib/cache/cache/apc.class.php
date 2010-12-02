<?php
/**
 * Cache class using APC opcode cache
 *
 * The following settings are supported:
 * - token    : used to build unique cache ids (optional)
 *
 * @ingroup  Cache
 * @version  1.0.0
 */
class Cache_APC extends Cache {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   *
   */
  public function __construct( $settings=array() ) {
    if (!extension_loaded('apc'))
      throw new Cache_Exception(__CLASS__.': Extension APC not loaded.', 9);
    parent::__construct($settings);
  }

  /**
   * Function set...
   *
   * @param string $id
   * @param mixed $data
   * @param int $expire Timestamp for expiration, if set to 0, expire never
   * @return bool
   */
  public function set( $id, $data, $expire=0 ) {
    // optimized for probability Set -> Delete -> Clear
    if ($data !== NULL) {
      // calculate time to live
      if ($expire) $expire -= $this->ts;
      return apc_store($this->id($id), $this->serialize($data), $expire);
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
    return $this->unserialize(apc_fetch($this->id($id)));
  }

  /**
   * Function delete...
   *
   * @param string $id
   * @return bool
   */
  public function delete( $id ) {
    return apc_delete($this->id($id));
  }

  /**
   * Function flush...
   *
   * @return bool
   */
  public function flush() {
    return apc_clear_cache();
  }

}
