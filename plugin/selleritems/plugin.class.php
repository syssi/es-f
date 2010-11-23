<?php
/**
 * @category   Plugin
 * @package    Plugin-SellerItems
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Add a link to "More seller items" to seller name
 *
 * @category   Plugin
 * @package    Plugin-SellerItems
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
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
    return array('DisplayAuction');
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