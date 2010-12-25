<?php
/**
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
abstract class eBayIncrements {

  /*
   * http://esniper.cvs.sourceforge.net/viewvc/esniper/esniper/auctioninfo.c?view=log
   *
   * Bidding increments
   *
   * first number is threshold for next increment range, second is increment.
   * For example, 1.00, 0.05 means that under $1.00 the increment is $0.05.
   *
   * Increments obtained from:
   * http://pages.ebay.com/help/buy/bid-increments.html
   * (and similar pages on international sites)
   */

  /**
   *
   * @param numeric $bid
   * qparam string $currency
   */
  public static function getNext( $bid, $currency ) {
    $currency = strtoupper($currency);
    if (isset(self::$Increments[$currency])) {
      // find relevant increment
      foreach (array_reverse(self::$Increments[$currency], TRUE) as $step=>$increment)
        if ($step <= $bid) break;
    } else {
      // be on the save side ;-)
      $increment = 0.01;
    }
    return $bid + $increment;
  } // getNext

  // ---------------------------------------------------------------------------
  // PRIVATE
  // ---------------------------------------------------------------------------

  /**
   * Increments used by eBay to calculate next possible auction bid
   *
   * @var array
   */
  private static $Increments = array(

    /*
     * Auction items not available from ebay.com:
     *
     * Argentina: http://www.mercadolibre.com.ar/
     * Brazil: http://www.mercadolivre.com.br/
     * India: http://www.baazee.com/ (seller can set increments)
     * Korea: http://www.auction.co.kr/
     * Mexico: http://www.mercadolibre.com.mx/
     */

    /*
     * Australia: http://pages.ebay.com.au/help/buy/bid-increments.html
     */
    'AU'  => array(    0 =>   0.05,
                       1 =>   0.25,
                       5 =>   0.50,
                      25 =>   1.00,
                     100 =>   2.50,
                     250 =>   5.00,
                     500 =>  10.00,
                    1000 =>  25.00,
                    2500 =>  50.00,
                    5000 => 100.00),

    /*
     * Austria: http://pages.ebay.at/help/buy/bid-increments.html
     * Belgium: http://pages.befr.ebay.be/help/buy/bid-increments.html
     * France: http://pages.ebay.fr/help/buy/bid-increments.html
     * Germany: http://pages.ebay.de/help/buy/bid-increments.html
     * Italy: http://pages.ebay.it/help/buy/bid-increments.html
     * Netherlands: http://pages.ebay.nl/help/buy/bid-increments.html
     * Spain: http://pages.es.ebay.com/help/buy/bid-increments.html
     */
    'EUR' => array(    0 =>   0.50,
                      50 =>   1.00,
                     500 =>   5.00,
                    1000 =>  10.00,
                    5000 =>  50.00),

    /*
     * Canada: http://pages.ebay.ca/help/buy/bid-increments.html
     */
    'C'   => array(    0 =>   0.05,
                       1 =>   0.25,
                       5 =>   0.50,
                      25 =>   1.00,
                     100 =>   2.50),

    /*
     * China: http://pages.ebay.com.cn/help/buy/bid-increments.html
     */
    'RMB' => array(    0 =>   0.05,
                       1 =>   0.20,
                       5 =>   0.50,
                      15 =>   1.00,
                      60 =>   2.00,
                     150 =>   5.00,
                     300 =>  10.00,
                     600 =>  20.00,
                    1500 =>  50.00,
                    3000 => 100.00),

    /*
     * Hong Kong: http://www.ebay.com.hk/
     *
     * Note: Cannot find bid-increments page.  Will use 0.01 to be safe.
     */

    /*
     * Singapore: http://www.ebay.com.sg/
     *
     * Note: Cannot find bid-increments page.  Will use 0.01 to be safe.
     *       From looking at auctions, it appears to be similar to US
     *       increments.
     */

    /*
     * Switzerland: http://pages.ebay.ch/help/buy/bid-increments.html
     */
    'CHF' => array(    0 =>  0.50,
                      50 =>  1.00,
                     500 =>  5.00,
                    1000 => 10.00,
                    5000 => 50.00),

    /*
     * Taiwan: http://pages.tw.ebay.com/help/buy/bid-increments.html
     */
    'NT'  => array(    0 =>  15.00,
                     500 =>  30.00,
                    2500 =>  50.00,
                    5000 => 100.00,
                   25000 => 200.00),

    /*
     * Ireland: http://pages.ebay.co.uk/help/buy/bid-increments.html
     * Sweden: http://pages.ebay.co.uk/help/buy/bid-increments.html
     * UK: http://pages.ebay.co.uk/help/buy/bid-increments.html
     *
     * Note: Sweden & Ireland use GBP or EUR.  English help pages redirect
     *       to UK site.
     */
    'GBP' => array(    0 =>   0.05,
                       1 =>   0.20,
                       5 =>   0.50,
                      15 =>   1.00,
                      60 =>   2.00,
                     150 =>   5.00,
                     300 =>  10.00,
                     600 =>  20.00,
                    1500 =>  50.00,
                    3000 => 100.00),

    /*
     * New Zealand: http://pages.ebay.com/help/buy/bid-increments.html
     * US: http://pages.ebay.com/help/buy/bid-increments.html
     *
     * Note: New Zealand site uses US or NT.
     */
    'US'  => array(    0 =>   0.05,
                       1 =>   0.25,
                       5 =>   0.50,
                      25 =>   1.00,
                     100 =>   2.50,
                     250 =>   5.00,
                     500 =>  10.00,
                    1000 =>  25.00,
                    2500 =>  50.00,
                    5000 => 100.00),

  ); // $Increments

}