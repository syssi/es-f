<?php
/** @defgroup Plugin-XEConverter Plugin XEConverter

*/

/**
 * Plugin XEConverter
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-XEConverter
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
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