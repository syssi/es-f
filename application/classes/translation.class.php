<?php
/**
 * @package    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @since      File available since Release 0.0.1
 */
abstract class Translation {

  /**
   * Separator to split key into array
   *
   * @access public
   * @static
   */
  public static $NameSpaceSeparator = '::';

  /**
   * Define translations
   *
   * @param string $Namespace
   * @param array $translations Translations
   */
  public static function Define( $Namespace, $translations ) {
    #if (DEVELOP) self::WriteXML($Namespace, $translations, 1);

    $Namespace = strtoupper($Namespace);

/*
    $hash = md5(serialize($transaltions));
    $cache = Cache::getInstance();
    /// DebugStack::StartTimer(__METHOD__, __METHOD__, __METHOD__);
    if ($data = $cache->get($Namespace) AND $data[0] == $hash) {
      self::$Translation[$Namespace] = $data[1];
      /// DebugStack::Info('Transaltions for "'.$Namespace.'" cached.');
    } else {
*/

      foreach ($translations as $key=>$str) {
        if (is_array($str)) {
          // singular & plural
          foreach ($str as $id=>$s) $str[$id] = self::analyseFilter($s);
        } else {
          // single string
          $str = self::analyseFilter($str);
        }
        self::$Translation[$Namespace][strtoupper($key)] = $str;
      }

/*
      $cache->set($Namespace, array($hash, self::$Translation[$Namespace]));
      /// DebugStack::Info('Transaltions for "'.$Namespace.'" saved.');
    }
    /// DebugStack::StopTimer(__METHOD__);
*/
  }

  /**
   * Translate text id, considers singular/plural
   * looks for an array behind ID
   *
   * Uses all paramters after $id for sprintf()
   *
   * @param int $n Number to check for singular/plural
   * @param string $id
   * @return string
   * @uses getf
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
   * @return string
   * @uses getf
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
   * @var array
   */
  protected static $Translation = array();

  /**
   * @var array
   */
  protected static $Masks = array(
    array( '<',    '>'    ),
    array( "\x01", "\x02" ),
  );

  /**
   *
   */
  protected static function analyseFilter( $str ) {
    // test for eventual filter functions
    if (strpos($str, ':') === FALSE) return $str;

    // at least one collon...
    list($func, $str) = explode(':', $str, 2);

    // we possibly found a function
    $isHTML = FALSE;

    if ($func == 'html') {
      // text contains html tags
      $isHTML = TRUE;
    } elseif ($func == 'nl2br') {
      $str = nl2br($str);
      // un-HTML
      $str = str_replace(self::$Masks[0], self::$Masks[1], $str);
    } elseif ($func == 'p') {
      $str = str_replace("\n\n",'</p><p>',$str);
      $str = '<p>'.nl2br($str).'</p>';
      // un-HTML
      $str = str_replace(self::$Masks[0], self::$Masks[1], $str);
    } elseif ($func == 'file') {
      // include text file
      $str = file_exists($str)
           ? file_get_contents($str)
           : Messages::toStr('ERROR: Missing translation file ['.$str.']', Messages::ERROR);
      // file is by default interpreted as HTML!
      $isHTML = TRUE;
    } elseif (isset($func)) {
      // assume, we got an collon inside text
      return $func.':'.$str;
    }

    if (!$isHTML) {
      $str = htmlspecialchars($str, ENT_COMPAT, 'UTF-8');
      $str = str_replace('"','&quot;',$str);
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
   */
  private static function writeXML( $Namespace, $translations, $force=FALSE ) {
    $dbg = debug_backtrace();
    while (!strstr($dbg[0]['file'], 'language')) array_shift($dbg);
    $Namespace = strtolower($Namespace);

    $file = str_replace('.dev.php', '', $dbg[0]['file']);
    $l = substr($file, -6, -4);
    $xmlfile = dirname($file) . DIRECTORY_SEPARATOR . $Namespace . '.' . $l . '.xml';

#    _dbg($file);
#    _dbg($xmlfile);
#    unlink($xmlfile);
#    return;

    if (@filemtime($file) <= @filemtime($xmlfile) AND !$force) return;

    $ns = ucwords(strtolower($Namespace));
    // CamelCase help namespaces
    $ns = str_replace('help', 'Help', $ns);

    $data = self::nl('<?xml version="1.0" encoding="UTF-8" ?'.'>')
          . self::nl('<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -')
          . self::nl('  Don\'t "htmlspecialchar" your translation,')
          . self::nl('  just use <![CDATA[<text>]]> and NOT &lt;text&gt;')
          . self::nl()
          . self::nl('  Nouns ')
          . self::nl('  <text> default, noun is not required or >1')
          . self::nl('  <text count="0"> for quantity 0, e.g. "%1$d files deleted"')
          . self::nl('  <text count="1"> for quantity 1, e.g. "%1$d file deleted"')
          . self::nl('  For quantities >1, <text> will be used.')
          . self::nl('  If your language have other special cases, just declare')
          . self::nl('  <text count="10"> or what ever :-)')
          . self::nl('- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->')
          . self::nl('<translation>')
          . self::nl('  <namespace>'.$ns.'</namespace>', 2)
          . self::nl('  <language>'.$l.'</language>')
          . self::nl('  <author><![CDATA[Knut Kohl <knutkohl@users.sourceforge.net]]></author>');

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

      $data .= self::nl('  <string>');
      $data .= self::nl('    <id>'.$key.'</id>');

      if ($t['s'][0]) $data .= self::nl('    <type>'.$t['s'][0].'</type>');

      if (strstr($t['s'][1],'%') OR isset($t['p'][1]) AND strstr($t['p'][1],'%'))
        $data .= self::nl('    <description>%$1s - ...</description>');

      $s = ($t['s'] && $t['p']) ? ' count="1"' : '' ;

      $data .= '    <text'.$s.'>';
      // make CDATA if string contains at least one of: < > & " '
      $data .= preg_match('~[<>&"\']~', $t['s'][1])
             ? '<![CDATA[' . $t['s'][1] . ']]>'
             : $t['s'][1];
      $data .= self::nl('</text>');

      if ($t['p']) {
        $data .= '    <text>';
        // make CDATA if string contains at least one of: < > & " '
        $data .= preg_match('~[<>]~', $t['p'][1])
               ? '<![CDATA[' . $t['p'][1] . ']]>'
               : $t['p'][1];
        $data .= self::nl('</text>');
      }

      $data .= self::nl('  </string>', 2);
    }

    $data .= self::nl('</translation>');

    file_put_contents($xmlfile, $data);
    #unlink($file);
  }

  /**
   *
   */
  private static function nl( $text='', $count=1 ) {
    for ($i=0; $i<$count; $i++) $text .= "\n";
    return $text;
  }

}
