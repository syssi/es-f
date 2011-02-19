<?php
/**
 * Translation / I18N handling
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
abstract class Translation {

  /**
   * Separator to split key into array
   *
   * @var string $NameSpaceSeparator
   */
  public static $NameSpaceSeparator = '::';

  /**
   * Define translations
   *
   * @param string $file TMX file to load
   * @param array $language Language to use from TMX file
   * @param Cache $cache Cache instance
   */
  public static function LoadTMXFile( $file, $language, Cache $cache ) {
    try {
      /* ///
      $id = str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $file);
      $cached = TRUE;
      Yryie::StartTimer($id, $id, 'parse TMX file');
      /// */
      while ($cache->save($file, $tmxdata, -File::MTime($file))) {
        /* ///
        Yryie::Info('Parse: '.$file);
        $cached = FALSE;
        /// */
        $tmx = new TMX($file, $language);
        $header = $tmx->getHeader();
        if (!isset($header['x-namespace']))
          throw new Exception('Missing prop: x-Namespace in header of '.$file);
        $tmxdata = array($header['x-namespace'], $tmx->getData());
        unset($tmx);
      }
      /* ///
      if ($cached) Yryie::Info('Cached: '.$file);
      Yryie::StopTimer($id);
      /// */
      foreach ($tmxdata[1] as $key => $data) {
        if (isset($data['']) AND $str = $data['']) {
          if (isset($data['x-format']))
            $str = self::applyFormat($str, $data['x-format']);
          self::set($tmxdata[0], $key, $str);
        }
      }
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  /**
   * Define translations
   *
   * @param string $Namespace
   * @param array $translations Translations
   */
  public static function Define( $Namespace, $translations ) {
    #if (DEVELOP) self::writeTMX($Namespace, $translations, 1);
    /// Yryie::StartTimer(__METHOD__, __METHOD__, __METHOD__);
    foreach ($translations as $key=>$str) {
      if (is_array($str)) {
        // singular & plural
        foreach ($str as $id=>$s) {
          $format = self::getFormat($s);
          $str[$id] = self::applyFormat($s, $format);
        }
      } else {
        // single string
        $format = self::getFormat($str);
        $str = self::applyFormat($str, $format);
      }
      self::set($Namespace, $key, $str);
    }
    /// Yryie::StopTimer(__METHOD__);
  }

  /**
   * Set single translation definition
   *
   * @param string $Namespace
   * @param string $key Translation
   * @param string $translation Translation
   */
  public static function set( $Namespace, $key, $translation ) {
    self::$Translation[strtoupper($Namespace)][strtoupper($key)] = $translation;
  }

  /**
   * Translate text id, considers singular/plural
   * looks for an array behind ID
   *
   * Uses all paramters after $id for sprintf()
   *
   * @see getf
   * @return string
   */
  public static function get() {
    if (!func_num_args()) return;

    $args = func_get_args();
    if (is_array($args[0])) $args = $args[0];
    $ustr = strtoupper($args[0]);

    // first argument after text id is interpreted as amount if integer
    $n = (isset($args[1]) AND $args[1]*1==$args[1]) ? (int)$args[1] : 0;

    list($NameSpace, $key) = explode(self::$NameSpaceSeparator, $ustr, 2);

    if (isset(self::$Translation[$NameSpace][$key])) {
      // pointer to content
      $str =& self::$Translation[$NameSpace][$key];

      if (is_array($str)) {
        if ($n == 1 AND isset($str[0])) {
          // singular
          $args[0] = $str[0];
        } elseif (isset($str[1])) {
          // plural
          $args[0] = $str[1];
        }
      } else {
        // default
        $args[0] = $str;
      }
      // translate
      return self::getf($args);
    }

    // not found in namespace or namespace not found
    return '[['.$args[0].']]';
  }

  /**
   * Translate text id with default value
   *
   * Uses all paramters after $id for sprintf()
   *
   * LAST PARAMETER have to be the default value.
   *
   * EXCEPT: If ONLY one paramter given, default value will be '' (empty string)!
   *
   * @see getf
   * @return string
   */
  public static function getNvl() {
    // last paramter HAVE TO BE the default value!!
    $args = func_get_args();
    $nvl = (count($args) > 1) ? array_pop($args) : '';
    $ustr = strtoupper($args[0]);
    list($NameSpace, $key) = explode(self::$NameSpaceSeparator, $ustr, 2);
    $return = $nvl;
    if (isset(self::$Translation[$NameSpace][$key])) {
      $args[0] = self::$Translation[$NameSpace][$key];
      $return = self::getf($args);
    }
    return $return;
  }

  /**
   * Return all translations
   *
   * @return array
   */
  public static function getAll() {
    return self::$Translation;
  }

  //---------------------------------------------------------------------------
  // PROTECTED
  //---------------------------------------------------------------------------

  /**
   * Internal data buffer
   * @var array $Translation
   */
  protected static $Translation = array();

  /**
   * Masks for HTML transformation
   * @var array $Masks
   */
  protected static $Masks = array(
    array( '<',    '>'    ),
    array( "\x01", "\x02" ),
  );

  /**
   * Extract format from definition
   *
   * @param str &$str
   */
  protected static function getFormat( &$str ) {
    // test for eventual filter functions
    if (strpos($str, ':') === FALSE) return;

    // at least one collon...
    list($fmt, $new) = explode(':', $str, 2);
    
    if (in_array($fmt, array('html', 'nl2br', 'p', 'file'))) {
      // valid format
      $str = $new;
      return $fmt;
    }
  }

  /**
   * Convert into destination format, e.g. escape if required
   *
   * @param string $str
   * @param string $format
   */
  protected static function applyFormat( $str, $format ) {

    if ($format == '' OR $format == 'plain') return $str;

    $isHTML = FALSE;

    switch ($format) {
      case 'html':
        // text contains html tags
        $isHTML = TRUE;
        break;
      case 'nl2br':
        $str = nl2br($str);
        // un-HTML
        $str = str_replace(self::$Masks[0], self::$Masks[1], $str);
        break;
      case 'p':
        $str = str_replace("\n\n",'</p><p>',$str);
        $str = '<p>'.nl2br($str).'</p>';
        // un-HTML
        $str = str_replace(self::$Masks[0], self::$Masks[1], $str);
        break;
      case 'file':
        // include text file
        $str = file_exists($str)
             ? file_get_contents($str)
             : Messages::toStr('ERROR: Missing translation file ['.$str.']', Messages::ERROR);
        // file is by default interpreted as HTML!
        $isHTML = TRUE;
        break;
    } // switch

    if (!$isHTML) {
      $str = htmlspecialchars($str, ENT_COMPAT, 'UTF-8');
      $str = str_replace('"', '&quot;', $str);
    }

    // re-HTML
    return str_replace(self::$Masks[1], self::$Masks[0], $str);
  }

  /**
   * Translate direct a text string
   *
   * Uses all paramters after $id for sprintf()
   *
   * @return string
   */
  protected static function getf() {
    $args = func_get_args();
    // called internally?
    if (is_array($args[0])) $args = $args[0];
    $str = array_shift($args);
    $str = vsprintf($str, $args);
    $str = str_replace("\r", '', $str);
    $str = str_replace(array("\n", "\t"), ' ', $str);
    return $str;
  }

  /**
   *
   */
  protected static function analyseFilter2( $str ) {
    // test for eventual filter functions
    if (strpos($str, ':') === FALSE) return array('', trim($str));

    // at least one collon...
    list($func, $str) = explode(':', $str, 2);

    if ($func == 'html' OR $func == 'nl2br' OR $func == 'p' OR $func == 'file')
      return array($func, trim($str));

    // assume, we got an collon inside text
    return array('', trim($func.':'.$str));
  }

  //---------------------------------------------------------------------------
  // PRIVATE
  //---------------------------------------------------------------------------

  /**
   *
   * /
  private static function writeTMX( $Namespace, $translations, $force=FALSE ) {
    $dbg = debug_backtrace();
    while (!strstr($dbg[0]['file'], 'language')) array_shift($dbg);
    $Namespace = strtolower($Namespace);

    $file = str_replace('.dev.php', '', $dbg[0]['file']);
    $l = substr($file, -6, -4);
    $xmlfile = dirname($file) . DIRECTORY_SEPARATOR . $Namespace . '.' . $l . '.tmx';

#    _dbg($file);
#    _dbg($xmlfile);
#    unlink($xmlfile);
#    return;

    if (@filemtime($file) <= @filemtime($xmlfile) AND !$force) return;

    $ns = ucwords(strtolower($Namespace));
    // CamelCase help namespaces
    $ns = str_replace('help', 'Help', $ns);

    $data = self::nl('<?xml version="1.0" encoding="UTF-8" ?'.'>')
          . self::nl('<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -')
          . self::nl('  Don\'t "htmlspecialchar" your translation, please use <![CDATA[text...]]>')
          . self::nl()
          . self::nl('  Nouns')
          . self::nl('  tuid="name"   - default, noun is not required or >1')
          . self::nl('  tuid="name-0" - for quantity 0, e.g. "%1$d files deleted"')
          . self::nl('  tuid="name-1" - for quantity 1, e.g. "%1$d file deleted"')
          . self::nl()
          . self::nl('  If your language have very special cases, just declare')
          . self::nl('  tuid="name-10"  or what ever :-)')
          . self::nl(' - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->')
          . self::nl('<tmx version="1.4">')
          . self::nl('  <header')
          . self::nl('   creationtool="es-f"')
          . self::nl('   creationtoolversion="1"')
          . self::nl('   datatype="PlainText"')
          . self::nl('   segtype="sentence"')
          . self::nl('   adminlang="EN"')
          . self::nl('   srclang="EN"')
          . self::nl('   o-tmf="ABC"')
          . self::nl('   changeid="Knut Kohl / knutkohl@users.sourceforge.net"')
          . self::nl('   changedate="'.date('Ymd\THis\Z').'"')
          . self::nl('   o-encoding="UTF-8"')
          . self::nl('  >')
          . self::nl('    <prop type="x-Namespace">'.$ns.'</prop>')
          . self::nl('  </header>')
          . self::nl('  <body>');

    foreach ($translations as $key=>$str) {
      if (is_array($str)) {
        // singular & plural
        $t['s'] = self::analyseFilter2($str[0]);
        $t['p'] = self::analyseFilter2($str[1]);
      } else {
        // single / singular only
        $t['s'] = self::analyseFilter2($str);
        $t['p'] = '';
      }

      if ($t['p']) {
        $data .= self::tu($key,      $l, $t['s'][0], $t['p'][1]);
        $data .= self::tu($key.'-1', $l, $t['s'][0], $t['s'][1], 2);
      } else {
        $data .= self::tu($key,      $l, $t['s'][0], $t['s'][1], 2);
      }
    }

    $data .= self::nl('  </body>');
    $data .= self::nl('</tmx>');

    file_put_contents($xmlfile, $data);
    #unlink($file);
  }

  /**
   *
   * /
  private static function tu( $key, $lang, $type, $txt, $nl=1 ) {
    if (preg_match('~[<>&"\']~', $txt)) $txt = '<![CDATA['.$txt.']]>';
    $s  = self::nl('    <tu tuid="'.$key.'">');

    if ($type) $s .= self::nl('      <prop type="x-Format">'.$type.'</prop>');
    if (strstr($txt, '%')) $s .= self::nl('      <note>%$1s - ...</note>');

    return $s
         . self::nl('      <tuv xml:lang="'.strtoupper($lang).'">')
         . self::nl('        <seg>'.$txt.'</seg>')
         . self::nl('      </tuv>')
         . self::nl('    </tu>', $nl);
  }

  /**
   *
   * /
  private static function nl( $text='', $count=1 ) {
    for ($i=0; $i<$count; $i++) $text .= "\n";
    return $text;
  }
  /* */

}
