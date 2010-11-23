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
* @package Module-Snipe
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
Translation::Define('SNIPE', array(
# -----------------------------------------------------------------------------

'AddAuction'                => 'Add auction',
'Comment'                   => 'Comment',
'Group'                     => 'Group',
'Shipping'                  => 'Shipping (e.g. if only mentioned in description)',
'GroupCount'                => 'Quantity',
'GroupBid'                  => 'Bid for group',
'Category'                  => 'Category',
'Or'                        => 'or',
'AuctionBid'                => 'Auction bid',
'DifferentFromGroup'        => 'different from group bid',
'BidNow'                    => 'Bid now',
'UseToBreakBuyNow'          => 'use this e.g. to break buy-it-now price',

// buttons
'Start'                     => 'Start',
'Save'                      => 'Save',
'Cancel'                    => 'Cancel',
'Close'                     => 'Close',

'PleaseWait'                => 'html:Auction will be added now.<br><br>Just a moment please...',

// messages
'Login'                     => 'Please login to add an auction.',

# -----------------------------------------------------------------------------
));
# -----------------------------------------------------------------------------

?>
