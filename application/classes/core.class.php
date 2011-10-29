<?php
/**
 * Class Core
 *
 * Core application functions
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-77-gc4bf735 2011-02-13 21:51:53 +0100 $
 * @revision   $Rev$
 */
abstract class Core {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Core cache instance
   *
   * @var Cache $Cache
   */
  public static $Cache;

  /**
   * Core crypter instance
   *
   * @var Crypter $Crypter
   */
  public static $Crypter;

  /**
   * Build an url.
   *
   * Use urlrewrite plugin if defined.
   *
   * @param array $options
   * @return string
   */
  public static function URL( $options=array() ) {
    // skip, if URL is still known...
    if (!empty($options['url'])) return $options['url'];

    // remove empty option parts
    foreach ($options as $key=>$value) if ($value=='') unset($options[$key]);

    // map some defaults
    $options = array_merge(array(
      'module' => Registry::get('esf.Module'),
      'action' => NULL,
      'params' => array(),
      'anchor' => NULL,
    ), $options);

    // use session id in url if cookies disabled
    if (defined('SID') AND SID) $options['params'][session_name()] = session_id();

    Event::Process('URLRewrite', $options);

    if (!empty($options['url'])) return $options['url'];

    $url = $_SERVER['PHP_SELF'].'?module='.$options['module'];
    if ($options['action']) $url .= '&action='.$options['action'];
    if (count($options['params'])) $url .= '&' . http_build_query($options['params']);
    if ($options['anchor']) $url .= '#'.$options['anchor'];

    return $url;
  }

  /**
   * Build HTML link
   *
   * @param string $url URL
   * @param string $text Text to show, default is the URL
   * @param string $title Title tag of link
   * @return string
   */
  public static function Link( $url, $text='', $title='' ) {
    if (empty($text)) $text = $url;
    return sprintf('<a href="%1$s" title="%3$s">%2$s</a>', $url, $text, $title);
  }

  /**
   * Build email, as link if requested
   *
   * @param string $email Email address
   * @param string $name Email name, use address if not set
   * @param boolean $asLink Return as mailto: link
   * @param array $headers Additional headers like subject
   * @return string
   */
  public static function Email( $email, $name='', $asLink=FALSE, $headers=array() ) {
    $return = $email;
    if (!empty($name)) $return = '"'.$name.'" <'.$return.'>';

    if ($asLink) {
      foreach ($headers as $key => $val) {
        $headers[$key] = $key . '=' . rawurlencode($val);
      }
      $return = sprintf('<a href="mailto:%s?%s">%1$s</a>',
                        htmlspecialchars($return),
                        htmlspecialchars(implode('&',$headers)));
    }

    return $return;
  }

  /**
   * ISO 8859-1 to UTF-8 conversion
   *
   * @source http://www.php.net/manual/en/function.iconv.php#43463
   *
   * @param string $text Text to convert
   * @param string $charset Convert from charset
   * @return string Converted text
   */
  public static function toUTF8( $text, $charset='ISO-8859-1' ) {
    if (function_exists('utf_encode'))
      $text = utf8_encode($text);
    elseif (function_exists('iconv'))
      $text = iconv('ISO-8859-1', 'UTF-8', $text);
    else
      $text = preg_replace("~([\x80-\xFF])~e",
                           "chr(0xC0|ord('\\1')>>6).chr(0x80|ord('\\1')&0x3F)",
                           $text);
    return $text;
  }

  /**
   * UTF-8 to ISO 8859-1 conversion
   *
   * @source http://www.php.net/manual/function.iconv.php#43463
   *
   * @param string $text Text to convert from UTF-8 to ISO-8859-1
   */
  public static function fromUTF8( $text ) {
    if (function_exists('utf_decode'))
      $text = utf8_decode($text);
    elseif (function_exists('iconv'))
      $text = iconv('UTF-8', 'ISO-8859-1', $text);
    else
      $text = preg_replace("~([\xC2\xC3])([\x80-\xBF])~e",
                           "chr(ord('\\1')<<6&0xC0|ord('\\2')&0x3F)",
                           $text);
    return $text;
  }

  /**
   * Own session handling
   *
   * @param bool $regenerate Force restart of session, e.g. in case of logout
   */
  public static function StartSession( $regenerate=FALSE ) {
    /// Session::$Debug = TRUE;
    /// Session::$Messages = array();
    if (!Session::Active()) {
      // Session not started yet
      Event::ProcessInform('InitSession');
      Session::Start(Registry::get('SessionName'), Cookie::get('ttl'));
    }
    if ($regenerate) Session::regenerate(TRUE);
    /// Yryie::Info('Session -> Debug');
    /// if (Yryie::Active()) foreach ((array)Session::$Messages as $msg) Yryie::Info($msg);
  }

  /**
   * Check request method
   *
   * @return bool
   */
  public static function isPost() {
    return (isset($_SERVER['REQUEST_METHOD']) AND
            strtoupper($_SERVER['REQUEST_METHOD']) == 'POST');
  }

  /**
   * Forward to another module and/or action without page relaod
   *
   * @param string $module Module
   * @param string $action Module action
   * @param string $params Additional parameters
   */
  public static function Forward( $module=NULL, $action=NULL, $params=array() ) {
    if (isset($params['module'])) {
      $module = $params['module'];
      unset($params['module']);
    }
    if (isset($params['action'])) {
      $action = $params['action'];
      unset($params['action']);
    }
    $do['module'] = $module;
    $do['action'] = $action;

    Registry::set('esf.module', ($do['module'] ? $do['module'] : STARTMODULE));
    Registry::set('esf.action', ($do['action'] ? $do['action'] : 'index'));

    $do = array_merge($params, $do);
    unset($do['module'], $do['action']);
    foreach ($do as $key => $val) $_REQUEST[$key] = $val;
  }

  /**
   * Redirect to another module and/or action with page reload
   *
   * @param string $url
   */
  public static function Redirect( $url ) {
    Event::ProcessInform('Redirect', $url);
    Session::close();
    if (!headers_sent()) {
      Header('Location: ' . str_replace('&amp;', '&', $url));
      exit;
    } else {
      die('<p><strong style="font-family:monospace">'
         .'Ups, redirect not possible, HTML still sent (debug mode?), '
         .'please <a href="'.$url.'">click to process...</a></strong></p>');
    }
  }

  /**
   * Find all installed layouts of given module/plugin
   *
   * @param mixed $types
   * @param string $area
   * @return array
   */
  public static function getLayouts( $types=NULL, $area='*' ) {
    if (!isset($types)) $types = esf_Extensions::$Types;
    if (!is_array($types)) $types = array($types);

    $layouts = array();
    foreach ($types as $type) {
      foreach (glob($type.DIRECTORY_SEPARATOR.$area.DIRECTORY_SEPARATOR.'layout'.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR) as $dir) {
        if (!file_exists($dir.DIRECTORY_SEPARATOR.'.disabled')) {
          $dir = basename($dir);
          if ($dir != 'doc') $layouts[$dir] = ucwords($dir);
        }
      }
    }
    return $layouts;
  }

  /**
   * Cover stripslashes(), work with arrays, also recursiv
   *
   * @param mixed &$var Variable to strip slashes
   */
  public static function StripSlashes( &$var ) {
    if (!get_magic_quotes_gpc()) return;

    if (!is_array($var))
      $var = stripslashes($var);
    else
      foreach (array_keys($var) as $key) self::StripSlashes($var[$key]);
  }

  /**
   * Transform extension CHANGELOG file to HTML code
   *
   * @param string $file If not exists, return empty string
   * @return string
   */
  public static function ChangeLog2TplData( $file ) {
    if (!file_exists($file)) return '';

    $ChangeLog = $changes = '';
    $ul = FALSE;

    foreach ((array)file($file) as $line) {
      $line = trim($line);
      if (empty($line)) continue;

      switch (TRUE) {
        case preg_match('~^\s*Version\s+([\d.]+)\s*$~i', $line, $args):
          $ChangeLog[$args[1]]['VERSION'] = $line;
          // get pointer to actual changes entry
          $changes =& $ChangeLog[$args[1]]['CHANGES'];
          $ul = FALSE;
          break;
        case preg_match('~^--+$~', $line, $args):
          // do nothing
          break;
        case preg_match('~^-(.*?)$~', $line, $args):
          if (!$ul) {
            $changes .= '<ul>';
            $ul = TRUE;
          }
          $changes .= '<li>'.trim($args[1]).'</li>';
          break;
        default:
          if ($ul) {
            $changes .= '</ul>'."\n";
            $ul = FALSE;
          }
          $changes .= '<strong>'.$line.'</strong><br>'."\n";
          break;
      }
    }
    return $ChangeLog;
  }

  /**
   * Include files according to scope and pattern
   *
   * @param string|array $scopes Module,Plugin
   * @param string|array $patterns File patterns
   * @param bool $force Force load
   */
  public static function IncludeSpecial( $scopes, $patterns, $force=FALSE ) {
    if (!is_array($scopes)) $scopes = array($scopes);
    if (!is_array($patterns)) $patterns = array($patterns);

    // >> Debug
    Yryie::Info(sprintf('(%s)'.DIRECTORY_SEPARATOR.'(%s)', implode('|',$scopes), implode('|',$patterns)));
    // << Debug

    foreach ($scopes as $scope) {
      $chk4User = (!$force AND ($scope == esf_Extensions::MODULE));
      switch ($scope) {
        // --------------------
        case esf_Extensions::MODULE :
        case esf_Extensions::PLUGIN :
          foreach (esf_Extensions::getExtensions($scope) as $ext) {
            /**
             * - load all plugin files if enabled
             * - load all module files if enabled and not login required or valid user
             */
            if (esf_Extensions::checkState($scope, $ext, esf_Extensions::BIT_ENABLED) AND
                (!$chk4User OR
                 !Registry::get('Module.'.$ext.'.LoginRequired') OR
                 esf_User::isValid())) {
              $path = sprintf(BASEDIR.'%1$s%2$s%1$s%3$s%1$s', DIRECTORY_SEPARATOR, $scope, $ext);
              foreach ($patterns as $pattern) {
                $pattern = str_replace('/', DIRECTORY_SEPARATOR, $pattern);
                foreach (glob($path.$pattern.'.php') as $file) {
                  /* ///
                  Yryie::StartTimer($file, $file, 'include special '.$scope);
                  Yryie::Info(sprintf('%s [0%s]',
                                           str_replace(@$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR, '', $file),
                                           File::Permissions($file)));
                  /// */
                  Loader::Load($file);
                  /// Yryie::StopTimer();
                }
              }
            }
          }
          break;

        // --------------------
        case NULL :
          foreach ($patterns as $pattern) {
            $pattern = str_replace('/', DIRECTORY_SEPARATOR, $pattern);
            $path = (substr($pattern,0,1) != DIRECTORY_SEPARATOR)
                    // relative path
                  ? BASEDIR.DIRECTORY_SEPARATOR.$pattern.'.php'
                    // absolute path
                  : $pattern.'.php';
            foreach (glob($path) as $file) {
              /* ///
              Yryie::StartTimer($file);
              Yryie::Info(sprintf('%s (0%s)',
                                       str_replace(@$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR, '', $file),
                                       File::Permissions($file)));
              /// */
              Loader::Load($file);
              /// Yryie::StopTimer();
            }
          }
          break;
      }
    }
  }

  /**
   * Read configurations from config.xml
   *
   * @param string $scope module|plugin or other defined path
   * @return void
   */
  public static function ReadConfigs( $scope ) {
    // all plugin/module directories
    if (in_array($scope, esf_Extensions::$Types))
      $scope .= DIRECTORY_SEPARATOR.'*';

    if (!isset(self::$Cache)) self::$Cache = Cache::create(NULL, 'Mock');
    $xml = new XML_Array_Configuration(self::$Cache);

    $files = glob(BASEDIR.DIRECTORY_SEPARATOR.$scope.DIRECTORY_SEPARATOR.'config.xml');
    foreach ($files as $file) {
      if (!$cfg = $xml->ParseXMLFile($file)) {
        // parser error
        Messages::Error($xml->Error);
        continue;
      }
      foreach ($cfg as $key1=>$data1) {
        if (!is_array($data1)) {
          Registry::set($key1, $data1);
        } else {
          foreach ($data1 as $key2=>$data2) {
            if (!is_array($data2)) {
              Registry::set($key1.'.'.$key2, $data2);
            } else
              foreach ($data2 as $key3=>$data3)
                Registry::set($key1.'.'.$key2.'.'.$key3, $data3);
          }
        }
      }
    }
  }

  /**
   * Define a required Extension for an Extension
   *
   * Extension #1 require Extension #2
   *
   * A minimum version can be defined.
   *
   * @param string $scope1 module|plugin
   * @param string $part1 Module/plugin name
   * @param string $scope2 module|plugin
   * @param string $part2 Module/plugin name
   * @param string $version Minimal required version
   */
  public static function setRequired( $scope1, $part1, $scope2, $part2, $version=0 ) {
    $require =& self::$Required[strtolower($scope1)][strtolower($part1)];
    $require[strtolower($scope2)][strtolower($part2)] = $version;
  }

  /**
   * Check if all required extensions for given extension found
   *
   * @param string $scope module|plugin
   * @param string $part Event name
   * @param array &$Err Error messages
   * @return boolean
   */
  public static function CheckRequired( $scope, $part, &$Err ) {
    $Err = array();
    $ls = strtolower($scope);
    $lp = strtolower($part);
    $checked = TRUE;
    if (!empty(self::$Required[$ls][$lp])) {
      foreach (self::$Required[$ls][$lp] as $reqscope => $reqparts) {
        if ($reqscope == 'core') continue;
        foreach ($reqparts as $check => $version) {
          $reqversion = Registry::get($reqscope.'.'.$check.'.Version', 0);
          $partversion = Registry::get($reqscope.'.'.$part.'.Version', 0);
          if (!esf_Extensions::checkState($reqscope, $check, esf_Extensions::BIT_ENABLED) OR
              version_compare($reqversion, $version, '<')) {
            $msg = sprintf('%s "%s" Version %s requires enabled %s "%s"',
                           ucwords($scope), ucwords($part), $partversion,
                           ucwords($reqscope), ucwords($check));
            if ($version) $msg .= sprintf(' with Version >= %s', $version);
            $Err[] = $msg.'!';
            $checked = FALSE;
          }
        }
      }
    }
    return $checked;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Definition of Event requirements
   *
   * @var array $Required
   */
  private static $Required = array();

}
