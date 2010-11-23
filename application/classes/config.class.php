<?php
/**
 *
 */

/**
 *
 */
abstract class Config {

  /**
   *
   */
  public function __construct( $Config=array() ) {
    if (!is_array($Config))
      throw new Exception(__CLASS__.': Constructor parameter $Config must be an array!');
    $this->Config = array_merge($this->Config, $Config);
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Overwrite this for default configuration in real classes
   *
   * @var array
   */
  protected $Config = array();

  /**
   *
   */
  protected function get( $key ) {
    return isset($this->Config[$key]) ? $this->Config[$key] : NULL;
  }

}
