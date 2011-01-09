<?php
/**
 * Add some infos to seller data
 *
 * @ingroup    Plugin-SellerInfo
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class esf_Plugin_SellerInfo extends esf_Plugin {

  /**
   * Class constructor
   */
  public function __construct() {
    parent::__construct();
    $this->ExtraKey = md5(__CLASS__);
  }

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('AuctionReadedInitial', 'AuctionReaded', 'DisplayAuction');
  }

  /**
   *
   */
  public function AuctionReadedInitial( &$auction ) {
    $this->Execute($auction);
  }

  /**
   *
   */
  public function AuctionReaded( &$auction ) {
    if ($this->ReadMultiple) $this->Execute($auction);
  }

  /**
   *
   */
  public function DisplayAuction( &$auction ) {
    if ($data['INFO'] = esf_Auctions::getExtra($auction, $this->ExtraKey)) {
      $data['HOMEPAGE'] = Registry::get('ebay.Homepage');
      $data['SELLER'] = $auction['seller'];
      esf_Auctions::setDisplay($auction, 'seller',
                               $this->Render('content', $data), TRUE);
    }
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   *
   * @var string
   */
  private $ExtraKey;

  /**
   * Buffer seller info to avoid multiple read, e.g. on refreshing all auctions
   *
   * For the case, no seller could be found...
   */
  private $Sellers = array( '' => '' );

  /**
   *
   */
  private function Execute( &$auction ) {
    if (empty($this->Sellers[$auction['seller']])) {
      $this->Sellers[$auction['seller']] = $auction['seller'];
      $url = $this->Homepage;
      $html = HTMLpage::get(str_ireplace('$SELLER', $auction['seller'], $url), $err);
      if ($err) {
        Messages::Error($err);
      } elseif (preg_match($this->DataRegex, $html, $args)) {
        $info = trim($args[1]);

        // replace "spacer images" .../s.gif with hard spaces
        $info = preg_replace('~<img[^>]+?src=(["\'])[^>]+?/s\.gif\\1[^>]+?'.'>~i','&nbsp;', $info);
        $info = preg_replace('~\s*/>~s', '>', $info);
        $info = preg_replace('~\s*xmlns=["\']?.*?["\']?~is', '', $info);

        // switch ebay links to users prefered TLD
        if (($tld=strtolower(Registry::get('EbayTLD'))) != 'com')
          $info = preg_replace('~ebay\.com/~i', 'ebay.'.$tld.'/', $info);

        // remove "reviewer image"
        $info = preg_replace('~<img.*src=.*imgAvaBadge1000.gif.*>~iU', '', $info);
        $info = trim($info);
        $this->Sellers[$auction['seller']] = $info;
        // >> Debug
        Yryie::Info('Seller info: ' . $info);
        // << Debug
      }
    }

    esf_Auctions::setExtra($auction, $this->ExtraKey, $this->Sellers[$auction['seller']]);
  }

}

Event::attach(new esf_Plugin_SellerInfo);