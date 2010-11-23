<?php
/**
 *
 */

/**
 * Class for Extension installation
 */
class esf_Install_Plugin_FileSystem extends esf_Install {

  /**
   * Setup message
   *
   * @return string
   */
  public function SetupInfo() {
    return '
      To speed up your installation, you can configure this plugin to buffer
      all auction data into the session and re-read from file system only if
      required (on changes).
    ';
  }

  /**
   * Plugin info
   *
   * @return string
   */
  public function Info() {
    return '<p>' . $this->SetupInfo() . '</p>';
  }

}