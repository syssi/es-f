<?php
/**
 * Writes errors to a log file
 *
 * @ingroup    ErrorHandler
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 * @version    $Id: v2.4.1-42-g440d05f - Sun Jan 9 21:40:58 2011 +0100 $
 */
class ErrorHandler_File implements ErrorHandlerI {

  /**
   * Class constructor
   *
   * @param bool   $file   File mask to store into (required),
   *                       use placeholder {TS} for time stamp (optional),
   *                       e.g. /path/to/error.{TS}.log
   * @param string $format date() format for time stamp
   */
  public function __construct( $file, $format='Y-m-d' ) {
    $this->file = str_replace('{TS}', date($format), $file);
  }

  /**
   * Handle error and write/append to file
   *
   * @param int    $errno      Contains the level of the error raised
   * @param string $errstr     Contains the error message
   * @param string $errfile    Contains the filename that the error was raised in
   * @param int    $errline    Contains the line number the error was raised at
   * @param int    $errcontext An array that points to the active symbol table at
   *                           the point the error occurred. So will contain an
   *                           array of every variable that existed in the scope
   *                           the error was triggered in.
   *                           User error handler must not modify error context.
   * @param array  $trace      $trace[0] holds the error, the rest is the backtrace to this
   */
  public function HandleError( $errno, $errstr, $errfile, $errline, $errcontext, $trace ) {
    if (empty($trace)) return;

    if ($fh = @fopen($this->file, 'a')) {
      fwrite($fh, date('r'));
      if ($user = esf_User::getActual()) fwrite($fh,', User: '.$user);
      fwrite($fh, "\n");
      fwrite($fh, implode("\n", $trace) . "\n");
      fwrite($fh, str_repeat('-',80) . "\n");
      fclose($fh);
      Messages::Error('An error occurred, please take a look into '.$this->file, TRUE);
    }
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * Log file
   *
   * @var string $file
   */
  protected $file;

}