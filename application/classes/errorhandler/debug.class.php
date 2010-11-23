<?php
/**
 *
 */

/**
 *
 */
class ErrorHandler_Debug extends ErrorHandler {

  /**
   *
   */
  public static function HandleError( $errno, $errstr, $errfile, $errline ) {
    $errfile = str_replace($_SERVER['DOCUMENT_ROOT'], '', $errfile);
    if ($err = self::analyseError($errno, $errstr, $errfile, $errline)) {
      $err = '<div style="padding:5px;background-color:#CC3F10;color:black">'.$err.'</div>';
      Messages::addError($err, TRUE);
    }
  }

}