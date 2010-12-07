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
    if (!self::available())
      throw new Cache_Exception(__CLASS__.': Extension EAccelerator not loaded.', 9);
    parent::__construct($settings);
    eaccelerator_caching(TRUE);
  }

  /**
   *
   */
  public static function available() {
    return (extension_loaded('eaccelerator') AND function_exists('eaccelerator_put'));
  }

  /**
   * Function set...
   *
   * @param string $id
   * @param mixed $data
   * @param $ttl int Time to live or timestamp
   *                 0  - expire never
   *                 >0 - Time to live
   *                 <0 - Timestamp of expiration
   * @return void
   */
  public function set( $id, $data, $ttl=0 ) {
    // optimized for probability Set -> Delete -> Clear
    if ($data !== NULL) {
      $this->delete($id);
      $id = $this->id($id);
      // calculate time to live
      return (eaccelerator_lock($id) AND
              eaccelerator_put($id, $this->serialize(array($this->ts, $ttl, $data))) AND
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
   * @param $expire int Time to live or timestamp
   *                    0  - expire never
   *                    >0 - Time to live
   *                    <0 - Timestamp of expiration
   * @return mixed
   */
  public function get( $id, $expire=0 ) {
    $id = $this->id($id);
    if (!eaccelerator_lock($id) OR
        !$cached = eaccelerator_get($id) OR
        !eaccelerator_unlock($id) OR
        !$cached = $this->unserialize($cached)) return;

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
