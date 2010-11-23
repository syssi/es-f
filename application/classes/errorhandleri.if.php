<?php
/**
 *
 */
interface ErrorHandlerI {

  /**
   * Have to be implemented
   */
  public static function HandleError( $errno, $errstr, $errfile, $errline );

}