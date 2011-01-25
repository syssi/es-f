<?php
/**
 * Module RSS installer
 *
 * @ingroup    Module
 * @ingroup    Module-RSS
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class esf_Install_Module_RSS extends esf_Install {

  /**
   * Module info
   *
   * @return string
   */
  public function Info() {
    $app = APPID;
    $user = esf_User::isValid() ? MD5Encryptor::encrypt(esf_User::getActual()) : '&lt;UserID&gt;';
    // html allowed
    return <<<EOT
      <p>Call news feed of your auction in form of</p>

      <div class="li">
        The default call is:
        <br>
        <tt>index.php?module=rss&amp;$app=$user</tt>
      </div>
      <div class="li">
        with Apaches mod_rewrite you can also use:
        <br>
        <tt>rss.xml?$app=$user</tt>
      </div>
EOT;
  }

  /**
   * Module installation
   *
   * @return string
   */
  public function Install() {
    $code = file_get_contents(dirname(__FILE__).'/_htaccess');
    if (!preg_match('~# START module rss~i', file_get_contents('.htaccess'))) {
      if (is_writeable('.htaccess')) {
        File::append('.htaccess', $code);
        $this->Message('Modified .htaccess successful for module RSS support.', Messages::SUCCESS);
      } else {
        $this->Message('Please make sure, .htaccess is writable from this script for automatic '
                      .'module RSS support or add this manually to your .htaccess:');
        $this->Message('<pre>'.htmlspecialchars($code).'</pre>', Messages::CODE, TRUE);
        return FALSE;
      }
    }
  }

  /**
   * Module deinstallation
   *
   * @return string
   */
  public function Deinstall() {
    $ht = file_get_contents('.htaccess');
    if (preg_match('~# START module rss~i',$ht) AND is_writeable('.htaccess')) {
      $ht = preg_replace('~^\s*# START module rss.*?# END module rss\s*$~ims', '', $ht);
      File::write('.htaccess', $ht);
      $this->Message('Removed module RSS support successful from .htaccess', Messages::SUCCESS);
    }
  }
}