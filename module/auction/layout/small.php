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
class esf_Plugin_Small_Module_Auction extends esf_Plugin {

  /**
   *
   */
  function OutputStart() {
    // Don't show refresh button on group and auction level
    if ($this->RefreshButtons > 1) $this->RefreshButtons = 1;
  }

}

Event::attach(new esf_Plugin_Small_Module_Auction);