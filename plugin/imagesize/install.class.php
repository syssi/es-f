<?php
/**
 *
 */

/**
 * Class for Extension installation
 */
class esf_Install_Plugin_ImageSize extends esf_Install {

  /**
   * Setup message
   *
   * @return string
   */
  public function Info() {
    return '
      <p>To exclude images from manipulation, just add the pseudo attribut
      "noimagesize" to the img tag: <tt>&lt;img noimagesize ... /&gt;</tt></p>
    ';
  }

}