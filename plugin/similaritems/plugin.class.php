<?php
/** @defgroup Plugin-SimilarItems Plugin SimilarItems

Add a link to search similar items on ebay

*/

/**
 * Plugin SimilarItems
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-SimilarItems
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_SimilarItems extends esf_Plugin {

  /**
   *
   */
  public function __construct() {
    parent::__construct();
    $this->ExtraKey = md5(__CLASS__);
    $this->URL = str_replace('$HOMEPAGE', Registry::get('ebay.Homepage'), $this->URL);
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
    $name = preg_replace('~[^'.$this->WordRegex.']+~', ' ', $auction['name']);
    $name = preg_replace('~\s\s+~', ' ', $name);

    $words = explode(' ', $name);
    foreach ($words as $id => $word)
      if (strlen($word) < $this->MinWordLength) unset($words[$id]);

    // find relevant words from auction title
    $regex = sprintf('~[%s]{%d,}~',
                     $this->WordRegex,
                     ceil(strlen(implode($words)) / count($words)));
    if (preg_match_all($regex, $auction['name'], $args)) {
      $query = urlencode(strtolower(implode(' ', $args[0])));
      $data['URL'] = str_replace('$QUERY', $query, $this->URL);
      esf_Auctions::setExtra($auction, $this->ExtraKey, $this->Render('content', $data));
    }
  }

  /**
   *
   */
  public function DisplayAuction( &$auction ) {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

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

Event::attach(new esf_Plugin_SimilarItems);