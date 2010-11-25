<?php
/**
 * @category   Plugin
 * @package    Plugin-Paypal
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Check for paypal allowed
 *
 * @category   Plugin
 * @package    Plugin-Paypal
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_Paypal extends esf_Plugin {

  /**
   * Class constructor
   */
  public function __construct() {
    parent::__construct();
    $this->ExtraKey = md5(__CLASS__);
    // transform config. data into parser data
    $regex = array();
    foreach ($this->Regex as $id => $expr) {
      $regex[$expr['expression']] =
        isset($expr['position']) ? $expr['position'] : 0;
    }
    $this->Regex = $regex;
  }

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('AuctionReadedInitial', 'DisplayAuction');
  }

  /**
   *
   */
  public function AuctionReadedInitial( &$auction ) {
    $parser = Registry::get('ebayParser');
    $parser->setExpression('PAYPAL', $this->Regex);
    if ($parser->getDetail($auction['item'], 'PAYPAL'))
      esf_Auctions::setExtra($auction, $this->ExtraKey, $this->Render());
  }

  /**
   *
   */
  public function DisplayAuction( &$auction ) {
    // don't show for ended auctions
    if ($auction['ended']) return;

    if ($data = esf_Auctions::getExtra($auction, $this->ExtraKey))
      esf_Auctions::setDisplay($auction, 'name', $data, TRUE);
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   *
   * @var string
   */
  private $ExtraKey;

}

// move at the end of the plugin chain, show as last add for auction name
Event::attach(new esf_Plugin_Paypal, 100);
