<?php
/**
 *
 */

/**
 *
 */
class ErrorHandler_Echo extends ErrorHandler {

/**
 *
 */
  public static function HandleError( $errno, $errstr, $errfile, $errline ) {
    $errfile = str_replace(@$_SERVER['DOCUMENT_ROOT'], '', $errfile);
    self::$HTML = FALSE;
    echo self::analyseError($errno, $errstr, $errfile, $errline);
  }

}