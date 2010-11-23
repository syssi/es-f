<?php
/**
 *
 */

/**
 * Class for Extension installation
 */
class esf_Install_Module_Refresh extends esf_Install {

  /**
   * Module info
   *
   * @return string
   */
  public function Info() {
    return '
      <p>This module refreshes your auctions and read the actual data from ebay.</p>
      <p>If you configure module variable "MaxAge", auctions are refreshed auctomatic.</p>
    ';
  }
}