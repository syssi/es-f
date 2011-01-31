<?php
/**
 * Echo errors (debug only!)
 *
 * @ingroup    ErrorHandler
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 * @version    $Id$
 */
class ErrorHandler_Echo implements ErrorHandlerI {

  /**
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

    echo '<div style="padding: 5px; color: black; background-color: #FF713F">',
         implode('<br>', $trace),
         '</div>';
  }

}