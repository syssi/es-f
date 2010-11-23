<?php
/**
 *
 */

/**
 * Class for Extension installation
 */
class esf_Install_Module_Analyse extends esf_Install {

  /**
   * Setup message
   *
   * @return string
   */
  public function SetupInfo() {
    return '
      It needs separate installation because depending of the PHP version,
      there will be installed different versions of
      <a class="extern" href="http://www.aditus.nu/jpgraph/index.php">JpGraph</a>
      (JpGraph1 for PHP < 5.1.0 else JpGraph2).
    ';
  }

  /**
   * Module info
   *
   * @return string
   */
  public function Info() {
    return '
      <p>This module analyses auctions of a group regarding the # of bids, end price and
      end time to identify potential "cheap" auctions.</p>
      <p>Depending of the PHP version, there will be installed different versions of
      <a class="extern" href="http://www.aditus.nu/jpgraph/index.php">JpGraph</a>
      (JpGraph1 for PHP < 5.1.0 else JpGraph2).</p>
    ';
  }

  /**
   * Module installation
   *
   * @return boolean
   */
  public function Install() {
    $ver = version_compare(PHP_VERSION, '5.1.0', '<') ? 1 : 2;
    if ($this->ExtractArchive('install/jpgraph'.$ver.'.zip')) {
      $this->Message('Installed JpGraph'.$ver);
    } else {
      return TRUE;
    }
  }

}