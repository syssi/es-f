<?php
/**
 * Error handler interface
 *
 * @ingroup    ErrorHandler
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 * @version    $Id$
 */
interface ErrorHandlerI {

  /**
   * Handles the errors
   *
   * @param int $errno
   * @param string $errstr
   * @param string $errfile
   * @param int $errline
   */
  public static function HandleError( $errno, $errstr, $errfile, $errline );

}