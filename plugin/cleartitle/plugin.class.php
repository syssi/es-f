<?php
/**
 * @category   Plugin
 * @package    Plugin-ClearTitle
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Clear auction title, make more readable
 *
 * @category   Plugin
 * @package    Plugin-ClearTitle
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_ClearTitle extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('AuctionReadedInitial');
  }

  /**
   *
   */
  public function AuctionReadedInitial( &$auction ) {
    $from = $to = array();
    foreach ($this->Pattern as $p) {
      $from[] = $p['from'];
      $to[]   = str_replace('__', ' ', $p['to']);
    }
    // >> Debug
    DebugStack::Info('Title before: '.$auction['name']);
    // << Debug
    do {
      $h = $auction['name'];
      $auction['name'] = preg_replace($from, $to, $auction['name']);
      // repeat until all changes done
    } while ($h != $auction['name']);
    $auction['name'] = trim($auction['name']);
    // >> Debug
    DebugStack::Info('Title after: '.$auction['name']);
    // << Debug
  }

  /**
   *
   */
  public function AuctionReaded( &$auction ) {
    $this->AuctionReadedInitial($auction);
  }

}

Event::attach(new esf_Plugin_ClearTitle);