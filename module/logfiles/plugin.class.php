<?php
/**
 * @category   Plugin
 * @package    Plugin-LogFiles
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Rewrite urls
 *
 * @category   Plugin
 * @package    Plugin-LogFiles
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_Module_LogFiles extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('BuildMenu');
  }

  /**
   *
   */
  function BuildMenu() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    // require valid login
    if (!esf_User::isValid()) return;

    esf_Menu::addSystem( array( 'module' => 'logfiles' ) );

    if ($file = Registry::get('LOGFILE'))
      Registry::add('Module.Logfiles.LogFile', $file);
  }

  /**
   * NOT YET ACTIVE
   */
  function PageStart() {
    if (!PluginEnabled('Validate')) return;

    DefineValidator('id', 'integer');
    DefineValidator('bug', 'base64');

    /**
     * Validator for base64 coded parameter
     * /
    function Validator_Base64 ( $key, $value, $params ) {
      $params['pattern'] = '[\w=]+';
      return Validator_Regex($key, $value, $params);
    }
    */
  }
}

Event::attach(new esf_Plugin_Module_LogFiles);