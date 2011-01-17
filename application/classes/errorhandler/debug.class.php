<?php
/**
 * Error handler adds messages
 *
 * @ingroup    Messages
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-42-g440d05f - Sun Jan 9 21:40:58 2011 +0100 $
 */
class ErrorHandler_Debug extends ErrorHandler {

  /**
   *
   */
  public static function HandleError( $errno, $errstr, $errfile, $errline ) {
    $errfile = str_replace($_SERVER['DOCUMENT_ROOT'], '', $errfile);
    if ($err = self::analyseError($errno, $errstr, $errfile, $errline)) {
      $err = '<div style="padding:5px;background-color:#CC3F10;color:black">'.$err.'</div>';
      Messages::Error($err, TRUE);
    }
  }

}