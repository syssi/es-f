<?php
/**
 * Translation / I18N handling
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
abstract class Translation {

  /**
   * Separator to split key into array
   *
   * @var string $NamespaceSeparator
   */
  public static $NamespaceSeparator = '::';

  /**
   * Apply this default filter, if no filter is defined
   *
   * @var string $DefaultFilter
   */
  public static $DefaultFilter = 'escape';

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
      $namespace = $tmxdata[0];
      foreach ($tmxdata[1] as $key => $data) {
        if (isset($data['']) AND $str = $data['']) {
          $filter = isset($data['x-filter']) ? $data['x-filter'] : '';
          self::set($namespace, $key, $str, $filter);
        }
      }
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  /**
   * Set single translation definition
   *
   * @param string $namespace
   * @param string $key Translation key
   * @param string $str Message text
   * @param string $filter Filter1[|Filter2]...
   */
  public static function set( $namespace, $key, $str, $filter='' ) {
    $filter = strtolower($filter);
    if (empty($filter)) $filter = self::$DefaultFilter;
    if (!empty($filter)) {
      // applay all defined filters for this string
      foreach(explode('|', $filter) as $f) {
        if (!isset(self::$Filter[$f])) {
          // try to register, if not yet done
          $class = 'Translation_Filter_'.$f;
          self::RegisterFilter(new $class, $f);
        }
        self::$Filter[$f]->process($str, $namespace, $key);
      }
    }
    self::$Translation[strtoupper($namespace)][strtoupper($key)] = $str;
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

    list($namespace, $key) = explode(self::$NamespaceSeparator, $ustr, 2);

    if (isset(self::$Translation[$namespace][$key])) {
      // pointer to content
      $str =& self::$Translation[$namespace][$key];

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
    list($namespace, $key) = explode(self::$NamespaceSeparator, $ustr, 2);
    $return = $nvl;
    if (isset(self::$Translation[$namespace][$key])) {
      $args[0] = self::$Translation[$namespace][$key];
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

  /**
   * Register a filter
   *
   * @param instance $filter Instance of Translation_Filter
   */
  public static function RegisterFilter( Translation_Filter $filter ) {
    $name = preg_replace('~.*?_~', '', get_class($filter));
    self::$Filter[strtolower($name)] = $filter;
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
   * Formating filters
   * @var array $Filter
   */
  protected static $Filter = array();

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

}
