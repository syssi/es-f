<?php

require_once dirname(__FILE__).'/../packeri.if.php';

/**
 *
 */
class Cache_Packer_GZ implements Cache_PackerI {

  /**
   *
   */
  public function __construct( $level=5 ) {
    $this->active = extension_loaded('zlib');
    $this->level = in_array($level, range(1,9)) ? $level : 5;
  }

  /**
   * Pack function
   *
   * @param &$data mixed
   * @return string
   */
  public function pack( &$data ) {
    if ($this->active) $data = base64_encode(gzcompress(serialize($data), $this->level));
  }

  /**
   * Unpack function
   *
   * @param &$data string
   * @return mixed
   */
  public function unpack( &$data ) {
    if ($this->active) $data = unserialize(gzuncompress(base64_decode($data)));
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   *
   */
  protected $active;

  /**
   *
   */
  protected $level;

}
