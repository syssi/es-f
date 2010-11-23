<?php
/**
 *
 */

/**
 * Class for Extension installation
 */
class esf_Install_Module_Register extends esf_Install {

  /**
   * Module installation
   *
   * @return boolean
   */
  public function Install() {
    return $this->CreateDirectory('reg');
  }
}