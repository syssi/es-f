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
* @package Module-Login
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
Translation::Define('RSS', array(
# -----------------------------------------------------------------------------

'Language'                  => 'en-us',

// $1$s : User name
'FeedFor'                   => '$1$ss auctions',
'LastState'                 => 'Last auction state',

'Ended'                     => 'ended',
'NoEnd'                     => 'No end date',
'None'                      => 'none',

'Category'                  => 'Category',
'Group'                     => 'Group',

'Remain'                    => 'Remain',
'AuctionEnded'              => 'Auction has ended.',
'Bids'                      => 'Bids',
'Bidder'                    => 'Bidder',
'Bid'                       => 'Bid',
'BinPrice'                  => 'Buy-it-now price',
'Shipping'                  => 'Shipping',
'Total'                     => 'Total',
'Comment'                   => 'Comment',
'End'                       => 'End',

# -----------------------------------------------------------------------------
));
# -----------------------------------------------------------------------------
