<?php
/**
 * Read ebay auction page html code for an auction
 *
 * @package    AuctionHTML
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 */
abstract class AuctionHTML {

  /**
   *
   */
  public static function clearBuffer( $item='*', $urlId='*' ) {
    $files = np('"%s/"%s.%s".html"', TEMPDIR, $item, $urlId);
    if (Exec::getInstance()->Remove($files, $res)) Messages::addInfo($res);
  }

  /**
   *
   */
  public static function &getHTML( $item, $urls, &$err, $urlId='default' ) {
    if (self::$FirstCall) {
      // remove old HTMLs only ONCE each script run
      self::clearBuffer();
      self::$FirstCall = FALSE;
    }

    // check for valid end existing URL id
    if (empty($urls[$urlId])) $urlId = 'default';
    $url = sprintf($urls[$urlId], $item);

    $file = sprintf('%s/%s.%s.%s.html', TEMPDIR, $item, $urlId, md5($url));

    // >> Debug
    DebugStack::Info(sprintf('AuctionHTML::getHTML : Read auction HTML for item "%s", Id "%s"', $item, $urlId));
    // << Debug

    if (file_exists($file)) {
      // >> Debug
      DebugStack::Info('AuctionHTML::getHTML : Found cached file = '.$file);
      // << Debug
      $html = file_get_contents($file);
    } else {
      // >> Debug
      DebugStack::Info('AuctionHTML::getHTML : Read from ebay');
      DebugStack::Info('URL: '.$url);
      DebugStack::Info('Cache file: '.$file);
      // << Debug

      $html = HTMLpage::get2file($url, $err, $file);
      if ($err) {
        Messages::addError($err);
      } else {
        $html = file_get_contents($file);
        // remove some uninteresting stuff
        $html = preg_replace('~<script.*?</script>~si', '', $html);
        $html = preg_replace('~<noscript>.*?</noscript>~si', '', $html);
        // add fetched item page URL
        $html = "<!-- $url -->\n" . $html;
        // save content to file for later debugging
        $file_ts = str_replace('.html', '-'.date('Ymd-His').'.html', $file);
        Exec::getInstance()->Copy($file, $file_ts, $dummy);
      }
    }
    return $html;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   *
   */
  private static $FirstCall = TRUE;

  /**
   *
   */
  private final function __construct() {}

}
