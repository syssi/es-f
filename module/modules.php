<?php
/**
 * Core funtions for module handling
 *
 * @ingroup    Module
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */

defined('_ESF_OK') || die('No direct call allowed.');

/**
 * Set a module variable
 *
 * @param string $module Module name
 * @param string|array $var Variable name | Array of Name => Value
 * @param mixed $val Variable value
 */
function setModuleVar( $module, $var, $val=NULL ) {
  Registry::set(esf_Extensions::MODULE.'.'.$module.'.'.$var, $val);
}

/**
 * Add data to a module variable
 *
 * @param string $module Module name
 * @param string $var Variable name
 * @param mixed $val Variable value
 * @see setModuleVar
 */
function addModuleVar( $module, $var, $val ) {
  Registry::add(esf_Extensions::MODULE.'.'.$module.'.'.$var, $val);
}

/**
 * Get a module variable value
 *
 * Returns all defined variables if no $var is defined.
 *
 * @param string $module Module name
 * @param string $var Variable name
 * @param boolean $triggerError Trigger error if variable is not defined
 * @return mixed
 */
function getModuleVar( $module, $var='', $default=NULL ) {
  return Registry::get(esf_Extensions::MODULE.'.'.$module.'.'.$var, $default);
}

/**
 * Check if a module is installed
 *
 * @param string $module Module name
 * @return boolean
 */
function ModuleInstalled( $module ) {
  return esf_Extensions::checkState( esf_Extensions::MODULE, $module, esf_Extensions::BIT_INSTALLED );
}

/**
 * Check if a module is enabled
 *
 * @param string $module Module name
 * @return boolean
 */
function ModuleEnabled( $module ) {
  return esf_Extensions::checkState( esf_Extensions::MODULE, $module, esf_Extensions::BIT_ENABLED );
}

/**
 * Set a module specific SESSION variable value
 *
 * @param string $module Module name
 * @param string $var Variable name
 * @param mixed $val Variable value
 */
function setSessionModuleVar( $module, $var, $val=NULL ) {
  Session::set(esf_Extensions::MODULE.'.'.$module.'.'.$var, $val);
}

/**
 * Get a module specific SESSION variable value
 *
 * @param string $module Module name
 * @param string $var Variable name
 * @return mixed
 */
function getSessionModuleVar( $module, $var ) {
  return Session::get(esf_Extensions::MODULE.'.'.$module.'.'.$var);
}

/**
 * Get all installed module layouts
 *
 * @param string $module Module name
 * @return array
 */
function getModuleLayouts( $module ) {
  return Core::getLayouts( esf_Extensions::MODULE, $module );
}

/**
 * Define a module required by a module
 *
 * Example:
 * <code>
 *   ModuleRequireModule('analyse', 'auction');
 * </code>
 *
 * If no version is defined, any version is valid.
 *
 * @param string $module Source module
 * @param string $require Required module
 * @param string $version Required version (optional)
 */
function ModuleRequireModule( $module, $require, $version=0 ) {
  Core::setRequired( esf_Extensions::MODULE, $module, esf_Extensions::MODULE, $require, $version );
}

/**
 * Define a plugin required by a module
 *
 * @param string $module Source module
 * @param string $require Required plugin
 * @param string $version Required version
 */
function ModuleRequirePlugin( $module, $require, $version=0 ) {
  Core::setRequired( esf_Extensions::MODULE, $module, esf_Extensions::PLUGIN, $require, $version );
}

/**
 * Parse a module specific (sub) template
 *
 * @param string $module Module name
 * @param string $tpl Template
 * @param array $data Template data
 */
function ParseModuleTemplate( $module, $tpl='content', $data=FALSE ) {
  return esf_Template::getInstance()->Render(
    $tpl,
    DEVELOP,
    'module/'.$module.'/layout',
    $data
  );
}

Registry::set('Defaults.'.esf_Extensions::MODULE.'.Layout', 'default');

esf_Extensions::Init(esf_Extensions::MODULE);
