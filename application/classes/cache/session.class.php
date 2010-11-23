<?php
/**
 * Class Cache
 *
 * description ...
 *
 * @package    Cache
 * @version  1.0.0
 * @version
 */
class Cache_Session extends Cache {

  const CACHE_ID = '__CACHE';

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Function set...
   *
   * @param string $key
   * @param mixed $data
   * @return mixed
   */
  public function set( $key, $data ) {
    $_SESSION[CACHE_ID][$this->map($key)] = array(time(), $data);
  } // function set()

  /**
   * Function get...
   *
   * @param string $key
   * @param int $timestamp
   * @return mixed
   */
  public function get( $key, $timestamp=0 ) {
    $key = $this->map($key);
    if (!isset($_SESSION[CACHE_ID][$key])) return;

    if ($timestamp <= $_SESSION[CACHE_ID][$key][0])
      return $_SESSION[CACHE_ID][$key][1];

    // else drop data for this key
    $this->remove($key);
  } // function get()

  /**
   * Function remove...
   *
   * @param string $key
   * @return void
   */
  public function remove( $key ) {
    $key = $this->map($key);
    if (isset($_SESSION[CACHE_ID][$key])) unset($_SESSION[CACHE_ID][$key]);
  } // function remove()

  /**
   * Function clear...
   *
   * @return void
   */
  public function clear() {
    unset($_SESSION[CACHE_ID]);
  } // function clear()

  /**
   * Function set...
   *
   * @param string $key
   * @param mixed $data
   * @return mixed
   */
  public function __destruct() {}

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Class constructor
   *
   * @protected
   * @param array $Settings
   * @return void
   */
  protected function __construct( $Settings=array() ) {
    session_start();
  } // function __construct()


}
