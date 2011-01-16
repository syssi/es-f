<?php
/**
 *
 * @package    Core
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */

/**
 * Generic abstrct extension class for modules/plugins
 */
abstract class esf_Extension implements EventHandlerI {

  /**
   * Class constructor
   *
   * @return void
   */
  public function __construct() {
    list($this->ExtensionName, $this->ExtensionScope,) =
      array_reverse(explode('_', strtolower(get_class($this))));

    if (!$this->isPost())
      $this->Request =& $_GET;
    else
      $this->Request =& $_POST;

    $this->Core['localpath'] = LOCALDIR.'/'.$this->ExtensionScope.'/'.$this->ExtensionName;

    if (!is_dir($this->Core['localpath'])) {
      $res = Exec::getInstance()->MkDir($this->Core['localpath'], $err);
      if ($res) Messages::Error($err);
    }
  }

  /**
   *
   * @param $name string
   * qparam $value mixed
   */
  public function __set( $name, $value ) {
    Registry::set($this->ExtensionScope.'.'.$this->ExtensionName.'.'.$name, $value);
  }

  /**
   *
   * @param $name string
   * @return mixed
   */
  public function __get( $name ) {
    return Registry::get($this->ExtensionScope.'.'.$this->ExtensionName.'.'.$name);
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * @var string
   */
  protected $ExtensionScope;

  /**
   * @var string
   */
  protected $ExtensionName;

  /**
   * Set to $_GET / $_POST from outside
   *
   * @var array
   */
  protected $Request;

  /**
   *
   * @var array
   */
  public $Core = array();

  /**
   *
   */
  protected function Request( $param, $default=NULL ) {
    return isset($this->Request[$param])
         ? $this->Request[$param]
         : $default;
  }

  /**
   * Check for a POST request
   *
   * @return bool
   */
  protected function isPost() {
    return (isset($_SERVER['REQUEST_METHOD']) AND strtoupper($_SERVER['REQUEST_METHOD']) == 'POST');
  }
}
