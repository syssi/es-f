<?php
/**
 * Class Core
 *
 * Core application functions
 *
 * @package    Ces-f
 * @author
 * @copyright
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version
 */
abstract class Core {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

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
      foreach ($headers as $key => $val)
        $headers[$key] = $key.'='.urlencode($val);
      $return = sprintf('<a href="mailto:%s?%s">%1$s</a>',
                        htmlspecialchars($return),
                        htmlspecialchars(implode('&',$headers)));
    }

    return $return;
  }

  /**
   *
   */
  public static function TempName( $prefix ) {
    return tempnam(TEMPDIR, $prefix);
  }

  /**
   * ISO 8859-1 to UTF-8 conversion
   *
   * Found on http://php.net/manual/function.iconv.php
   * UCN by ng4rrjanbiah at rediffmail dot com, 22-Jun-2004 05:10
   *
   * @param string $text Text to convert
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
   * Found on http://php.net/manual/function.iconv.php
   * UCN by ng4rrjanbiah at rediffmail dot com, 22-Jun-2004 05:10
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
   * @param boolean $forceRestart Force restart of session, e.g. in case of logout
   * @param boolean $keepCookie Keep cookie over session restart
   * @return void
   */
  public static function StartSession( $forceRestart=FALSE, $keepCookie=FALSE ) {
    /// Session::$Debug = TRUE;
    /// Session::$Messages = array();
    if (!Session::Active()) {
      // Session not started yet
      Event::ProcessInform('InitSession');
      Session::SetName(Registry::get('SessionName'));
      Session::Start();
    } elseif ($forceRestart) {
      // force restart
      if (!$keepCookie) setCookie(esf_User::COOKIE, '');
      Session::destroy(!$keepCookie);
      Event::ProcessInform('InitSession');
      Session::SetName(Registry::get('SessionName'));
      Session::Start();
    }

    // >> Debug
    if (DebugStack::Active())
      foreach ((array)Session::$Messages as $msg)
        DebugStack::Info($msg);
    Session::$Messages = array();
    // << Debug
  }

  /**
   * Forward to another module and/or action without page relaod,
   * used in prepare.php
   *
   * @param string $ud_module Module
   * @param string $ud_action Module action
   * @param string $ua_params Additional parameters
   */
  public static function Forward( $ud_module=NULL, $ud_action=NULL, $ua_params=array() ) {
    if (isset($ua_params['module'])) {
      $ud_module = $ua_params['module'];
      unset($ua_params['module']);
    }
    if (isset($ua_params['action'])) {
      $ud_action = $ua_params['action'];
      unset($ua_params['action']);
    }
    $do['module'] = $ud_module;
    $do['action'] = $ud_action;

    Registry::set('esf.module', ($do['module'] ? $do['module'] : Registry::get('StartModule')));
    Registry::set('esf.action', ($do['action'] ? $do['action'] : 'index'));

    $do = array_merge($ua_params, $do);
    unset($do['module'], $do['action']);
    foreach ($do as $key => $val) {
      $_REQUEST[$key] = $val;
    }
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
    if (get_magic_quotes_gpc()) {
      if (!is_array($var)) {
        $var = stripslashes($var);
      } else {
        foreach (array_keys($var) as $key) self::StripSlashes($var[$key]);
      }
    }
  }

  /**
   *
   */
  public static function IncludeSpecial( $Scopes, $Patterns, $force=FALSE ) {

    if (!is_array($Scopes)) $Scopes = array($Scopes);
    if (!is_array($Patterns)) $Patterns = array($Patterns);

    // >> Debug
    DebugStack::Info(sprintf('(%s)'.DIRECTORY_SEPARATOR.'(%s)', implode('|',$Scopes), implode('|',$Patterns)));
    // << Debug

    foreach ($Scopes as $Scope) {

      $chk4User = (!$force AND ($Scope == esf_Extensions::MODULE));

      switch ($Scope) {
        // --------------------
        case esf_Extensions::MODULE :
        case esf_Extensions::PLUGIN :
          foreach (esf_Extensions::getExtensions($Scope) as $Extension) {
            /**
             * - load all plugin files if enabled
             * - load all module files if enabled and not login required or valid user
             */
            if (esf_Extensions::checkState($Scope, $Extension, esf_Extensions::BIT_ENABLED) AND
                (!$chk4User OR
                 !Registry::get('Module.'.$Extension.'.LoginRequired') OR
                 esf_User::isValid())) {
              $path = sprintf(BASEDIR.'%1$s%2$s%1$s%3$s%1$s', DIRECTORY_SEPARATOR, $Scope, $Extension);
              foreach ($Patterns as $Pattern) {
                $Pattern = str_replace('/', DIRECTORY_SEPARATOR, $Pattern);
                foreach (glob($path.$Pattern.'.php') as $file) {
                  /* ///
                  DebugStack::StartTimer($file, $file, 'include special '.$Scope);
                  DebugStack::Info(sprintf('%s [0%s]',
                                           str_replace(@$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR, '', $file),
                                           File::Permissions($file)));
                  /// */
                  Loader::Load($file);
                  /// DebugStack::StopTimer();
                }
              }
            }
          }
          break;

        // --------------------
        case NULL :
          foreach ($Patterns as $Pattern) {
            $Pattern = str_replace('/', DIRECTORY_SEPARATOR, $Pattern);
            $path = (substr($Pattern,0,1) != DIRECTORY_SEPARATOR)
                    // relative path
                  ? BASEDIR.DIRECTORY_SEPARATOR.$Pattern.'.php'
                    // absolute path
                  : $Pattern.'.php';
            foreach (glob($path) as $file) {
              /* ///
              DebugStack::StartTimer($file);
              DebugStack::Info(sprintf('%s (0%s)',
                                       str_replace(@$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR, '', $file),
                                       File::Permissions($file)));
              /// */
              Loader::Load($file);
              /// DebugStack::StopTimer();
            }
          }
          break;
      }
    }
  }

  /**
   * Read configurations from config.xml
   *
   * $param string $scope module|plugin or other defined path
   * @return void
   */
  public static function ReadConfigs( $scope ) {
    // all plugin/module directories
    if (in_array($scope, esf_Extensions::$Types))
      $scope .= DIRECTORY_SEPARATOR.'*';

    $xml = new XML_Array_Configuration(Cache::getInstance());

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
            } else {
              foreach ($data2 as $key3=>$data3) {
                Registry::set($key1.'.'.$key2.'.'.$key3, $data3);
              }
            }
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
   * Check if all required Events for given Event found
   *
   * @param string $scope module|plugin
   * @param string $part Event name
   * @param array &$Err Error messages
   * @return boolean
   * @global array
   */
  public static function CheckRequired( $scope, $part, &$Err ) {
    $Err = array();
    $ls = strtolower($scope);
    $lp = strtolower($part);
    $checked = TRUE;
    if (!empty(self::$Required[$ls][$lp])) {
      foreach (self::$Required[$ls][$lp] as $reqscope => $reqparts) {
        switch ($reqscope) {
          case 'core':
            break;
          default:
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
              } else {
              }
            }
            break;
        } // switch
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
   * @var array
   */
  private static $Required = array();

}