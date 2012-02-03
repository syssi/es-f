<?php
/**
 * Parser for ebay.co.uk
 *
 * @ingroup    ebayParser
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class ebayParser_co_uk extends ebayParser {

  /**
   * Class constructor
   */
  public function __construct() {
    parent::__construct('co_uk');
  }

  /**
   * Individually convert end time string to a timestamp
   *
   * @param string $dt Found string by reg. expression
   */
  public function getDetailEND( $dt ) {

    /// _dbg($ts);

    if (!preg_match('~(\d+)\s*(\w+),\s*(\d{4})(\d+):(\d+):(\d+)\s+(\w+)~', $dt, $ts))
      return FALSE;

    // translate month
    $ts[2] = array_search(strtoupper($ts[2]),
                          array(1=>'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN',
                                   'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'));

    return $this->BuildTimestamp($ts);
  }

} // class