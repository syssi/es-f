<?php
/**
 *
 */

/**
 * Generic abstrct extension class for modules/plugins
 */
abstract class esf_Extension extends MagicObject {

  /**
   * Class constructor
   *
   * @return void
   */
  public function __construct() {
    list($this->ExtensionName, $this->ExtensionScope,) =
      array_reverse(explode('_', strtolower(get_class($this))));

    parent::__construct(Registry::get($this->ExtensionScope.'.'.$this->ExtensionName, array()));

    if (!$this->isPost())
      $this->Request =& $_GET;
    else
      $this->Request =& $_POST;

    is_dir($this->Core['localpath']) || Exec::getInstance()->MkDir($this->Core['localpath']);
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
   */
  protected $Request = array();

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