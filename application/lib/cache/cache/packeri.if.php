<?php

interface Cache_PackerI {

  /**
   * Pack function
   *
   * @param $data mixed
   * @return string
   */
  public function pack( &$data );

  /**
   * Unpack function
   *
   * @param $data string
   * @return mixed
   */
  public function unpack( &$data );

}
