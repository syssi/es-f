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
* @package Plugin-BidderInfo
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
#   'english text' => 'translated text',
#
# but there is ONE exception:
#   write in "html:..." visible Quotes (") as &quot;
#
# line format (php array):
#   'english text' => 'translated text',
#   'english text' => 'html:translated text',      // contains html tags
#   'english text' => 'file:filename to include',  // bigger texts are better
#                                                     editable in separate files
Translation::Define('BIDDERINFO', array(
# -----------------------------------------------------------------------------

'Anonymous'                 => 'Bidders are assigned anonymous names on auctions that exceed a certain level.',

# %1$s => bidder name
'Feedback'                  => 'eBay Feedback Profile for %1$s',

# -----------------------------------------------------------------------------
));
# -----------------------------------------------------------------------------

?>