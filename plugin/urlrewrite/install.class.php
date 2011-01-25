<?php
/**
 * Plugin UrlRewrite installer
 *
 * @ingroup    Plugin-UrlRewrite
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class esf_Install_Plugin_UrlRewrite extends esf_Install {

  /**
   *
   */
  public function Info() {
    // html allowed
    return 'Make user friendly urls in form of <tt>&lt;module&gt;[-&lt;action&gt;].html?...</tt>';
  }

  /**
   *
   */
  public function Install() {
    $htaccess = file_get_contents(dirname(__FILE__).'/install/_htaccess');

    if (!preg_match('~# START URLRewrite~i',file_get_contents('.htaccess'))) {
      if (is_writeable('.htaccess')) {
        File::write('.htaccess', "\n\n".$htaccess, 'a');
        $this->Message('Modified .htaccess successful for UrlRewrite plugin support.', Messages::SUCCESS);
      } else {
        $this->Message('Please make sure, .htaccess is writable from this script for automatic '
                      .'UrlRewrite plugin support or add this manually to your .htaccess:', Messages::INFO);
        $this->Message('<pre>'.htmlspecialchars($htaccess).'</pre>', Messages::CODE, TRUE);
        return TRUE;
      }
    }
    return $this->checkMultiple();
  }

  /**
   *
   */
  public function Disable() {
    return $this->checkMultiple();
  }

  /**
   *
   */
  public function DisableFinished() {
    // reload page with new (unrewritten) links
    Core::Redirect(Core::URL(array('module'=>'backend')));
  }

  /**
   *
   */
  public function Deinstall() {
    $htaccess = file_get_contents('.htaccess');

    if (preg_match('~# START URLRewrite~i', $htaccess) AND is_writeable('.htaccess')) {
      $htaccess = preg_replace('~^\s*# START URLRewrite.*?# END URLRewrite\s*$~ims', '', $htaccess);
      File::write('.htaccess', $htaccess);
      $this->Message('Removed UrlRewrite plugin support successful from .htaccess', Messages::SUCCESS);
    }
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   *
   */
  private function checkMultiple () {
    $this->Message('ATTENTION: Make sure, only ONE plugin can be installed for URL rewriting!');
  }

}
