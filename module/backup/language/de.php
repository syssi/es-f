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
* @subpackage Backup
* @desc German language definitions
*/

defined('_ESF_OK') || die('No direct call allowed.');

// ---------------------------------------------------------------------------
Translation::Define('BACKUP', array(
// ---------------------------------------------------------------------------

// menu
'Menu'                      => 'Backups',
'Menuhint'                  => 'Auktions-Backups',
'Menuauction'               => 'Auktionen',
'Menuauctionhint'           => 'Liste der Auktionen',
'Title'                     => 'Backups',

'TitleShow'                 => 'Anzeigen',

// list table header
'AuctionsCount'             => 'Anzahl Auktionen: %1$d',
'Auction'                   => 'Auktion',
'Category'                  => 'Kategorie',
'Group'                     => 'Gruppe',

// actions
'Toggle'                    => 'Markierung umschalten',
'Details'                   => 'Details',
'MarkedAuctions'            => 'markierte Auktionen',
'Actions'                   => '--- bitte auswählen ---',
'Delete'                    => 'Löschen',
'Restore'                   => 'Wiederherstellen',
'Send'                      => 'Ausführen',

'Attention'                 => 'ACHTUNG',
'DeleteAll'                 => 'Alle Auktionen löschen',
'DeleteAllTip'              => 'Vorsicht: Es werden ALLE Auktionen gelöscht, AUCH die gesperrten!',

'Lock'                      => 'Sperren',
'Locked'                    => 'Auktion wurde gegen Löschen gesperrt.',
'Unlock'                    => 'Entsperren',
'Unlocked'                  => 'Auktion wurde entsperrt.',

// messages
'AuctionMissing'            => 'Auktion nicht gefunden',
// %1$d - count of deleted auctions
'Deleted'                   => '%1$d Auktionen wurden gelöscht.',
'Restored'                  => 'Auktionen wurden wiederhergestellt.',
'AllDeleted'                => 'Alle Auktionen wurden gelöscht.',

'Back'                      => 'zurück',
'PaginationHint'            => 'Gehe zu Seite {PAGE}',

// ---------------------------------------------------------------------------
));
