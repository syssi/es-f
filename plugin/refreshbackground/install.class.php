<?php
/**
 * Class for Extension installation
 *
 * @ingroup    Plugin-RefreshBackground
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-61-ga611518 2011-01-23 13:19:14 +0100 $
 */
class esf_Install_Plugin_RefreshBackground extends esf_Install {

  /**
   * Plugin information
   */
  public function Info() {
    $php = Exec::getInstance()->Execute('which php', $php) ? 'path/to/your/php' : $php[0];
    return sprintf(Translation::get('Refresh_bg.Info', $php, dirname(__FILE__)));
  }

  /**
   * Plugin installation function
   */
  public function Install() {
    Exec::getInstance()->Execute('which php', $php);
    if (isset($php[0])) {
      $this->Message('Found cli version of PHP as "'.$php[0].'"', Messages::SUCCESS);
    } else {
      $this->Message('Cli version of PHP not found, please install or '
                    .'configure whole path afterwards!', Messages::INFO);
    }
  }

  /**
   * Plugin activation function
   */
  public function Enable() {
    $this->ForceInfo = TRUE;
  }

}