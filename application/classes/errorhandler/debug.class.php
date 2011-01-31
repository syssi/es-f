<?php
/**
 * Error handler adds messages
 *
 * @ingroup    ErrorHandler
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-42-g440d05f - Sun Jan 9 21:40:58 2011 +0100 $
 */
class ErrorHandler_Debug implements ErrorHandlerI {

  /**
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

    $err = '<div style="padding: 5px; color: black; background-color: #FF713F">'
         . implode('<br>', $trace)
         . '</div>';
    Messages::Error($err, TRUE);
  }

}