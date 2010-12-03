<?php
/**
 * Cache class using EAccelerator opcode cache
 *
 * The following settings are supported:
 * - token    : used to build unique cache ids (optional)
 *
 * @ingroup  Cache
 * @version  1.0.0
 */
class Cache_EAccelerator extends Cache {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   *
   */
  public function __construct( $settings=array() ) {
    if (!extension_loaded('eaccelerator') OR !function_exists('eaccelerator_put'))
      throw new Cache_Exception(__CLASS__.': Extension EAccelerator not loaded.', 9);
    parent::__construct($settings);
    eaccelerator_caching(TRUE);
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
      $this->delete($id);
      $id = $this->id($id);
      // calculate time to live
      if ($expire) $expire -= $this->ts;
      return (eaccelerator_lock($id) AND
              eaccelerator_put($id, $this->serialize($data), $expire) AND
              eaccelerator_unlock($id));
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
    $id = $this->id($id);
    if (eaccelerator_lock($id) AND
        $data = eaccelerator_get($id) AND
        eaccelerator_unlock($id)) return $this->unserialize($data);
  }

  /**
   * Function delete...
   *
   * @param string $id
   * @return void
   */
  public function delete( $id ) {
		$id = $this->id($id);
		return (eaccelerator_lock($id) AND
            eaccelerator_rm($id) AND
            eaccelerator_unlock($id));
  }

  /**
   * Function flush...
   *
   * @return void
   */
  public function flush() {
		return (eaccelerator_clean() AND eaccelerator_clear());
  }

}
