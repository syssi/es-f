<?php
/**
 *
 */

/**
 * Class that implements a locked magic object
 */
abstract class LockedObject extends MagicObject {

  //--------------------------------------------------------------------------
  // PUBLIC
  //--------------------------------------------------------------------------

  /**
   *
   */
  public function __construct( $data=array() ) {
    parent::__construct($data, TRUE);
  }

}