<?php
/**
 * Module Logfiles plugin
 *
 * @ingroup    Plugin
 * @ingroup    Module-Logfiles
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
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