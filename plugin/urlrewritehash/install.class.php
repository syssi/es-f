<?php
/**
 *
 */

/**
 * Class for Extension installation
 */
class esf_Install_Plugin_UrlRewriteHash extends esf_Install {

  /**
   * HTML code is allowed
   */
  public function Info() {
    return 'Make user friendly urls in form of <tt>?go&lt;HashOfParameters&gt;</tt>';
  }

  /**
   *
   */
  function Install() {
    return $this->checkMultiple();
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   *
   */
  function checkMultiple () {
    $this->Message('ATTENTION: Make sure, only ONE plugin can be installed for URL rewriting!');
  }

}