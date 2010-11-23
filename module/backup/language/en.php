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
* @package Module-Backup
* @subpackage Languages
* @desc English language definitions
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
Translation::Define('BACKUP', array(
# -----------------------------------------------------------------------------

# menu
'Menu'                      => 'Backup',
'Menuhint'                  => 'Backup auctions',
'Menuauction'               => 'Auctions',
'Menuauctionhint'           => 'List of auctions',
'Title'                     => 'Backup',

'TitleShow'                 => 'Show',

# list table header
'AuctionsCount'             => '# of auctions: %1$d',
'Auction'                   => 'Auction',
'Category'                  => 'Category',
'Group'                     => 'Group',

# actions
'Toggle'                    => 'Toggle auctions',
'Details'                   => 'Details',
'MarkedAuctions'            => 'marked auctions',
'Actions'                   => '--- please select ---',
'Delete'                    => 'Delete',
'Restore'                   => 'Restore',
'Send'                      => 'Send',

'Attention'                 => 'ATTENTION',
'DeleteAll'                 => 'Delete all auctions',
'DeleteAllTip'              => 'Caution: This will delete ALL auctions, also the locked ones!',

'Lock'                      => 'Lock',
'Locked'                    => 'Auctions locked against deleting.',
'Unlock'                    => 'Unlock',
'Unlocked'                  => 'Auctions unlocked.',

# messages
'AuctionMissing'            => 'Auction is missing',
// %1$d - count of deleted auctions
'Deleted'                   => '%1$d Auctions deleted.',
'Restored'                  => 'Auctions restored.',
'AllDeleted'                => 'All auctions deleted.',

'Back'                      => 'back',
'PaginationHint'            => 'Go to page {PAGE}',

# -----------------------------------------------------------------------------
));
