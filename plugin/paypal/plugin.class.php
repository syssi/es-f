<?php
/** @defgroup Plugin-PayPal Plugin PayPal

*/

/**
 * Plugin PayPal
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-PayPal
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-80-g4acbac1 2011-02-15 22:22:16 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_Paypal extends esf_Plugin {

  /**
   * Class constructor
   */
  public function __construct() {
    parent::__construct();
    $this->ExtraKey = md5(__CLASS__);
    $this->Pattern = explode("\n", $this->Pattern);
  }

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LanguageSet', 'AuctionReadedInitial', 'DisplayAuction');
  }

  /**
   *
   */
  public function AuctionReadedInitial( &$auction ) {
    $parser = Registry::get('ebayParser');
    foreach ($this->Pattern as $pattern) $parser->setExpression('PayPal', $pattern);

    if ($parser->getDetail($auction['item'], 'PayPal'))
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
   * Key to store data into auctions extra data
   *
   * @var string $ExtraKey
   */
  private $ExtraKey;

}

// move at the end of the plugin chain, show as last add for auction name
Event::attach(new esf_Plugin_Paypal, 100);
