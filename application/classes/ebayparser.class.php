<?php
/** @defgroup ebayParser

*/

/**
 * Parameters for HTML parser of eBay pages
 *
 * @ingroup    ebayParser
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 * @throws     ebayParserException
 */
abstract class ebayParser {

  /**
   * Parser expressions version
   */
  public $Version;

  /**
   * Urls to parse
   */
  public $URL = array();

  /**
   * Constructor
   *
   * @param $tld string Top level domain to create
   */
  public final static function factory( $tld ) {
    $tld = str_replace('.', '_', strtolower($tld));
    $file = dirname(__FILE__).'/ebayparser/'.$tld.'.class.php';
    if (Loader::Load($file)) {
      $class = 'ebayParser_'.$tld;
      // >> Debug
      Yryie::Info($class.' ('.$file.')');
      // << Debug
      return new $class;
    }
    throw new ebayParserException('Missing file parser class: '.$file);
  }

  /**
   * Get an auction detail
   *
   * @param $item string
   * @param $name string Detail name
   * @param $stripTags bool Strip tags from result
   * @param $url string URL id
   * @return string
   */
  public final function getDetail( $item, $name, $stripTags=TRUE, $url=NULL ) {
    if (!isset($this->RegEx[$name])) {
      Messages::Info('Missing reg. expression for detail "'.$name.'"');
      return FALSE;
    }

    $result = '';
    $html = AuctionHTML::getHTML($item, $this->URL, $err, ($url?$url:$name));
    if ($err) {
      Messages::Error($err);
    } else {
      // >> Debug
      $dbg = '\'%s\' => %s';
      $msgs = array();
      // << Debug

      foreach ($this->RegEx[$name] as $regex => $id) {
        if (preg_match($regex, $html, $args)) {
          if ($id == 0) {
            // if $id == 0 only find out, if HTML contains the whole regex
            $result = TRUE;
            // >> Debug
            $msgs[] = sprintf($dbg, $regex, 'TRUE');
            // << Debug
          } else {
            $result = trim($args[$id]);
            if ($stripTags) {
              // clear out the result
              $result = strip_tags($result);
              $result = str_replace('&nbsp;', ' ', $result);
            }
            // >> Debug
            $msgs[] = sprintf($dbg, $regex, '"'.$result.'" ('.$args[0].')');
            // << Debug
          }
          // hit found, escape
          $found = TRUE;
          break;
        // >> Debug
        } else {
          $msgs[] = sprintf($dbg, $regex, '[NO MATCH]');
        // << Debug
        }
      }
      // >> Debug
      foreach ($msgs as $dbg) Yryie::Debug($name.' : '.$dbg);
      // << Debug
    }

    // Check for a special detail method
    $method = 'getDetail'.$name;
    if (method_exists($this, $method)) $result = $this->$method($result);

    return $result;
  }

  /**
   * Add another url to parse (e.g. from plugin)
   *
   * @param $name string URL id
   * @param $url string URL
   */
  public final function setURL( $name, $url ) {
    $this->URL[strtoupper($name)] = $url;
  }

  /**
   * Add another expression (e.g. from plugin)
   *
   * @param $name string Detail name
   * @param $expression string reg. expression matching the detail
   */
  public final function setExpression( $name, $expression ) {
    $this->RegEx[strtoupper($name)] = $expression;
  }

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   * reg. expressions, load from config files
   */
  protected $RegEx = array();

  /**
   * local time zone
   */
  protected $Timezone;

  /**
   * Time zone definitions
   *
   * Source: http://www.timegenie.com/timezones.php
   */
  protected $TimeZones = array(
    'BIT'     => -12,          // Baker Island Time
    'NUT'     => -11,          // Niue Time
    'SST'     => -11,          // Samoa Standard Time
    'CKT'     => -10,          // Cook Island Time
    'HAST'    => -10,          // Hawaii - Aleutian Standard Time
    'TAHT'    => -10,          // Tahiti Time
    'TKT'     => -10,          // Tokelau Time
    'MIT'     =>  -9.5,        // Marquesas Islands Time
    'AKST'    =>  -9,          // Alaska Standard Time
    'GIT'     =>  -9,          // Gambier Island Time
    'HADT'    =>  -9,          // Hawaii - Aleutian Daylight Time
    'AKDT'    =>  -8,          // Alaska Daylight Time
    'CIST'    =>  -8,          // Clipperton Island Standard Time
    'PST'     =>  -8,          // Pacific Standard Time
    'PST'     =>  -8,          // Pitcairn Standard Time
    'MST'     =>  -7,          // Mountain Standard Time
    'PDT'     =>  -7,          // Pacific Daylight Time
    'CST'     =>  -6,          // Central Standard Time
    'EAST'    =>  -6,          // Easter Island Standard Time
    'GALT'    =>  -6,          // Galapagos Time
    'MDT'     =>  -6,          // Mountain Daylight Time
    'PIT'     =>  -6,          // Peter Island Time
    'ACT'     =>  -5,          // Acre Time
    'CDT'     =>  -5,          // Central Daylight Time
    'COT'     =>  -5,          // Colombia Time
    'EADT'    =>  -5,          // Easter Island Daylight Time
    'ECT'     =>  -5,          // Ecuador Time
    'EST'     =>  -5,          // Eastern Standard Time
    'PET'     =>  -5,          // Peru Time
    'VST'     =>  -4.5,        // Venezuela Standard Time
    'AMT'     =>  -4,          // Amazon Time
    'AST'     =>  -4,          // Atlantic Standard Time
    'BOT'     =>  -4,          // Bolivia Time
    'CLST'    =>  -4,          // Chile Standard Time
    'EDT'     =>  -4,          // Eastern Daylight Time
    'FKST'    =>  -4,          // Falkland Island Standard Time
    'GYT'     =>  -4,          // Guyana Time
    'JFST'    =>  -4,          // Juan Fernandez Islands Standard Time
    'PYT'     =>  -4,          // Paraguay Time
    'NST'     =>  -3.5,        // Newfoundland Standard Time
    'ADT'     =>  -3,          // Atlantic Daylight Time
    'AMST'    =>  -3,          // Amazon Standard Time
    'ART'     =>  -3,          // Argentina Time
    'BRT'     =>  -3,          // Brazilia Time
    'CGT'     =>  -3,          // Central Greenland Time
    'CLDT'    =>  -3,          // Chile Daylight Time
    'FKDT'    =>  -3,          // Falkland Island Daylight Time
    'GFT'     =>  -3,          // French Guiana Time
    'JFDT'    =>  -3,          // Juan Fernandez Islands Daylight Time
    'PMST'    =>  -3,          // Pierre & Miquelon Standard Time
    'PYST'    =>  -3,          // Paraguay Summer Time
    'ROTT'    =>  -3,          // Rothera Time
    'SRT'     =>  -3,          // Suriname Time
    'UYT'     =>  -3,          // Uruguay Standard Time
    'NDT'     =>  -2.5,        // Newfoundland Daylight Time
    'BRST'    =>  -2,          // Brazilia Summer Time
    'CGST'    =>  -2,          // Central Greenland Summer Time
    'FNT'     =>  -2,          // Fernando de Noronha Time
    'GST'     =>  -2,          // South Georgia and the South Sandwich Islands
    'PMDT'    =>  -2,          // Pierre & Miquelon Daylight Time
    'UYST'    =>  -2,          // Uruguay Summer Time
    'AZOST'   =>  -1,          // Azores Standard Time
    'CVT'     =>  -1,          // Cape Verde Time
    'EGT'     =>  -1,          // Eastern Greenland Time
    'AZODT'   =>   0,          // Azores Daylight Time
    'EGST'    =>   0,          // Eastern Greenland Summer Time
    'GMT'     =>   0,          // Greenwich Meantime
    'IST'     =>   0,          // Ireland Standard Time
    'SLT'     =>   0,          // Sierra Leone Time
    'UTC'     =>   0,          // Universal Coordinated Time
    'WET'     =>   0,          // Western Europe Time
    'BST'     =>   1,          // British Summer Time
    'CET'     =>   1,          // Central Europe Time
    'MEZ'     =>   1,          // Mitteleuropäische Zeit
    'IDT'     =>   1,          // Ireland Daylight Time
    'SEST'    =>   1,          // Swedish Standard Time
    'WAT'     =>   1,          // Western Africa Time
    'WEST'    =>   1,          // Western Europe Summer Time
    'CAT'     =>   2,          // Central Africa Time
    'CEDT'    =>   2,          // Central Europe Daylight Time
    'CEST'    =>   2,          // Central Europe Summer Time
    'MESZ'    =>   2,          // Mitteleuropäische Sommerzeit
    'EET'     =>   2,          // Eastern Europe Time
    'IST'     =>   2,          // Israel Standard Time
    'SAST'    =>   2,          // South Africa Standard Time
    'SYT'     =>   2,          // Syrian Standard Time
    'WAST'    =>   2,          // Western Africa Summer Time
    'AST'     =>   3,          // Al Manamah Standard Time
    'AST'     =>   3,          // Arabia Standard Time
    'AST'     =>   3,          // Arabic Standard Time
    'EAT'     =>   3,          // East Africa Time
    'EEST'    =>   3,          // Eastern Europe Summer Time
    'IDT'     =>   3,          // Israel Daylight Time
    'MSST'    =>   3,          // Moscow Standard Time
    'SYST'    =>   3,          // Syrian Summer Time
    'IRST'    =>   3.5,        // Îran Standard Time
    'ADT'     =>   4,          // Arabia Daylight Time
    'AMST'    =>   4,          // Armenia Standard Time
    'AZT'     =>   4,          // Azerbaijan Time
    'GET'     =>   4,          // Georgia Standard Time
    'GST'     =>   4,          // Gulf Standard Time
    'ICT'     =>   4,          // Îles Crozet Time
    'MSDT'    =>   4,          // Moscow Daylight Time
    'MUT'     =>   4,          // Mauritius Time
    'RET'     =>   4,          // Réunion Time
    'SAMT'    =>   4,          // Samara Time
    'SCT'     =>   4,          // Seychelles Time
    'AFT'     =>   4.5,        // Afghanistan Time
    'IRDT'    =>   4.5,        // Îran Daylight Time
    'AMDT'    =>   5,          // Armenia Daylight Time
    'AZST'    =>   5,          // Azerbaijan Summer Time
    'CAST'    =>   5,          // Chinese Antarctic Standard Time
    'HMT'     =>   5,          // Heard and McDonald Islands Time
    'KGT'     =>   5,          // Kyrgyzstan Time
    'MVT'     =>   5,          // Maldives Time
    'PKT'     =>   5,          // Pakistan Time
    'SAMST'   =>   5,          // Samara Summer Time
    'TFT'     =>   5,          // French Southern and Antarctic Time
    'TJT'     =>   5,          // Tajikistan Time
    'TMT'     =>   5,          // Turkmenistan Time
    'UZT'     =>   5,          // Uzbekistan Time
    'WKST'    =>   5,          // West Kazakhstan Standard Time
    'YEKT'    =>   5,          // Yekaterinburg Time
    'IST'     =>   5.5,        // Indian Standard Time
    'NPT'     =>   5.75,       // Nepal Time
    'BDT'     =>   6,          // Bangladesh Time
    'BIOT'    =>   6,          // British Indian Ocean Time
    'BTT'     =>   6,          // Bhutan Time
    'EKST'    =>   6,          // East Kazakhstan Standard Time
    'KGST'    =>   6,          // Kyrgyzstan Summer Time
    'LKT'     =>   6,          // Sri Lanka Time
    'MAWT'    =>   6,          // Mawson Time
    'NOVT'    =>   6,          // Novosibirsk Time
    'OMST'    =>   6,          // Omsk Standard Time
    'VOST'    =>   6,          // Vostok Time
    'YEKST'   =>   6,          // Yekaterinburg Summer Time
    'CCT'     =>   6.5,        // Cocos Islands Time
    'MMT'     =>   6.5,        // Myanmar Time
    'CXT'     =>   7,          // Christmas Island Time
    'DAVT'    =>   7,          // Davis Time
    'ICT'     =>   7,          // Indochina Time
    'KOVT'    =>   7,          // Khovd Time
    'KRAT'    =>   7,          // Krasnoyarsk Time
    'NOVST'   =>   7,          // Novosibirsk Summer Time
    'OMSST'   =>   7,          // Omsk Summer Time
    'WIB'     =>   7,          // Waktu Indonesia Bagian Barat
    'ACIT'    =>   8,          // Ashmore and Cartier Islands Time
    'BDT'     =>   8,          // Brunei Time
    'CST'     =>   8,          // China Standard Time
    'HKST'    =>   8,          // Hong Kong Standard Time
    'IRKT'    =>   8,          // Irkutsk Time
    'KOVST'   =>   8,          // Khovd Summer Time
    'KRAST'   =>   8,          // Krasnoyarsk Summer Time
    'MNT'     =>   8,          // Mongolia Time
    'MYT'     =>   8,          // Malaysia Time
    'PHT'     =>   8,          // Philippines Time
    'PIT'     =>   8,          // Paracel Islands Time
    'SGT'     =>   8,          // Singapore Time
    'SIT'     =>   8,          // Spratly Islands Time
    'TWT'     =>   8,          // Taiwan Time
    'WITA'    =>   8,          // Waktu Indonesia Bagian Tengah
    'WST'     =>   8,          // Western Australia Standard Time
    'ACWST'   =>   8.75,       // Australian Central Western Standard Time
    'IRKST'   =>   9,          // Irkutsk Summer Time
    'JST'     =>   9,          // Japan Standard Time
    'KST'     =>   9,          // Korea Standard Time
    'MNST'    =>   9,          // Mongolia Summer Time
    'PWT'     =>   9,          // Palau Time
    'TPT'     =>   9,          // East Timor Time
    'WDT'     =>   9,          // Western Australia Daylight Time
    'WIT'     =>   9,          // Waktu Indonesia Bagian Timur
    'YAKT'    =>   9,          // Yakutsk Time
    'ACST'    =>   9.5,        // Australian Central Standard Time
    'ACWDT'   =>   9.75,       // Australian Central Western Daylight Time
    'AEST'    =>  10,          // Australian Eastern Standard Time
    'ChST'    =>  10,          // Chamorro Standard Time
    'DTAT'    =>  10,          // District de Terre Adélie Time
    'PGT'     =>  10,          // Papua New Guinea Time
    'TRUT'    =>  10,          // Truk Time
    'VLAT'    =>  10,          // Vladivostok Time
    'YAKST'   =>  10,          // Yakutsk Summer Time
    'YAPT'    =>  10,          // Yap Time
    'ACDT'    =>  10.5,        // Australian Central Daylight Time
    'LHST'    =>  10.5,        // Lord Howe Standard Time
    'AEDT'    =>  11,          // Australian Eastern Daylight Time
    'KOST'    =>  11,          // Kosrae Standard Time
    'LHDT'    =>  11,          // Lord Howe Daylight Time
    'MAGT'    =>  11,          // Magadan Island Time
    'NCT'     =>  11,          // New Caledonia Time
    'PONT'    =>  11,          // Pohnpei Standard Time
    'SBT'     =>  11,          // Solomon Island Time
    'VLAST'   =>  11,          // Vladivostok Summer Time
    'VUT'     =>  11,          // Vanuatu Time
    'NFT'     =>  11.5,        // Norfolk Time
    'ANAT'    =>  12,          // Anadyr’ Time
    'FJT'     =>  12,          // Fiji Time
    'GILT'    =>  12,          // Gilbert Island Time
    'MAGST'   =>  12,          // Magadan Island Summer Time
    'MHT'     =>  12,          // Marshall Islands Time
    'NRT'     =>  12,          // Nauru Time
    'NZST'    =>  12,          // New Zealand Standard Time
    'PETT'    =>  12,          // Petropavlovsk Time
    'SCST'    =>  12,          // Santa Claus Standard Time
    'TVT'     =>  12,          // Tuvalu Time
    'WFT'     =>  12,          // Wallis and Futuna Time
    'CHAST'   =>  12.75,       // Chatham Island Standard Time
    'ANAST'   =>  13,          // Anadyr’ Summer Time
    'NZDT'    =>  13,          // New Zealand Daylight Time
    'PETST'   =>  13,          // Petropavlovsk Summer Time
    'PHOT'    =>  13,          // Phoenix Island Time
    'SCDT'    =>  13,          // Santa Claus Delivery Time
    'TOT'     =>  13,          // Tonga Time
    'CHADT'   =>  13.75,       // Chatham Island Daylight Time
    'LINT'    =>  14,          // Line Island Time
  );

  /**
   * Class constructor
   *
   * @param $tld string Top level domain to create
   */
  protected function __construct( $tld ) {
    $this->Timezone = date('T');
    $iniDir = dirname(__FILE__).'/ebayparser/';

    if (!IniFile::Parse($iniDir.$tld.'.ini', TRUE))
      throw new ebayParserException(IniFile::$Error);

    $this->Version = IniFile::$Data['Version'];
    $this->URL     = IniFile::$Data['URL'];
    $this->Transform(IniFile::$Data['EXPRESSIONS']);

    // add common definitions
    if (IniFile::Parse($iniDir.'common.ini', TRUE))
      $this->Transform(IniFile::$Data['EXPRESSIONS']);

/*
    $xml = new XML_Array_Parser(Registry::get('Cache'));
    $xml->Key2Lower = FALSE;

    $data = $xml->ParseXMLFile($iniDir.$tld.'.xml');
    if (!$data) throw new ebayParserException($xml->Error);

    $this->Version = $data['VERSION'];
    $this->URL     = $data['URL'];

    $this->TransformXML($data['EXPRESSIONS']);

    // add common definitions
    $data = $xml->ParseXMLFile($iniDir.'common.xml');
    if ($data) $this->TransformXML($data['EXPRESSIONS']);
*/

    // >> Debug
    Yryie::Debug($this->RegEx);
    // << Debug
  }

  /**
   * Transform expresions into key => val relations
   *
   * @param array $regex Expressions from ini file
   */
  private function Transform( $regex ) {
    foreach ($regex as $name => $data) {
      if (!is_array($data)) $regex[$name] = array($data);
      foreach ($regex[$name] as $id => $expr) {
        list($key, $val) = preg_split('~\s+=>\s+~', $expr, 2);
        // set name => expr
        $this->RegEx[$name][$key] = $val;
      }
    }
  }

  /**
   * Transform expresions into key => val relations
   *
   * @param array $array Expressions from xml file
   */
  private function TransformXML( $array ) {
    foreach ($array as $name => $data) {
      foreach ($data as $expression) {
        $this->RegEx1[$name][$expression['EXPRESSION']] =
          (isset($expression['HIT']) ? $expression['HIT'] : 0);
      }
    }
  }
} // class

/**
 *
 */
class ebayParserException extends Exception {}
