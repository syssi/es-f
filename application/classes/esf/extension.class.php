<?php
/**
 * Abstract extension
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
abstract class esf_Extension {

  /**
   * @todo
   * replace $this->Core['localpath'] by $this->LocalPath
   *
   * @var array $Core
   */
  public $Core = array();

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
   * Magic function...
   *
   * @param $name string
   * @param $value mixed
   */
  public function __set( $name, $value ) {
    Registry::set($this->ExtensionScope.'.'.$this->ExtensionName.'.'.$name, $value);
  }

  /**
   * Magic function...
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
   * @var string $ExtensionScope
   */
  protected $ExtensionScope;

  /**
   * @var string $ExtensionName
   */
  protected $ExtensionName;

  /**
   * Set to $_GET / $_POST from outside
   *
   * @var array $Request
   */
  protected $Request;

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
