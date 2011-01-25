<?php
/**
 * Plugin UrlRewriteHash installer
 *
 * @ingroup    Plugin-UrlRewriteHash
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
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