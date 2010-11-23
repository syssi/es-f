<?php
/**
 * @category   Plugin
 * @package    Plugin-XEConverter
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Rewrite urls
 *
 * @category   Plugin
 * @package    Plugin-XEConverter
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_XEConverter extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('BuildMenu');
  }

  /**
   *
   */
  public function BuildMenu() {
    esf_Menu::addSystem( array(
      'extra'  => 'onclick="return openWin(\'http://www.xe.com/pca/input.php\',640,210)"',
      'url'    => 'http://www.xe.com/pca/input.php',
      'title'  => 'Currency converter by XE.com',
      'img'    => 'plugin/xeconverter/images/menu.gif',
      'type'   => 'image',
    ));
    // done
    Event::dettach($this);
  }
}

Event::attach(new esf_Plugin_XEConverter);