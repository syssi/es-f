<?php
/*
 *
 */

defined('_ESF_OK') || die('No direct call allowed.');

/**
 * Is a named plugin installed
 *
 * @param string $plugin Plugin name
 * @return bool
 */
function PluginInstalled( $plugin ) {
  return esf_Extensions::checkState(esf_Extensions::PLUGIN, $plugin, esf_Extensions::BIT_INSTALLED);
}

/**
 * Is a named plugin enabled
 *
 * @param string $plugin Plugin name
 * @return bool
 */
function PluginEnabled( $plugin ) {
  return esf_Extensions::checkState(esf_Extensions::PLUGIN, $plugin, esf_Extensions::BIT_ENABLED);
}

/**
 *
 */
function getPluginLayouts( $plugin ) {
  return Core::getLayouts(esf_Extensions::PLUGIN, $plugin);
}

/**
 *
 */
function PluginRequirePlugin( $plugin, $require, $version=0 ) {
  Core::setRequired(esf_Extensions::PLUGIN, $plugin, esf_Extensions::PLUGIN, $require, $version);
}

/**
 *
 */
function PluginRequireModule( $plugin, $require, $version=0 ) {
  Core::setRequired(esf_Extensions::PLUGIN, $plugin, esf_Extensions::MODULE, $require, $version);
}

/**
 *
 */
Registry::set('Defaults.'.esf_Extensions::PLUGIN.'.Layout', 'default');

esf_Extensions::Init(esf_Extensions::PLUGIN);
