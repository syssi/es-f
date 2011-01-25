<?php
/**
 * Check application version against remote version file
 *
 * @ingroup    CheckVersion
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2010-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.0-15-g82a2021 - Sun Dec 19 20:39:56 2010 +0100 $
 */
class CheckVersion {

  /**
   * ID to cache in session
   */
  const SESSIONID = '.vchk';

  /**
   * Class constructor
   *
   * Proposed file format
   * Works with the defaults for getVersion(), getDate() and getComment()
   * line 1: version  (required, e.g. 1.2.3)
   * line 2: date     (optional, e.g. 2010-12-01)
   * line 3: comment  (optional, e.g. Feature release)
   * line n: comment...
   *
   * @param $cUrl cURL cURL instance
   * @param $URL string URL of version file
   * @param $ttl int Time to live of fetched data in sec.
   *                 -1: expires never during a session
   *                  0: won't cache data at all, reread on each page load!
   *                     Use this ONLY for debugging purposes!
   * @throws CheckVersionException In case of $Config error
   */
  public function __construct( cURL $cUrl, $URL, $ttl=-1 ) {
    $this->curl = $cUrl;
    $this->url = $URL;
    if ($this->url == '')
      throw new CheckVersionException(__CLASS__.': Missing "URL" option in $Config parameter!');
    $this->ttl = (int)$ttl;
  }

  /**
   * Get all new application version data
   *
   * @throws CheckVersionException In case of error during getting update informations
   * @return array
   */
  public function getAll() {
    if (session_id() == '') session_start();

        //    expiration timestamp           fetched data
    if (isset($_SESSION[self::SESSIONID][0], $_SESSION[self::SESSIONID][1]) AND
        // expire never   OR expire after now
        ($this->ttl === -1 OR $_SESSION[self::SESSIONID][0]>time())) {
      // Valid data in this session
      $data = $_SESSION[self::SESSIONID][1];
    } else {
      // Fetch data
      $ret = $this->curl
           ->setOpt(CURLOPT_URL,            $this->url)
           ->setOpt(CURLOPT_HEADER,         FALSE)
           ->setOpt(CURLOPT_RETURNTRANSFER, TRUE)
           ->exec($data);

      $data = explode("\n", $data);

      if ($ret !== 0 OR !$data)
        throw new CheckVersionException($this->curl->error());

      // Store data into session
      if ($this->ttl !== 0)
        $_SESSION[self::SESSIONID] = array( time()+$this->ttl, $data );
    }
    return $data;
  }

  /**
   * Get new application version
   *
   * @param $line int Line in version file
   * @return string
   */
  public function Version( $line=1 ) {
    $data = $this->getAll();
    return isset($data[$line-1]) ? isset($data[$line-1]) : '';
  }

  /**
   * Get application release date
   *
   * @param $line int Line in version file
   * @return string
   */
  public function Date( $line=2 ) {
    $data = $this->getAll();
    return isset($data[$line-1]) ? isset($data[$line-1]) : '';
  }

  /**
   * Get an array with the application release comment line(s)
   *
   * @param $line int Starting line in version file
   * @return array
   */
  public function Comment( $line=3 ) {
    $data = $this->getAll();
    return isset($data[$line-1]) ? array_slice($data, $line-1) : array();
  }

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  /**
   * cURL instance
   *
   * @var cURL $curl
   */
  protected $curl;

  /**
   * URL to fetch from
   *
   * @var string $url
   */
  protected $url;

  /**
   * Time to life for fetched data
   *
   * @c 0 expires never
   *
   * @var int $ttl
   */
  protected $ttl = 0;

}

/**
 * Exception for CheckVersion
 *
 * @ingroup CheckVersion
 */
class CheckVersionException extends Exception {}
