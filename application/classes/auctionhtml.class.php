<?php
/**
 * Read ebay auction page HTML code for an auction
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-79-g85bf9fc 2011-02-15 18:24:07 +0100 $
 * @revision   $Rev$
 */
abstract class AuctionHTML {

  /**
   * Clear file buffer
   *
   * @param string $item
   * @param string $urlId
   */
  public static function clearBuffer( $item='*', $urlId='*' ) {
    $files = np('"%s/"%s.%s".html"', TEMPDIR, $item, $urlId);
    if (Exec::getInstance()->Remove($files, $res)) Messages::Info($res);
  }

  /**
   * Get HTML code for an item according to provided URLs
   *
   * @param string $item
   * @param array $urls
   * @param array &$err
   * @param array $urlId
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
    Yryie::Info(sprintf('Read auction HTML for item "%s", Id "%s"', $item, $urlId));
    // << Debug

    if (file_exists($file)) {
      // >> Debug
      Yryie::Info('Found cached file = '.$file);
      // << Debug
      $html = file_get_contents($file);
    } else {
      // >> Debug
      Yryie::Info('Read from ebay');
      Yryie::Info('URL: '.$url);
      Yryie::Info('Cache file: '.$file);
      // << Debug

      $html = HTMLpage::get2file($url, $err, $file);
      if ($err) {
        Messages::Error($err);
      } else {
        $html = file_get_contents($file);

        /// $l1 = strlen($html);

        // remove some uninteresting stuff
        // strip out comments
        $html = preg_replace('~<!--.*?-->~is', '', $html);
        // strip out cdata
        $html = preg_replace('~<!\[CDATA\[.*?\]\]>~is', '', $html);
        // strip out <style> tags
        $html = preg_replace('~<\s*style[^>]*[^/]>.*?<\s*/\s*style\s*>~is', '', $html);
        $html = preg_replace('~<\s*style\s*>.*?<\s*/\s*style\s*>~is', '', $html);
        // strip out <script> tags
        $html = preg_replace('~<\s*script[^>]*[^/]>.*?<\s*/\s*script\s*>~is', '', $html);
        $html = preg_replace('~<\s*script\s*>.*?<\s*/\s*script\s*>~is', '', $html);
        // strip out preformatted tags
        $html = preg_replace('~<\s*(?:code)[^>]*>.*?<\s*/\s*(?:code)\s*>~is', '', $html);
        // strip out server side scripts
        $html = preg_replace('~<\?.*?\?'.'>~s', '', $html);
        // strip smarty scripts
        $html = preg_replace('~\{\w.*?\}~s', '', $html);
        // empty tag delimiters
        $html = preg_replace('~</?(table|tbody|tr|td|div|p|span|font)>~s', '', $html);
        // replace some special chars
        $html = str_replace(array('&#163;', '&#165;'),
                            array('GBP',    'Yen'),
                            $html);

        /* ///
          if ($l1) {
            $l2 = strlen($html);
            Yryie::Info('File size reduced from %s Bytes to %s Bytes == %.2f%%',
                             number_format($l1,0,',','.'),
                             number_format($l2,0,',','.'), $l2/$l1*100);
          }
        /// */

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
   * Clear buffer on 1st call
   *
   * @var bool $FirstCall
   */
  private static $FirstCall = TRUE;

  /**
   * Don't initiate the class
   */
  private final function __construct() {}

}
