<?php
/** @defgroup Plugin-SellerItems Plugin SellerItems

*/

/**
 * Plugin SellerItems
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-SellerItems
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_SellerItems extends esf_Plugin {

  /**
   *
   */
  public function __construct() {
    parent::__construct();
    $this->URL = str_replace('$SELLER', '%s', $this->OtherItems);
    $this->URL = str_replace('$HOMEPAGE', Registry::get('ebay.Homepage'), $this->URL);
  }

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LanguageSet', 'DisplayAuction');
  }

  /**
   *
   */
  public function DisplayAuction( &$auction ) {
    if (empty($auction['seller'])) return;

    $data['URL'] = sprintf($this->URL, $auction['seller']);
    esf_Auctions::setDisplay($auction, 'seller',
                            $this->Render('content', $data), TRUE);
  }

}

Event::attach(new esf_Plugin_SellerItems);