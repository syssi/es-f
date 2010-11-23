<?php
/**
 * Handle application messages
 *
 * @package    Messages
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 * @since      File available since Release 2.0.0
 */

abstract class Messages {

  /**#@+
   * Message type
   */
  const INFO    = 'info';
  const SUCCESS = 'success';
  const ERROR   = 'error';
  const CODE    = 'code';
  /**#@-*/

  /**
   * Variable name to store messages into session
   *
   * @access public
   */
  public static $SessionVar = '__MESSAGES__';

  /**
   * html code to format messages for output
   *
   * string param 1: message type: info|error|success
   * string param 2: message text
   *
   * @access public
   */
  public static $OutHTML = '<div class="msg%1$s">%2$s</div>';

  /**
   * Format message string
   *
   * @access public
   * @param mixed $msg Message(s)
   * @param string $type Message type
   * @param boolean $formated Message is still HTML formated
   * @return string
   */
  public static function toStr( $msg, $type=MSG_INFO, $formated=FALSE ) {
    if (is_array($msg)) {
      if (!$formated)
        $msg = array_map_recursive('htmlspecialchars', $msg);
    } else {
      @list($func, $msg) = explode(':',$msg,2);
      if (!$msg) {
        $msg = $func;
      } elseif ($func == 'html') {
        $formated = TRUE;
      } else {
        $msg = $func.':'.$msg;
      }
      if (!$formated) $msg = htmlspecialchars($msg, ENT_QUOTES);
    }
    $return = '';
    foreach ((array)$msg as $m)
      $return .= sprintf(self::$OutHTML, $type, $m)."\n";

    return $return;
  }

  /**
   * Store info message into buffer
   *
   * @access public
   * @param mixed $msg Message(s)
   * @param boolean $formated Message is still HTML formated
   */
  public static function addInfo( $msg, $formated=FALSE ) {
    self::add($msg, self::INFO, $formated);
  }
  public static function Info( $msg, $formated=FALSE ) {
    self::add($msg, self::INFO, $formated);
  }

  /**
   * Store success message into buffer
   *
   * @access public
   * @param mixed $msg Message(s)
   * @param boolean $formated Message is still HTML formated
   */
  public static function addSuccess( $msg, $formated=FALSE ) {
    self::add($msg, self::SUCCESS, $formated);
  }
  public static function Success( $msg, $formated=FALSE ) {
    self::add($msg, self::SUCCESS, $formated);
  }

  /**
   * Store error message into buffer
   *
   * @access public
   * @param mixed $msg Message(s)
   * @param boolean $formated Message is still HTML formated
   */
  public static function addError( $msg, $formated=FALSE ) {
    if (!is_array($msg)) $msg = '[Error] '.$msg;
    self::add($msg, self::ERROR, $formated);
  }
  public static function Error( $msg, $formated=FALSE ) {
    if (!is_array($msg)) $msg = '[Error] '.$msg;
    self::add($msg, self::ERROR, $formated);
  }

  /**
   * Store code message into buffer
   *
   * @access public
   * @param mixed $msg Message(s)
   * @param boolean $formated Message is still HTML formated
   */
  public static function addCode( $msg, $formated=FALSE ) {
    self::add($msg, self::CODE, $formated);
  }
  public static function Code( $msg, $formated=FALSE ) {
    self::add($msg, self::CODE, $formated);
  }

  /**
   * Generic function to store message into buffer
   *
   * @access public
   * @param mixed $msg Message(s)
   * @param string $type Message type
   * @param boolean $formated Message is still HTML formated
   */
  public static function add( $msg, $type, $formated ) {
    Session::addP(self::$SessionVar, self::toStr($msg, $type, $formated));
  }

  /**
   * Get messages from buffer an clear buffer if requested
   *
   * @access public
   * @param boolean $clear Clear buffer
   * @return array
   */
  public static function get( $clear=TRUE ) {
    $msgs = Session::getP(self::$SessionVar);
    if ($clear) self::clear();
    return $msgs;
  }

  /**
   * Clear message buffer
   *
   * @access public
   */
  public static function clear() {
    Session::setP(self::$SessionVar);
  }
}
