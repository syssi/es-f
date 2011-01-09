<?php
/**
 * Writes errors to a log file
 *
 * @ingroup    Messages
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
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

      Messages::Error('An error occurred, please take a look into '.$log, TRUE);
    }
  }

}