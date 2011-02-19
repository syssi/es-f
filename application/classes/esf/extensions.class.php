<?php
/**
 * Extensions handling
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class esf_Extensions {

  /**
   * @name Shortcuts
   * @{
   */
  const MODULE = 'module';
  const PLUGIN = 'plugin';
  /** @} */

  /**
   * @name State bit for extensions
   * @{
   */
  /**
   * extension is installed
   */
  const BIT_INSTALLED  = 1;

  /**
   * extension is enabled
   */
  const BIT_ENABLED    = 2;

  /**
   * extension is a protected core function and can't disabled
   */
  const BIT_PROTECTED  = 4;

  /**
   * extension is a hidden core function and isn't visible in {@link esf_Module_Backend backend module}
   */
  const BIT_HIDDENCORE = 8;
  /** @} */

  /**
   * @name Extension states
   * @{
   */
  /**
   * extension is not installed yet
   */
  const STATE_NOTHING    = 0;

  /**
   * extension is installed
   */
  const STATE_INSTALLED  = 1;

  /**
   * extension is enabled
   */
  const STATE_ENABLED    = 3;

  /**
   * extension is a protected core function and can't disabled
   */
  const STATE_PROTECTED  = 5;

  /**
   * extension is a hidden core function and isn't visible in {@link esf_Module_Backend backend module}
   */
  const STATE_HIDDENCORE = 11;
  /** @} */

  /**
   * Shortcut for both handled extension types
   */
  public static $Types = array( self::MODULE, self::PLUGIN );

  /**
   * Initialize extensions system
   *
   * @param  string $scope module|plugin
   * @return void
   */
  public static function Init( $scope='' ) {

    if (!$scope) {

      // first run, read state.xml
      $xml = new XML_Array_Configuration(Core::$Cache);
      self::$States = $xml->ParseXMLFile(LOCALDIR.'/config/state.xml');
      if (!self::$States) die($xml->Error);

    } else {

      $path = BASEDIR.'/'.$scope;
      $Exec = Exec::getInstance();

      // find all directories
      foreach (array_map('basename', glob($path.'/*', GLOB_ONLYDIR)) as $extension) {
        if (!file_exists($path.'/'.$extension.'/.disabled')) {
          // NOT disabled, set Event state
          Registry::add($scope.'.'.$extension, Registry::get('Defaults.'.$scope));

          self::$Extensions[$scope][] = $extension;
          if (!isset(self::$States[$scope][$extension])) {
            self::$States[$scope][$extension] = self::STATE_NOTHING;
          } elseif (self::$States[$scope][$extension] > self::BIT_INSTALLED) {
            $file = sprintf('%s/%s/%s/exec.%s.xml', BASEDIR, $scope, $extension, ESF_OS);
            file_exists($file) && $Exec->setCommandsFromXMLFile($file);
          }
        } else {
          // disabled, remove Event state
          unset(self::$States[$scope][$extension]);
        }
      }
      sort(self::$Extensions[$scope]);
    }
  }

  /**
   * Get all extensions
   *
   * @param  string $scope
   * @return array
   */
  public static function getExtensions( $scope='' ) {
    return $scope ? self::$Extensions[$scope] : self::$Extensions;
  }

  /**
   * Set an extension state
   *
   * @param string $scope
   * @param string $extension
   * @param int    $state
   */
  public static function setState( $scope, $extension, $state ) {
    self::$States[$scope][strtolower($extension)] = $state;
  }

  /**
   * Get an extension state
   *
   * @param string $scope
   * @param string $extension
   * @return int
   */
  public static function getState( $scope, $extension ) {
    $extension = strtolower($extension);
    return isset(self::$States[$scope][$extension])
         ? self::$States[$scope][$extension]
         : self::STATE_NOTHING;
  }

  /**
   * Check an extension state
   *
   * @param string $scope
   * @param string $extension
   * @param string $state
   * @return bool
   */
  public static function checkState( $scope, $extension, $state ) {
    $extension = strtolower($extension);
    return (isset(self::$States[$scope][$extension]) AND
            ((self::$States[$scope][$extension] & $state) == $state));
  }

  /**
   *
   */
  public static function saveStates( $file ) {
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->formatOutput = TRUE;

    $root = $doc->createElement('configuration');
    $doc->appendChild($root);

    foreach (self::$Types as $scope) {
      $sec = $doc->createElement('section');
      $root->appendChild($sec);

      $attr = $doc->createAttribute('name');
      $attr->appendChild(new DOMText($scope));
      $sec->appendChild($attr);

      ksort(self::$States[$scope]);

      foreach (self::$States[$scope] as $key => $value) {
        // don't save the state ESF_STATE_NOTHING
        if ($value === ESF_STATE_NOTHING) continue;

        $cfg = $doc->createElement('config', $value);
        $sec->appendChild($cfg);

        $attr = $doc->createAttribute('name');
        $attr->appendChild(new DOMText($key));
        $cfg->appendChild($attr);

        $attr = $doc->createAttribute('type');
        $attr->appendChild(new DOMText('int'));
        $cfg->appendChild($attr);
      }
    }
    return $doc->save($file);
  }

  /**
   * Check if an extesion is configurable
   *
   * @param string $scope
   * @param string $extension
   * @return bool
   */
  public static function isConfigurable( $scope, $extension ) {
    return file_exists($scope.'/'.$extension.'/configuration.xml');
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Store installed extensions
   *
   * @var array $Extensions
   */
  private static $Extensions = array();

  /**
   * Store extensions states
   *
   * @var array $States
   */
  private static $States = array();

}
