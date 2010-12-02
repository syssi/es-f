<?php
/**
 *
 */
class Cache_Packer_GZ implements Cache_PackerI {

  /**
   *
   */
  public function __construct( $level=5 ) {
    $this->active = extension_loaded('zlib');
    $this->level = in_array($level, range(0,9)) ? $level : 5;
  }

  /**
   * Pack function
   *
   * @param &$data mixed
   * @return string
   */
  public function pack( &$data ) {
    $data = serialize($data);
#    if ($this->active) $data = gzdeflate($data, $this->level);
    $data = base64_encode($data);
  }

  /**
   * Unpack function
   *
   * @param &$data string
   * @return mixed
   */
  public function unpack( &$data ) {
    $data = base64_decode($data);
#    if ($this->active) $data = gzinflate($data, $this->level);
    $data = unserialize($data);
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
