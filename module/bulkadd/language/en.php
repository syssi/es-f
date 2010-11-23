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
Translation::Define('BulkAdd', array(
# -----------------------------------------------------------------------------

'Title'                     => 'Add auctions',

'Menu'                      => 'Add multiple',
'MenuHint'                  => 'Add multiple auction at once from list',

'AuctionsHere'              => 'Add many auctions at once',

'AddListOrFile'             => 'Put a list of auctions into the text area or upload a file containing auctions.',

'Save'                      => 'Save',

# -----------------------------------------------------------------------------
));