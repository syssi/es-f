<?php
/**
 * Delete ended auctions automatic after certain days
 *
 * @ingroup    Plugin-AutoDelete
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-42-g440d05f - Sun Jan 9 21:40:58 2011 +0100 $
 */
class esf_Plugin_AutoDelete extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('AuctionsLoaded');
  }

  /**
   * Handle AuctionsLoaded
   *
   * @access private
   * @param string $output HTML to filter
   * @return void
   */
  public function AuctionsLoaded ( &$auctions ) {
    // timestamp to check against
    $ts = $_SERVER['REQUEST_TIME'] - $this->Days*60*60*24;

    foreach ($auctions as $item => $auction) {
      if ($auction['endts'] AND $auction['endts'] < $ts) {
        esf_Auctions::Delete($auction, FALSE);
        Messages::Info(Translation::get('AutoDelete.Deleted', $item, $auction['name']));
      }
    }
    // Only once per script run!
    Event::Dettach($this);
  }
}

Event::attach(new esf_Plugin_AutoDelete);