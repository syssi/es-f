<?php
/** @defgroup Module-Savings Module Savings

*/

/**
 * Module Auction savings
 *
 * @ingroup    Module
 * @ingroup    Module-Savings
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class esf_Module_Savings extends esf_Module {

  /**
   * @return array Array of actions handled by the module
   */
  public function handles() {
    return array('index');
  }

  /**
   *
   */
  public function IndexAction() {
    $auctions = $total = array();
    $user = esf_User::getActual();
    foreach (esf_Auctions::$Auctions as $item=>$auction) {
      if ($auction['ended'] AND $auction['bidder'] == $user) {
        $bid = esf_Auctions::getBid(esf_Auctions::getGroup($item));
        $bid = esf_Auctions::getBid($item);
        $save = $bid - $auction['bid'];
        $savep = 100 - ($auction['bid'] * 100 / $bid);
        $auctions[] = array (
          'NAME'          => $auction['name'],
          'PRICE'         => $auction['bid'],
          'BID'           => $bid,
          'SAVING'        => $save,
          'SAVINGPERCENT' => $savep,
        );
        $total['PRICE']         += $auction['bid'];
        $total['BID']           += $bid;
        $total['SAVING']        += $save;
        $total['SAVINGPERCENT'] += $savep;
      }
    }

    if (count($auctions)) {
      TplData::set('AUCTIONS', $auctions);
      $total['SAVINGPERCENT'] = $total['SAVINGPERCENT'] / count($auctions);
      TplData::set('TOTAL', $total);
    } else {
      Messages::Info('No won auctions found.');
      $this->forward('auction');
    }
  }
}