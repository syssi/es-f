<?php
/**
 * @package Module-Auction
 * @subpackage Languages
 * @desc English language definitions
 */

defined('_ESF_OK') || die('No direct call allowed.');

// ---------------------------------------------------------------------------
Translation::Define('Auction', array(
// ---------------------------------------------------------------------------

'Title'                     => 'Auctions',
'TitleIndex'                => 'Auctions',
'TitleEditAuction'          => 'Edit auction',
'TitleEditGroup'            => 'Edit group',
'TitleDelete'               => 'Delete auction',

// menu
'Menu'                      => 'Auctions',
'MenuHint'                  => 'List of auctions',

'MenuDeleteEnded'           => 'Purge',
'MenuHintDeleteEnded'       => 'Delete all ended auctions',

// table
'Image'                     => 'Image',
'Auction'                   => 'Auction',
'Auctions'                  => 'Auctions',
'EndTime'                   => 'End time',
'Ended'                     => 'Ended',
'Endless'                   => 'Without end date',
'RemainingTime'             => 'Time remaining',
'NoOfBids'                  => '# of bids',
'NoBids'                    => '--',
'CurrentPrice'              => 'Current price',
'HighBidder'                => 'High bidder',
'Currency'                  => 'Currency',
'Shipping'                  => 'Shipping',
'ShippingFree'              => 'Delivered free',
'Seller'                    => 'Seller',
'GetFromEbay'               => 'get from eBay',
'Quantity'                  => 'Quantity',
'Bid'                       => 'Bid',
'MyBid'                     => 'My bid',

'Url'                       => 'URL',
'ImageRotate'               => 'Rotate image',
'ImageRotateNo'             => 'no',
'ImageRotateLeft'           => ' to the left ',
'ImageRotateRight'          => ' to the right ',

'AddAuctions'               => 'Add auctions',
'Price'                     => 'Price',
'Comment'                   => 'Comment',
'Piece'                     => 'pce.',
'Available'                 => 'avail.',
'Category'                  => 'Category',
'Categories'                => 'Categories',
'NoCategory'                => 'Without category',
'AuctionBid'                => "Auction \nbid",
'GroupBid'                  => 'Bid for group',
'GroupSingle'               => 'Base price',
'GroupTotal'                => 'All round price (incl. shipping)',
'Group'                     => 'Group',
'Groups'                    => 'Groups',
'InclCategory'              => 'includes category',
'ShouldReadAutomatic'       => 'This data was obtained automatically',
'ImageUrl'                  => 'Image URL',
'YourAuctionSettings'       => 'Your auction settings',
'DifferentFromGroup'        => 'different from group bid',
'BidNow'                    => 'Bid now',
'UseToBreakBuyNow'          => 'use this e.g. to break buy-it-now price',
'AddAuctions'               => 'Add auctions',

'Actions'                   => 'Actions',
'EditAuction'               => 'Edit auction',
'EditGroup'                 => 'Edit group',

'ConfirmDelete'             => 'Confirm delete',
'PleaseConfirmDelete'       => 'Please confirm deletion!',

'DeleteAuction'             => 'Delete auction',
'DeleteAuctionsOfGroup'     => 'Delete all auctions of group [%s].',
'CleanupAuctions'           => 'Delete ended auctions',
'DeleteGroup'               => 'Delete all auctions of the group',

'CategoryIgnoredOnGroup'    => 'If an existing group is selected, the category from this group will be used!',
// %1$s : category
'ShowAuctionsOfCategory'    => 'Show/hide all auctions of category "%s"',

'ShowMultiAddRow'           => "Show row to add auctions",

// %1$s : item id, %2$s : item name
'ConfirmDeleteAuction'      => 'html:Would you really delete auction<br><br><strong>%2$s</strong>?',
'ConfirmCleanupAuctions'    => 'Would you really delete all ended auctions?',

'Yes'                       => 'Yes',
'No'                        => 'No',

'Rename'                    => 'Rename',
'Start'                     => 'Start group',
'Stop'                      => 'Stop group',
'Startstop'                 => 'Start/stop group',
'Save'                      => 'Save',
'NoBidDefinedYet'           => 'No bid defined yet!',

'StartGroup'                => 'Start group',
'GroupComment'              => 'Group comment',
'EsniperIsRunning'          => 'esniper is running and ready to snipe...',

'ClickForEdit'              => 'Click for "inline" editing',

'RemoveGroupWillSplit'      => 'If you empty the group name, the group will be split into individual auctions.',

'EditSaveAuction'           => 'Save',
'EditSaveGroup'             => 'Save',
'EditStartGroup'            => 'Start',
'EditCancel'                => 'Cancel',
'Cancel'                    => 'Cancel',

'MarkedAuctions'            => 'marked '."\n".'auctions',
'Or'                        => 'or',
'Select'                    => 'select',
'MoveToCategory'            => 'Move to category',
'MoveToGroup'               => 'Move to group',
'SetImage'                  => 'Replace image',
'SetComment'                => 'Set comment',
'SetBid'                    => 'Set auction bid',
'SetCurrency'               => 'Set currency',
'Refresh'                   => 'Refresh auction',
'RefreshAuctions'           => 'Refresh auctions',
'RefreshCategory'           => 'Refresh auctions of this category',
'RefreshGroup'              => 'Refresh auctions of this group',
'Go'                        => 'Go',

// errors
'Error'                     => 'ERROR',
'NoItem'                    => 'Got no item number!',
'GroupBidUpdated'           => 'Group bid of group [%1$s] updated from auction bid.',
'MissingAmount'             => 'Missing bid amount!',

// messages
// %1$s : Auction title
'AuctionSaved'              => 'Auction [%1$s] saved.',
// %1$s : Auction title
'AuctionDeleted'            => 'Auction [%1$s] deleted.',
// %1$d : Count of deleted auctions
'AuctionsDeleted'           => '%1$d auctions deleted.',
// %1$d : Count of deleted auctions
'DeletedEnded'              => array( 'One ended auction deleted.',
                                      '%1$d ended auctions deleted.' ),
'NoDeletedEnded'            => 'No ended auctions found.',
'GroupSaved'                => 'Group saved.',
// %1$s : group
'GroupStarted'              => 'Group [%1$s] started.',
// %1$s : group, %2$s : group hash (for anchor)
'GroupNotStarted'           => 'html:Group [%1$s] not started! (refer to the <a href="?module=protocol#%2$s">auction log file</a> for more informations)',
'AuctionBiddedNow'          => 'Auction bid placed.',
// %1$s : group
'GroupStopped'              => 'Group [%1$s] stopped.',
// %1$s : group, %2$s : category
'MovedGroupToCategory'      => 'Moved all auctions in group [%1$s] to category [%2$s].',
// %1$s : auction id, %2$s : auction name
'RefreshedJustEnded'        => 'html:Refreshed just ended auction "%2$s".',
// %1$s : auction id, %2$s : auction name
'SkipMonitored'             => 'Skip still monitored auction [%1$s] "%2$s"',
// %1$s : auction id
'ErrorRetrieving'           => 'Error retieving auction html data for item [%1$s] OR no usable parser found.',
// %1$s : auction id
'ErrorRetrievingTryAgain'   => 'html:May be, '.ESF_TITLE.' couldn\'t fetch the ebay page correctly, in this case, '
                             . 'you should <a href="?module=auction&amp;action=mrefresh&amp;auctions=%1$s">give it another try</a> :-)',
// %1$s : temp. dir., %2$s : auction id, %3$s : url to create bug tracker item
'ReportAuctionFiles'        => 'html:
If this error occurs more often, please:
<div class="li">go to this directory: %1$s</div>
<div class="li">pack these files into an archive: %2$s.*.html</div>
<div class="li"><a href="%3$s">create a bug report</a> on sourceforge and append these files</div>',

// %1$s : new version
'Upgrade'                   => 'Upgrade auctions to version %1$s',
'Upgraded'                  => 'Auctions upgraded to version %1$s',

// drag'n'drop
'Dragger'                   => 'Drag this onto a Drag\'n\'Drop target, to move that auction.',
'Droptarget'                => 'Drag\'n\'Drop target',
'DropRemoveGroup'           => 'Drop an auction here to remove it from its group.',
'DropCategory'              => 'Drop an auction here to move it into this category.',
'DropGroup'                 => 'Drop an auction here to move it into this group.',

// ---------------------------------------------------------------------------
));
// ---------------------------------------------------------------------------

// ---------------------------------------------------------------------------
Translation::Define('AuctionHelp', array(
// ---------------------------------------------------------------------------

'Category'                  => 'A category groups several auction with similar characteristics, e.g. "Books", "CDs" or "Car parts"',
'Group'                     => 'A groups pool auctions of the same product. e.g. a special CD. '
                             . 'You can now bid for a group of auctions until a bid was successful.',

'AddMultipleAuctions'       => 'html:
Add multiple auctions at once|
<div class="li">Provide a list of auction ids, separated by a &quot;none numeric&quot; separator, like space or comma.</div>
<div class="li">Select a existing category / group from the dropdowns or define a new one.</div>
<div class="li">Define quantity and bid amount and save / start the auctions.</div>',

// ---------------------------------------------------------------------------
));
