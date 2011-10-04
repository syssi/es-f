<?php
/**
 * Shutdown functions handler class
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2008-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id$
 * @revision   $Rev$
 */
class ShutDown {

  /**
   * Singleton access function
   *
   * @return ShutDown
   */
  public static function getInstance() {
    if (is_null(self::$Instance)) self::$Instance = new ShutDown;
    return self::$Instance;
  }

  /**
   * Register a function / class method that should be executed
   * on application shutdown
   *
   * Pass from parameter 2 as many parameters as you like
   *
   * @param string/array function / class method
   * @param mixed function parameter(s)
   * @return ShutDown $this
   */
  public function register() {
    $callback = func_get_args();

    if (empty($callback)) {
      trigger_error('No callback passed to '.__FUNCTION__.' method', E_USER_ERROR);
      return FALSE;
    }

    if (!is_callable($callback[0])) {
      trigger_error('Invalid callback passed to the '.__FUNCTION__.' method', E_USER_ERROR);
      return FALSE;
    }

    $this->callbacks[] = $callback;
    return $this;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Singleton instance
   *
   * @var ShutDown $Instance
   */
  private static $Instance;

  /**
   * Registered shutdown functions
   *
   * @var array $callbacks
   */
  private $callbacks;

  /**
   * Class constructor
   *
   * @return ShutDown Instance
   */
  private function __construct() {
    $this->callbacks = array();
    register_shutdown_function(array($this, 'callRegisteredShutdown'));
  }

  /**
   * Public for register_shutdown_function() only
   *
   * @return void
   */
  public function callRegisteredShutdown() {
    foreach ($this->callbacks as $arguments) {
      $callback = array_shift($arguments);
      call_user_func_array($callback, $arguments);
    }
  }
}