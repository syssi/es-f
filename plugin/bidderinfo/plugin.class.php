<?php
/**
 * @category   Plugin
 * @package    Plugin-BidderInfo
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Add. bidder infos
 *
 * @category   Plugin
 * @package    Plugin-BidderInfo
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_BidderInfo extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('DisplayAuction');
  }

  /**
   *
   */
  public function DisplayAuction( &$auction ) {
    $value = '';
    if (!empty($auction['bidder'])) {
      if (!preg_match('~.\*{3,}.~', $auction['bidder'])) {
//        $value = sprintf('<abbr title="%1$s" onmouseover="Tip(\'%1$s\',WIDTH,250)">%2$s</abbr>',
//                         Translation::get('BIDDERINFO.ANONYMOUS'), $auction['bidder']);
        $url = str_replace('$BIDDER', $auction['bidder'], $this->URL);
        $value = sprintf('<a href="%1$s" title="%2$s" onmouseover="Tip(\'%2$s\')">%3$s</a>',
                         $url, Translation::get('BIDDERINFO.FEEDBACK', $auction['bidder']), $auction['bidder']);
      }
    }
    esf_Auctions::setDisplay($auction, 'bidder', $value);
  }

}

Event::attach(new esf_Plugin_BidderInfo);