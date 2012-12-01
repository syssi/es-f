<?php
/**
 * Parser for ebay.de

 *
 * @ingroup    ebayParser
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class ebayParser_de extends ebayParser {

  /**
   * Class constructor
   */
  public function __construct() {
    parent::__construct('de');
  }

  /**
   * Individually convert end time string to a timestamp
   *
   * @param string $dt Found string by reg. expression
   */
  public function getDetailEND( $dt ) {

  $months = array( 'Jan' => 1, 'Feb' => 2, 'März' => 3, 'Apr' => 4, 'Mai' => 5, 'Juni' => 6, 'Juli' => 7, 'Aug' => 8, 'Sept' => 9, 'Okt' => 10, 'Nov' => 11, 'Dez' => 12 );

  # _dbg($dt);

    if (preg_match('~(\d{1,2})\.(\d{1,2})\.(\d{1,4})\s+(\d{1,2}):(\d{1,2}):(\d{1,2})\s*(\w{3,4})~', $dt, $ts)) {
		# old scheme when there are no html tags in between date and time plus month represented by a number
		$ts = mktime($ts[4],$ts[5],$ts[6],$ts[2],$ts[1],$ts[3]);
		$ts -= $offset * 60*60;

	  # _dbg(date('r',$ts));
		
	}
	elseif (preg_match('~(\d{1,2})\.\s*([^\.\d\s]{3,4})\.?\s*(\d{1,4})\s*(?:<[^<]+>)*\s*(\d{1,2}):(\d{1,2}):(\d{1,2})\s*(\w{3,4})~', $dt, $ts)) {
		# new scheme when there are html tags in between date and time plus month represented by abreviated name string
		$ts = mktime($ts[4],$ts[5],$ts[6],$months[$ts[2]],$ts[1],$ts[3]);
		$ts -= $offset * 60*60;

	  # _dbg(date('r',$ts));

	}
	elseif (preg_match('~(\d{1,2})\.\s*([^\.\d\s]{3,4})\.?\s*(\d{1,4})\s+(\d{1,2}):(\d{1,2}):(\d{1,2})\s*(\w{3,4})~', $dt, $ts)) {
		# old scheme when there are no html tags in between date and time but month is represented by abreviated name string
		$ts = mktime($ts[4],$ts[5],$ts[6],$months[$ts[2]],$ts[1],$ts[3]);
		$ts -= $offset * 60*60;

	  # _dbg(date('r',$ts));

	}
	else {
		return FALSE;
	}
  # _dbg($ts);

    // local and ebay time zone
    $tz = array($this->Timezone, $ts[7]);

  # _dbg($tz);

    foreach ($tz as $t) {
      if (!isset($this->TimeZones[$t])) {
        trigger_error('Missing time zone definition: '.$t);
        $this->TimeZones[$t] = 0;
      }
    }

    // offset between ebay.com used time and local time plus undocumented server offset
    $offset = $this->TimeZones[$tz[1]] - $this->TimeZones[$tz[0]] - Registry::get('TZOFFSET');

  # _dbg($this->TimeZones[$tz[1]].' - '.$this->TimeZones[$tz[0]]);

    return $ts;
  }

} // class