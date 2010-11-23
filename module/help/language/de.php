<?php
/**
 * Language definition
 *
 * @package Modules
 * @subpackage Help
 */

defined('_ESF_OK') || die('No direct call allowed.');

/**
 * Don't "htmlspecialchar" your translation,
 * just type '<text>' and NOT '&lt;text&gt;'!
 *
 * line format (php array):
 *
 * 'TextId' => 'translated text',
 */
// ----------------------------------------------------------------------------
Translation::Define('Help', array(
// ----------------------------------------------------------------------------

'Title'                     => 'Hilfe',
'TitleShow'                 => 'Anzeigen',
'TitleEdit'                 => 'Bearbeiten',

// menu
'Menu'                      => 'Hilfe',
'Menuhint'                  => 'Hilfe zu Modulen und Plugins',

'ModuleMenu'                => 'Hilfe',
'ModuleMenuhint'            => 'Hilfe zum Modul',

'Description'               => 'Beschreibung',

'Back'                      => '<< zurück',
'Edit'                      => 'Ändern',

'Save'                      => 'Sichern',
'Saved'                     => 'Datei gespeichert.',

'CloseWindow'               => 'Fenster schließen',

// ----------------------------------------------------------------------------
));