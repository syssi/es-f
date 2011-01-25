<?php
/**
 * Set some layout specific data
 *
 * @ingroup    Module-Auction
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-25-gfc3c29e - Sat Jan 1 21:14:18 2011 +0100 $
 */
class esf_Plugin_Small_Module_Auction extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('OutputStart');
  }

  /**
   *
   */
  function OutputStart() {
    // Don't show refresh button on group and auction level
    if ($this->RefreshButtons > 1) $this->RefreshButtons = 1;
  }

}

Event::attach(new esf_Plugin_Small_Module_Auction);