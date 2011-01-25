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
class ErrorHandler_Echo extends ErrorHandler {

  /**
   * Echo errors (debug only!)
   *
   * @param int $errno
   * @param string $errstr
   * @param string $errfile
   * @param int $errline
   */
  public static function HandleError( $errno, $errstr, $errfile, $errline ) {
    $errfile = str_replace(@$_SERVER['DOCUMENT_ROOT'], '', $errfile);
    self::$HTML = FALSE;
    echo self::analyseError($errno, $errstr, $errfile, $errline);
  }

}