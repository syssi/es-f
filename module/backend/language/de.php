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
* @package Module-Backend
* @subpackage Languages
* @desc German language definitions
*/

defined('_ESF_OK') || die('No direct call allowed.');

// ---------------------------------------------------------------------------
Translation::Define('Backend', array(
// ---------------------------------------------------------------------------

# menu
'Menu'                      => 'Backend',
'Menuhint'                  => 'Module und Plugins bearbeiten',
'Title'                     => 'Backend',
'TitleIndex'                => 'Backend',

'Category'                  => 'Kategorie',
'Modules'                   => 'Module',
'Plugins'                   => 'Plugins',

'Extension'                 => 'Erweiterung',
'Version'                   => 'Version',
'Description'               => 'Beschreibung',
'Actions'                   => 'Aktionen',

'Enable'                    => 'aktivieren',
'Enabled'                   => 'aktiv',

'State'                     => 'Status',
'Disable'                   => 'deaktivieren',
'Disabled'                  => 'inaktiv',

'Install'                   => 'installieren',
'Deinstall'                 => 'deinstallieren',
'Reinstall'                 => 'neu installieren',
'Installed'                 => 'installiert',
'NotInstalled'              => 'nicht installiert',

'EditConfiguration'         => 'Konfiguration bearbeiten',

'CoreInfo'                  => 'Basis-Funktion, kann nicht deaktiviert/deinstalliert werden',

'InstallSuccessed'          => 'Installation erfolgreich durchgeführt.',
'InstallFailed'             => 'Installation nicht erfolgreich durchgeführt.',
'DeinstallSuccessed'        => 'Deinstallation erfolgreich durchgeführt.',
'DeinstallFailed'           => 'Deinstallation nicht erfolgreich durchgeführt.',
'EnableSuccessed'           => 'Aktivierung erfolgreich durchgeführt.',
'EnableFailed'              => 'Aktivierung nicht erfolgreich durchgeführt.',
'DisableSuccessed'          => 'Deaktivierung erfolgreich durchgeführt.',
'DisableFailed'             => 'Deaktivierung nicht erfolgreich durchgeführt.',

# %s => info url
'LookForInfo'               => 'html:
Lies bitte auf der <a href="%s">Informations-Seite</a> nach,
ob zusätzliche Anforderungen bestehen!',

# %s => directory name
'CreateDirectory'           => 'Erstelle Verzeichnis [%s]',
# %s => directory name
'CantMakeDirectory'         => 'Verzeichnis [%s] konnte nicht erstellt werden.',
# %s => work directory name
'MakeDirectoryWritable'     => 'Mache bitte das Verzeichnis [%s] beschreibbar!',

# %s => file name
'CreateFile'                => 'Erstelle Datei [%s]',
# %s => file name
'CantMakeFile'              => 'Datei [%s] konnte nicht erstellt werden.',

# %s => zip file name
'ExtractArchive'            => 'Extrahiere Archiv [%s]',
# %s => file name
'MissingFile'               => 'Datei [%s] nicht vorhanden!',
# %s => directory name
'RemoveDirectory'           => 'Entferne Verzeichnis [%s]',
# %s => file name
'RemoveFile'                => 'Entferne Datei [%s]',

'Done'                      => 'Erfolgreich',
'Failed'                    => 'NICHT erfolgreich',

// ---------------------------------------------------------------------------
));