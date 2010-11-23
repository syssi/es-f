<?php
/**
 *
 */

/**
 *
 */
class ErrorHandler_Default extends ErrorHandler {

  /**
   * Handle error and write/append to file
   */
  public static function HandleError( $errno, $errstr, $errfile, $errline ) {
    $errfile = str_replace($_SERVER['DOCUMENT_ROOT'], '', $errfile);
    $log = 'error.'.date('Y-m-d').'.log';
    if ($err = self::analyseError($errno, $errstr, $errfile, $errline) AND
        $fh = @fopen($log, 'a')) {
      fwrite($fh, date('r'));
      if (esf_User::getActual()) fwrite($fh,', User: '.$user);
      fwrite($fh, "\n");
      fwrite($fh, trim(strip_tags($err)) . "\n");
      fwrite($fh, str_repeat('-',80) . "\n");
      fclose($fh);

      if (Registry::get('Module.LogFiles.Enabled'))
        $log = sprintf('<a href="index.php?module=logfiles">%s</a>', $log);

      Messages::addError('An error occurred, please take a look into '.$log, TRUE);
    }
  }

}