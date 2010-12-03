<?php
/**
 * Class Cache
 *
 * The following settings are supported:
 * - token    : used to build unique cache ids (optional)
 *
 * @package  Cache
 * @version  1.0.0
 * @version
 */
class Cache_Session extends Cache {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Function set...
   *
   * @param string $id
   * @param mixed $data
   * @param int $expire Timestamp for expiration, if set to 0, expire never
   * @return mixed
   */
  public function set( $id, $data, $expire=0 ) {
    // optimized for probability Set -> Delete -> Clear
    if ($data !== NULL) {
      $_SESSION[$this->token][$this->id($id)] = $this->serialize(array($expire, $data));
      return TRUE;
    } elseif ($id !== NULL) { // AND $data === NULL
      return $this->delete($id);
    } else { // $id === NULL AND $data === NULL
      return $this->clear();
    }
  } // function set()

  /**
   * Function get...
   *
   * @param string $id
   * @return mixed
   */
  public function get( $id ) {
    $id = $this->id($id);
    if (!isset($_SESSION[$this->token][$id])) return;

    $data = $this->unserialize($_SESSION[$this->token][$id]);

    if ($data[0] === 0 OR // expire never
        $data[0] >= $this->ts) return $data[1];

    // else drop data for this key
    $this->delete($id);
  } // function get()

  /**
   * Function delete...
   *
   * @param string $id
   * @return void
   */
  public function delete( $id ) {
    $id = $this->id($id);
    if (isset($_SESSION[$this->token][$id])) unset($_SESSION[$this->token][$id]);
  } // function remove()

  /**
   * Function flush...
   *
   * @return void
   */
  public function flush() {
    unset($_SESSION[$this->token]);
  } // function clear()

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Class constructor
   *
   * @protected
   * @param array $settings
   * @return void
   */
  protected function __construct( $settings=array() ) {
    parent::__construct($settings);
    session_start();
  } // function __construct()

}