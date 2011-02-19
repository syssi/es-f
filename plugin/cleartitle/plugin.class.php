<?php
/** @defgroup Plugin-ClearTitle Plugin ClearTitle

Clear auction title, make more readable

*/

/**
 * Plugin ClearTitle
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-ClearTitle
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
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
    /// Yryie::Info('Title before: '.$auction['name']);
    do {
      $h = $auction['name'];
      $auction['name'] = preg_replace($from, $to, $auction['name']);
      // repeat until all changes done
    } while ($h != $auction['name']);
    $auction['name'] = trim($auction['name']);
    /// Yryie::Info('Title after: '.$auction['name']);
  }

  /**
   *
   */
  public function AuctionReaded( &$auction ) {
    $this->AuctionReadedInitial($auction);
  }

}

Event::attach(new esf_Plugin_ClearTitle);