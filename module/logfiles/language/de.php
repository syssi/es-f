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
* @subpackage Logfiles
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
Translation::Define('LOGFILES', array(
# -----------------------------------------------------------------------------

# menu
'Menu'                      => 'Logfiles',
'Menuhint'                  => 'Anzeige diverser Logfiles',
'Menu2'                     => 'Auswahl',
'Menu2hint'                 => 'Log-Datei auswählen',

'Title'                     => 'Logfiles',
'TitleShow'                 => 'Anzeigen',

'Show'                      => 'Log-File anzeigen',
'Delete'                    => 'Log-File löschen',

# %s => log file name
'Deleted'                   => 'Log-File [%s] gelöscht.',
'DeleteError'               => 'Fehler beim Löschen des Log-Files [%s]!',

# -----------------------------------------------------------------------------
));
# -----------------------------------------------------------------------------

?>
