<?php
/**
 *
 */

/**
 *
 */
abstract class esf_Plugin extends esf_Extension implements EventHandlerI {

  public function __construct() {
    parent::__construct();
    $this->Layouts = explode(',', $this->Layouts);
  }

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   * Parse plugin specific templates
   */
  protected function Render( $tpl='content', $data=array() ) {
    return esf_Template::getInstance()->Render(
      $tpl,
      DEVELOP,
      $this->ExtensionScope.'/'.$this->ExtensionName.'/layout',
      $data
    );
  }

}