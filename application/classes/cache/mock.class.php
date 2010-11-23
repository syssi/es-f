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
   * @param string $key
   * @param mixed $data
   * @return void
   */
  public function set( $key, $data ) {}

  /**
   * Function get...
   *
   * @param string $key
   * @param int $timestamp
   * @return mixed
   */
  public function get( $key, $timestamp=0 ) {}

  /**
   * Function remove...
   *
   * @param string $key
   * @return void
   */
  public function remove( $key ) {}

  /**
   * Function clear...
   *
   * @return void
   */
  public function clear() {}

  /**
   * Class desctructor
   *
   * @return void
   */
  public function __destruct() {}

}