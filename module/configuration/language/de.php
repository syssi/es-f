<?php
/**
* Copyright (c) 2006-2009 Knut Kohl <knutkohl@users.sourceforge.net>
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* GPL: http://www.gnu.org/licenses/gpl.txt
*
* @package Modules
* @subpackage Configuration
* @desc German language definitions
*/

defined('_ESF_OK') || die('No direct call allowed.');

# -----------------------------------------------------------------------------
#
# Don't "htmlspecialchar" your translation,
# just type '<text>' and NOT '&lt;text&gt;'!
#
# line format (php array):
# 'english text' => 'translated text',
#
Translation::Define('CONFIGURATION', array(
# -----------------------------------------------------------------------------

'Title'                     => 'Konfiguration',
'TitleEdit'                 => 'Bearbeiten',

# menu
'Menu'                      => 'Konfiguration',
'ModuleMenu'                => 'Konfigurieren',

'Menuhint'                  => 'Konfiguration der Module und Plugins bearbeiten',
'ModuleMenuhint'            => 'Konfiguration des Moduls bearbeiten',

# titles
'Modules'                   => 'Module',
'Plugins'                   => 'Plugins',

'NotWritable'               => 'Konfiguration [%1$s] ist nicht beschreibbar!',

# shown boolean config values
'True'                      => 'Ja',
'False'                     => 'Nein',

'Edit'                      => 'Ändern',
'Save'                      => 'Sichern',
'Reset'                     => 'Auf Standard zurücksetzen',
'Cancel'                    => 'Abbrechen',

# %s => user config file
// %1$s : file name
'Saved'                     => 'Konfiguration gesichert.',
'Reseted'                   => 'Konfiguration auf Standard zurückgesetzt.',

# -----------------------------------------------------------------------------
));
