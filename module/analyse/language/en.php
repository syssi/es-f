<?php
/**
* @package Module-Analyse
* @subpackage Languages
* @desc English language definitions
*/

defined('_ESF_OK') || die('No direct call allowed.');

// ---------------------------------------------------------------------------
Translation::Define('ANALYSE', array(
// ---------------------------------------------------------------------------

# menu
'Menu'                      => 'Analyse',
'Menuhint'                  => 'Analyse ended auctions',
'Menuauction'               => 'Auctions',
'Menuauctionhint'           => 'List of auctions',
'Menu2'                     => 'Selection',
'Menu2hint'                 => 'Group selection',

'Title'                     => 'Analyse',
'TitleShow'                 => 'Show',
'TitleShowmulti'            => 'Show multiple',

'Group'                     => 'Bid group',
'Groups'                    => 'Bid groups',

'ShowAuctions'              => 'Show auctions',
'HideAuctions'              => 'Hide auctions',

'Analyse'                   => 'Analyse bid group(s)',

// table
'Auction'                   => 'Auction',
'End'                       => 'End time',
'Ended'                     => 'ended',
'Bid'                       => 'Highest bid',
'Bids'                      => '# of bids',

'MyBid'                     => 'my bid',
'Average'                   => 'average',
'HarmonicAverage'           => 'harm. av.',

'Description'               => 'html:
As bigger and greener a circle is, there are lesser bids on an auction.<br>
As smaller and reder a circle is, there are more bids on an auction.<br>
Not yet ended auctions are yellow.',

'ChanceHeader'              => 'Chance to win an auction',
'ChanceHeaderVariant'       => 'Variant',

'PriceRange'                => 'Price range',
'Auctions'                  => 'Auctions',
'Chance'                    => 'Chance',

'ChanceMessageDesc1'        => 'html:Splitted price range until a range holds less than <tt>%d%%</tt> of auctions.',

'ChanceMessageDesc1'        => 'html:
<form style="display:inline" method="post">
<input type="hidden" name="module" value="analyse">
<input type="hidden" name="action" value="show">
<input type="hidden" name="group" value="%s">
<input style="float:right;margin-left:20px" type="submit" value="Recalc">
Splitted price range until a range holds less than
<input id="split" class="input" name="split" value="%d">%% of auctions.
</form>',

'ChanceMessageDesc2'        => 'Splitted price range while no empty range occurs.',

// %1$s : price range, %2$f : percent chance, %3$f : percent chance
'ChanceMessage'             => 'html:The price range of <tt><strong>%1$.2f-%2$.2f</strong></tt>
offers a chance of about <tt><strong>%3$.0f%%</strong></tt> to win an auction.',

// ---------------------------------------------------------------------------
));
