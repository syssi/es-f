<?php
/**
 *
 */

/**
 * Class for Extension installation
 */
class esf_Install_Plugin_Session extends esf_Install {

  /**
   * Install plugin
   *
   * Create directory
   */
  public function Install () {
    return $this->CreateDirectory();
  }

  /**
   * Install info
   */
  public function Info () {
    return <<<EOT
EOT;
  }

}