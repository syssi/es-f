<?php
/**
 * @category   Module
 * @package    Module-Savings
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Homepage module
 *
 * @category   Module
 * @package    Module-Savings
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Module_Savings extends esf_Module {

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
      Messages::addInfo('No won auctions found.');
      $this->forward('auction');
    }
  }
}