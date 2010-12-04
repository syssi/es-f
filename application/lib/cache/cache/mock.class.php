<?php
/**
 * Mockup class with no functionality
 *
 * @ingroup  Cache
 * @version  1.0.0
 */
class Cache_Mock extends Cache {

  // -------------------------------------------------------------------------
  // PUBLIC
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
   * @return void
   */
  public function set( $id, $data, $ttl=0 ) {}

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
  public function get( $id, $expire=0 ) {}

  /**
   * Function delete...
   *
   * @param string $id
   * @return void
   */
  public function delete( $id ) {}

  /**
   * Function flush...
   *
   * @return void
   */
  public function flush() {}

}
