<?php

/**
 * Class that graps the html code of an url
 */
abstract class HTMLpage {

  /**
   *
   */
  public static $UserAgent = 'User-Agent: Mozilla/5.0(X11; U; Linux i686; en-US; rv:1.2.1) Gecko/20021204';

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
    DebugStack::Info($url);
    if (self::$Debug) DebugStack::Debug(self::$curl->getDebug());
    DebugStack::Debug(self::$curl->info());
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
