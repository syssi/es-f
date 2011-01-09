<?php
/** --------------------------------------------------------------------------
@file httplanguage.class.php File description
*/

/** --------------------------------------------------------------------------
@mainpage HTTPlanguage Page title

Idea from http://techpatterns.com/downloads/php_language_detection.php

A language tag identifies a natural language spoken, written, or otherwise
conveyed by human beings for communication of information to other human beings.
Computer languages are explicitly excluded. HTTP uses language tags within the
Accept-Language and Content- Language fields.

The syntax and registry of HTTP language tags is the same as that defined by
RFC 1766 [1]. In summary, a language tag is composed of 1 or more parts:
A primary language tag and a possibly empty series of subtags:

        language-tag  = primary-tag *( "-" subtag )
        primary-tag   = 1*8ALPHA
        subtag        = 1*8ALPHA

White space is not allowed within the tag and all tags are case-insensitive.
The name space of language tags is administered by the IANA. Example tags
include:

       en, en-US, en-cockney, i-cherokee, x-pig-latin

where any two-letter primary-tag is an ISO-639 language abbreviation and any
two-letter initial subtag is an ISO-3166 country code. (The last three tags
above are not registered tags; all but the last are examples of tags which could
be registered in future.)

Source: http://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.10

*/

/** --------------------------------------------------------------------------
@defgroup HTTPlanguage Group description

Brief description goes here

Long description goes here
*/

/** --------------------------------------------------------------------------
 * Brief description goes here
 *
 * Long description goes here
 *
 * @ingroup    HTTPlanguage
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2011 Knut Kohl
 * @par Licence:
 * <a href="http://www.gnu.org/licenses/gpl.txt">GNU General Public License</a>
 * @version    $Id$
 */
abstract class HTTPlanguage {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Error messages on unknown languages/counties
   *
   * @var array
   */
  public static $Error;

  /**
   * Brief description goes here
   *
   * Long description goes here
   *
   * <strong>Usage example:</strong>
   * @code
   * ...
   * @endcode
   *
   * @param bool $full Find only full codes or only primary codes
   * @return array|FALSE
   */
  public static function getAll( $full=TRUE ) {
    if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) return FALSE;

    self::$Error = $return = array();

    $languages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    #$languages = 'fr-ch;q=0.3, da, de-de;q=0.7, de, en-us;q=0.8, en;q=0.5, fr;q=0.3';
    $languages = explode(',', strtolower($languages));

    foreach ($languages as $language) {
      $language = trim($language);

      // Full language incl. country?
      list($language) = explode(';', $language);
      if (!$name = self::Name($language, FALSE)) continue; // skip

      $return[$full ? $language : substr($language, 0, 2)] = $name;
    }

    return $return;
  } // function get()

  /**
   * Brief description goes here
   *
   * Long description goes here
   *
   * <strong>Usage example:</strong>
   * @code
   * ...
   * @endcode
   *
   * @param string $language Full language code, e.g. en-us or en
   * @return string|FALSE e.g. for en-us -> English (United states), en -> English
   */
  public static function getName( $language ) {
    return self::Name($language, TRUE);
  } // function getName()

  /**
   * Brief description goes here
   *
   * Long description goes here
   *
   * <strong>Usage example:</strong>
   * @code
   * // Assume: $_SERVER['HTTP_ACCEPT_LANGUAGE'] is
   * //         'fr-ch;q=0.3, da, de-de;q=0.7, de, en-us;q=0.8, en;q=0.5, fr;q=0.3'
   * echo HTTPlanguage::getMatch( array('de', 'en') );
   * // Will output: de
   *
   * echo HTTPlanguage::getMatch( array('de-CH', 'en') );
   * // Will output: en
   *
   * echo HTTPlanguage::getMatch( array('de-CH', 'en'), TRUE );
   * // Will output: de because of the check for primary language,
   * //              because de is before en
   * @endcode
   *
   * @param array $languages Array of languages to check against.
   *                         Return 1st match in HTTP_ACCEPT_LANGUAGE
   * @param bool $primary Check also primary languages
   * @return string|FALSE Check on FALSE HTTPlanguage::$Error
   */
  public static function getMatch( $languages, $primary=FALSE ) {
    // Check full language Ids
    if ($HTTPlanguages = self::getAll())
      foreach ($HTTPlanguages as $lang=>$name)
        if (in_array($lang, $languages)) return $lang;

    // Check primary languages if not found yet
    if ($primary AND $HTTPlanguages = HTTPlanguage::getAll(FALSE))
      foreach ($HTTPlanguages as $lang=>$name)
        if (in_array($lang, $languages)) return $lang;

    return FALSE;
  } // function getMatch()

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Brief description goes here
   *
   * Long description goes here
   *
   * <strong>Usage example:</strong>
   * @code
   * ...
   * @endcode
   *
   * @param string $language Full language code, e.g. en-us or en
   * @param bool $clear Clear $Error, do not, if called from get()
   * @return string|FALSE e.g. for en-us -> English (United states), en -> English
   */
  private static function Name( $language, $clear ) {
    if ($clear) self::$Error = array();

    // Try to split into language and country
    @list($lg, $cn) = explode('-', $language);

    if (!isset(self::$languages[$lg])) {
      self::$Error[] = 'Unknown language: '.$lg.' in '.$language;
      return FALSE;
    }
    if (!empty($cn) AND !isset(self::$countries[$cn])) {
      self::$Error[] = 'Unknown country: '.$cn.' in '.$language;
      return FALSE;
    }

    $return = self::$languages[$lg];
    if (!empty($cn)) $return .= ' ('.self::$countries[$cn].')';
    return $return;
  } // function Name()

  /**
   * Code for the representation of names of languages
   *
   * http://ftp.ics.uci.edu/pub/ietf/http/related/iso639.txt
   *
   * @var array
   */
  private static $languages = array(
    'aa' => 'Afar',
    'ab' => 'Abkhazian',
    'af' => 'Afrikaans',
    'am' => 'Amharic',
    'ar' => 'Arabic',
    'as' => 'Assamese',
    'ay' => 'Aymara',
    'az' => 'Azerbaijani',

    'ba' => 'Bashkir',
    'be' => 'Byelorussian',
    'bg' => 'Bulgarian',
    'bh' => 'Bihari',
    'bi' => 'Bislama',
    'bn' => 'Bengali, Bangla',
    'bo' => 'Tibetan',
    'br' => 'Breton',

    'ca' => 'Catalan',
    'co' => 'Corsican',
    'cs' => 'Czech',
    'cy' => 'Welsh',

    'da' => 'Danish',
    'de' => 'German',
    'dz' => 'Bhutani',

    'el' => 'Greek',
    'en' => 'English',
    'eo' => 'Esperanto',
    'es' => 'Spanish',
    'et' => 'Estonian',
    'eu' => 'Basque',

    'fa' => 'Persian',
    'fi' => 'Finnish',
    'fj' => 'Fiji',
    'fo' => 'Faroese',
    'fr' => 'French',
    'fy' => 'Frisian',

    'ga' => 'Irish',
    'gd' => 'Scots, Gaelic',
    'gl' => 'Galician',
    'gn' => 'Guarani',
    'gu' => 'Gujarati',

    'ha' => 'Hausa',
    'he' => 'Hebrew',
    'hi' => 'Hindi',
    'hr' => 'Croatian',
    'hu' => 'Hungarian',
    'hy' => 'Armenian',

    'ia' => 'Interlingua',
    'id' => 'Indonesian',
    'ie' => 'Interlingue',
    'ik' => 'Inupiak',
    'is' => 'Icelandic',
    'it' => 'Italian',
    'iu' => 'Inuktitut',

    'ja' => 'Japanese',
    'jw' => 'Javanese',

    'ka' => 'Georgian',
    'kk' => 'Kazakh',
    'kl' => 'Greenlandic',
    'km' => 'Cambodian',
    'kn' => 'Kannada',
    'ko' => 'Korean',
    'ks' => 'Kashmiri',
    'ku' => 'Kurdish',
    'ky' => 'Kirghiz',

    'la' => 'Latin',
    'ln' => 'Lingala',
    'lo' => 'Laothian',
    'lt' => 'Lithuanian',
    'lv' => 'Latvian, Lettish',

    'mg' => 'Malagasy',
    'mi' => 'Maori',
    'mk' => 'Macedonian',
    'ml' => 'Malayalam',
    'mn' => 'Mongolian',
    'mo' => 'Moldavian',
    'mr' => 'Marathi',
    'ms' => 'Malay',
    'mt' => 'Maltese',
    'my' => 'Burmese',

    'na' => 'Nauru',
    'ne' => 'Nepali',
    'nl' => 'Dutch',
    'no' => 'Norwegian',

    'oc' => 'Occitan',
    'om' => '(Afan) Oromo',
    'or' => 'Oriya',

    'pa' => 'Punjabi',
    'pl' => 'Polish',
    'ps' => 'Pashto, Pushto',
    'pt' => 'Portuguese',

    'qu' => 'Quechua',

    'rm' => 'Rhaeto-Romance',
    'rn' => 'Kirundi',
    'ro' => 'Romanian',
    'ru' => 'Russian',
    'rw' => 'Kinyarwanda',

    'sa' => 'Sanskrit',
    'sd' => 'Sindhi',
    'sg' => 'Sangho',
    'sh' => 'Serbo-Croatian',
    'si' => 'Sinhalese',
    'sk' => 'Slovak',
    'sl' => 'Slovenian',
    'sm' => 'Samoan',
    'sn' => 'Shona',
    'so' => 'Somali',
    'sq' => 'Albanian',
    'sr' => 'Serbian',
    'ss' => 'Siswati',
    'st' => 'Sesotho',
    'su' => 'Sundanese',
    'sv' => 'Swedish',
    'sw' => 'Swahili',

    'ta' => 'Tamil',
    'te' => 'Telugu',
    'tg' => 'Tajik',
    'th' => 'Thai',
    'ti' => 'Tigrinya',
    'tk' => 'Turkmen',
    'tl' => 'Tagalog',
    'tn' => 'Setswana',
    'to' => 'Tonga',
    'tr' => 'Turkish',
    'ts' => 'Tsonga',
    'tt' => 'Tatar',
    'tw' => 'Twi',

    'ug' => 'Uighur',
    'uk' => 'Ukrainian',
    'ur' => 'Urdu',
    'uz' => 'Uzbek',

    'vi' => 'Vietnamese',
    'vo' => 'Volapuk',

    'wo' => 'Wolof',

    'xh' => 'Xhosa',

    'yi' => 'Yiddish',
    'yo' => 'Yoruba',

    'za' => 'Zhuang',
    'zh' => 'Chinese',
    'zu' => 'Zulu',
  );

  /**
   * Codes from ISO 3166
   *
   * http://ftp.ics.uci.edu/pub/ietf/http/related/iso3166.txt
   *
   * @var array
   */
  private static $countries = array(
    '*'  => 'all',
    'af' => 'Afghanistan',
    'al' => 'Albania',
    'dz' => 'Algeria',
    'as' => 'American samoa',
    'ad' => 'Andorra',
    'ao' => 'Angola',
    'ai' => 'Anguilla',
    'aq' => 'Antarctica',
    'ag' => 'Antigua and Barbuda',
    'ar' => 'Argentina',
    'am' => 'Armenia',
    'aw' => 'Aruba',
    'au' => 'Australia',
    'at' => 'Austria',
    'az' => 'Azerbaijan',

    'bs' => 'Bahamas',
    'bh' => 'Bahrain',
    'bd' => 'Bangladesh',
    'bb' => 'Barbados',
    'by' => 'Belarus',
    'be' => 'Belgium',
    'bz' => 'Belize',
    'bj' => 'Benin',
    'bm' => 'Bermuda',
    'bt' => 'Bhutan',
    'bo' => 'Bolivia',
    'ba' => 'Bosnia and Herzegowina',
    'bw' => 'Botswana',
    'bv' => 'Bouvet island',
    'br' => 'Brazil',
    'io' => 'British indian ocean territory',
    'bn' => 'Brunei darussalam',
    'bg' => 'Bulgaria',
    'bf' => 'Burkina faso',
    'bi' => 'Burundi',

    'kh' => 'Cambodia',
    'cm' => 'Cameroon',
    'ca' => 'Canada',
    'cv' => 'Cape verde',
    'ky' => 'Cayman islands',
    'cf' => 'Central african republic',
    'td' => 'Chad',
    'cl' => 'Chile',
    'cn' => 'China',
    'cx' => 'Christmas island',
    'cc' => 'Cocos (keeling) islands',
    'co' => 'Colombia',
    'km' => 'Comoros',
    'cg' => 'Congo',
    'ck' => 'Cook islands',
    'cr' => 'Costa rica',
    'ci' => 'Cote d\'ivoire',
    'hr' => 'Croatia',
    'cu' => 'Cuba',
    'cy' => 'Cyprus',
    'cz' => 'Czech republic',

    'dk' => 'Denmark',
    'dj' => 'Djibouti',
    'dm' => 'Dominica',
    'do' => 'Dominican republic',

    'tp' => 'East timor',
    'ec' => 'Ecuador',
    'eg' => 'Egypt',
    'sv' => 'El salvador',
    'gq' => 'Equatorial guinea',
    'er' => 'Eritrea',
    'ee' => 'Estonia',
    'et' => 'Ethiopia',

    'fk' => 'Falkland islands (malvinas)',
    'fo' => 'Faroe islands',
    'fj' => 'Fiji',
    'fi' => 'Finland',
    'fr' => 'France',
    'fx' => 'France, metropolitan',
    'gf' => 'French guiana',
    'pf' => 'French polynesia',
    'tf' => 'French southern territories',

    'ga' => 'Gabon',
    'gm' => 'Gambia',
    'ge' => 'Georgia',
    'de' => 'Germany',
    'gh' => 'Ghana',
    'gi' => 'Gibraltar',
    'gr' => 'Greece',
    'gl' => 'Greenland',
    'gd' => 'Grenada',
    'gp' => 'Guadeloupe',
    'gu' => 'Guam',
    'gt' => 'Guatemala',
    'gn' => 'Guinea',
    'gw' => 'Guinea-bissau',
    'gy' => 'Guyana',

    'ht' => 'Haiti',
    'hm' => 'Heard and mc donald islands',
    'hn' => 'Honduras',
    'hk' => 'Hong kong',
    'hu' => 'Hungary',

    'is' => 'Iceland',
    'in' => 'India',
    'id' => 'Indonesia',
    'ir' => 'Iran (islamic republic of)',
    'iq' => 'Iraq',
    'ie' => 'Ireland',
    'il' => 'Israel',
    'it' => 'Italy',

    'jm' => 'Jamaica',
    'jp' => 'Japan',
    'jo' => 'Jordan',

    'kz' => 'Kazakhstan',
    'ke' => 'Kenya',
    'ki' => 'Kiribati',
    'kp' => 'Korea, democratic people\'s republic of',
    'kr' => 'Korea, republic of',
    'kw' => 'Kuwait',
    'kg' => 'Kyrgyzstan',

    'la' => 'Lao people\'s democratic republic',
    'lv' => 'Latvia',
    'lb' => 'Lebanon',
    'ls' => 'Lesotho',
    'lr' => 'Liberia',
    'ly' => 'Libyan arab jamahiriya',
    'li' => 'Liechtenstein',
    'lt' => 'Lithuania',
    'lu' => 'Luxembourg',

    'mo' => 'Macau',
    'mk' => 'Macedonia, the former yugoslav republic of',
    'mg' => 'Madagascar',
    'mw' => 'Malawi',
    'my' => 'Malaysia',
    'mv' => 'Maldives',
    'ml' => 'Mali',
    'mt' => 'Malta',
    'mh' => 'Marshall islands',
    'mq' => 'Martinique',
    'mr' => 'Mauritania',
    'mu' => 'Mauritius',
    'yt' => 'Mayotte',
    'mx' => 'Mexico',
    'fm' => 'Micronesia, federated states of',
    'md' => 'Moldova, republic of',
    'mc' => 'Monaco',
    'mn' => 'Mongolia',
    'ms' => 'Montserrat',
    'ma' => 'Morocco',
    'mz' => 'Mozambique',
    'mm' => 'Myanmar',

    'na' => 'Namibia',
    'nr' => 'Nauru',
    'np' => 'Nepal',
    'nl' => 'Netherlands',
    'an' => 'Netherlands antilles',
    'nc' => 'New caledonia',
    'nz' => 'New zealand',
    'ni' => 'Nicaragua',
    'ne' => 'Niger',
    'ng' => 'Nigeria',
    'nu' => 'Niue',
    'nf' => 'Norfolk island',
    'mp' => 'Northern mariana islands',
    'no' => 'Norway',

    'om' => 'Oman',

    'pk' => 'Pakistan',
    'pw' => 'Palau',
    'pa' => 'Panama',
    'pg' => 'Papua new guinea',
    'py' => 'Paraguay',
    'pe' => 'Peru',
    'ph' => 'Philippines',
    'pn' => 'Pitcairn',
    'pl' => 'Poland',
    'pt' => 'Portugal',
    'pr' => 'Puerto rico',

    'qa' => 'Qatar',

    're' => 'Reunion',
    'ro' => 'Romania',
    'ru' => 'Russian federation',
    'rw' => 'Rwanda',

    'kn' => 'Saint kitts and nevis',
    'lc' => 'Saint lucia',
    'vc' => 'Saint vincent and the grenadines',
    'ws' => 'Samoa',
    'sm' => 'San marino',
    'st' => 'Sao tome and principe',
    'sa' => 'Saudi arabia',
    'sn' => 'Senegal',
    'sc' => 'Seychelles',
    'sl' => 'Sierra leone',
    'sg' => 'Singapore',
    'sk' => 'Slovakia (slovak republic)',
    'si' => 'Slovenia',
    'sb' => 'Solomon islands',
    'so' => 'Somalia',
    'za' => 'South africa',
    'es' => 'Spain',
    'lk' => 'Sri lanka',
    'sh' => 'St. helena',
    'pm' => 'St. pierre and miquelon',
    'sd' => 'Sudan',
    'sr' => 'Suriname',
    'sj' => 'Svalbard and jan mayen islands',
    'sz' => 'Swaziland',
    'se' => 'Sweden',
    'ch' => 'Switzerland',
    'sy' => 'Syrian arab republic',

    'tw' => 'Taiwan, province of china',
    'tj' => 'Tajikistan',
    'tz' => 'Tanzania, united republic of',
    'th' => 'Thailand',
    'tg' => 'Togo',
    'tk' => 'Tokelau',
    'to' => 'Tonga',
    'tt' => 'Trinidad and tobago',
    'tn' => 'Tunisia',
    'tr' => 'Turkey',
    'tm' => 'Turkmenistan',
    'tc' => 'Turks and caicos islands',
    'tv' => 'Tuvalu',

    'ug' => 'Uganda',
    'ua' => 'Ukraine',
    'ae' => 'United arab emirates',
    'gb' => 'United kingdom',
    'us' => 'United states',
    'um' => 'United states minor outlying islands',
    'uy' => 'Uruguay',
    'uz' => 'Uzbekistan',

    'vu' => 'Vanuatu',
    'va' => 'Vatican city state (holy see)',
    've' => 'Venezuela',
    'vn' => 'Viet nam',
    'vg' => 'Virgin islands (british)',
    'vi' => 'Virgin islands (u.s.)',

    'wf' => 'Wallis and futuna islands',
    'eh' => 'Western sahara',

    'ye' => 'Yemen',
    'yu' => 'Yugoslavia',

    'zr' => 'Zaire',
    'zm' => 'Zambia',
    'zw' => 'Zimbabwe',
  );

}
