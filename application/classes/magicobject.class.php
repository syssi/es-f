<?php
/**
 *
 */

/**
 * Class that implements magic methods for data retrieving
 *
 * Class can be locked to not allow to set new variables
 */
abstract class MagicObject {

  //--------------------------------------------------------------------------
  // PUBLIC
  //--------------------------------------------------------------------------

  /**
   * Class constructor
   *
   * @param array $data
   * @param bool $lock
   */
  public function __construct( $data=array(), $lock=FALSE ) {
    if (!is_array($data))
      throw new Exception(__CLASS__.': 1st parameter must be an array!');
    foreach ($data as $key=>$value) {
      $this->_magicData[$this->map($key)] = $value;
    }
    $this->_magicLocked = $lock;
  }

  /**
   * @param bool $lock Lock/unlock object
   */
  public function lock( $lock=TRUE ) {
    $this->_magicLocked = $lock;
  }

  /**
   * @param string $key Variabale name
   * @param mixed $value Variable data
   */
  public function __set( $key, $value ) {
    $_key = $this->map($key);
    if ($this->_magicLocked and !isset($this->_magicData[$_key]))
      throw new Exception(__CLASS__.': Object is locked, can\'t set new property "'.$key.'"');

    // setter
    $method = 'set'.$key;

    $this->_magicData[$_key] = method_exists($this, $method)
                             ? $this->$method($value)
                             : $value;
  }

  /**
   * @param string $key Variable to retrieve
   * @return mixed
   */
  public function __get( $key ) {
    $_key = $this->map($key);

    // getter
    $method = 'get'.$key;

    return isset($this->_magicData[$_key])
         ? ( method_exists($this, $method)
           ? $this->$method($this->_magicData[$_key])
           : $this->_magicData[$_key]
           )
         : NULL;
  }

  /**
   * From PHP 5.1.0
   *
   * @param string $key Variable to test
   */
  public function __isset( $key ) {
    return isset($this->_magicData[$this->map($key)]);
  }

  /**
   * From PHP 5.1.0
   *
   * @param string $key Variable to unset
   */
  public function __unset( $key ) {
    unset($this->_magicData[$this->map($key)]);
  }

  /**
   * Return internal data
   */
  public function __sleep() {
    return array('_magicData', '_magicLocked');
  }

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   *
   */
  protected $_magicData = array();

  /**
   * If the object is locked, NO new variables can be set and throw an exception
   */
  protected $_magicLocked = FALSE;

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   * @param string $key Variable to map
   */
  private function map( $key ) {
    return strtolower($key);
  }

}