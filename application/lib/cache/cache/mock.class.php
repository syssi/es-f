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
   * @param int $expire Timestamp for expiration, if set to 0, expire never
   * @return void
   */
  public function set( $id, $data, $expire=0 ) {}

  /**
   * Function get...
   *
   * @param string $id
   * @return mixed
   */
  public function get( $id ) {}

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