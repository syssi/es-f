<?php
/**
 * @category   Plugin
 * @package    Plugin-AuctionLayout
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Set some layout specific data
 *
 * @category   Plugin
 * @package    Plugin-AuctionLayout
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_Large_Module_Auction extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('OutputStart');
  }

  /**
   *
   */
  function OutputStart( &$tpldata ) {
    TplData::set('ThumbSize', 80);
  }

}

Event::attach(new esf_Plugin_Large_Module_Auction);