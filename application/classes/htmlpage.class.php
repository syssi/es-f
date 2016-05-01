<?php
/**
 * Class that graps the html code of an url
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-54-g83ea36d 2011-01-17 20:17:17 +0100 $
 */
abstract class HTMLpage {

  /**
   *
   */
  public static $UserAgent = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36';

  /**
   *
   */
  public static $Debug = FALSE;

  /**
   *
   */
  public static $Retry = 3;

  /**
   *
   */
  public static function get( $url, &$err ) {
    $tempname = tempnam(ini_get('upload_tmp_dir'), 'curl.');
    self::get2file($url, $err, $tempname);
    $html = file_get_contents($tempname);
    @unlink($tempname);
    return $html;
  }

  /**
   *
   */
  public static function get2file( $url, &$err, $filename ) {
    self::init();

    $fh = @fopen($filename, 'w');

    self::$curl->setOpt(CURLOPT_URL, $url)
               ->setOpt(CURLOPT_FILE, $fh)
               ->setOpt(CURLOPT_REFERER, $url)
               ->setOpt(CURLOPT_USERAGENT, self::$UserAgent)
               ->setOpt(CURLOPT_VERBOSE, self::$Debug)
               ->setRetry(self::$Retry);

    if (self::$curl->exec($ret))
      $err = 'cUrl error: ' . self::$curl->error();

    @fclose($fh);

    // >> Debug
    Yryie::Info($url);
    if (self::$Debug) Yryie::Debug(self::$curl->getDebug());
    Yryie::Debug(self::$curl->info());
    // << Debug

    return $ret;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   *
   */
  private static $curl;

  /**
   *
   */
  private static function init() {
    if (self::$curl) return;

    self::$curl = new cURL;
    self::$curl->setOpt(CURLOPT_COOKIESESSION,  TRUE)
               ->setOpt(CURLOPT_FOLLOWLOCATION, TRUE)
               ->setOpt(CURLOPT_AUTOREFERER,    TRUE)
               ->setOpt(CURLOPT_HEADER,         FALSE)
               ->setOpt(CURLOPT_CONNECTTIMEOUT, Registry::get('cURL.ConnectionTimeOut'))
               ->setOpt(CURLOPT_TIMEOUT,        Registry::get('cURL.TimeOut'));
  }
}
