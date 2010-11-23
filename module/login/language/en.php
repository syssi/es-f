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
Translation::Define('Login', array(
# -----------------------------------------------------------------------------

#menu
'Menu'                      => 'Login',
'MenuHint'                  => 'html:Login into <tt>'.ESF_TITLE.'</tt> with account name and password',
'Title'                     => 'Login',

# form
'Login'                     => 'Login',
'Account'                   => 'Account',
'Select'                    => 'Select',
'Password'                  => 'html:<tt>'.ESF_TITLE.'</tt> passwort',
'YourAccountAndPassword'    => 'html:Your eBay account and <tt>'.ESF_TITLE.'</tt> password',
'Cookie'                    => 'Keep me signed in for today.',
'CookieHint'                => 'Don\'t check this if you\'re at a public or shared computer.',

# messages
'GoodMorning'               => 'Good morning',
'GoodDay'                   => 'Good day',
'GoodAfternoon'             => 'Good afternoon',
'GoodEvening'               => 'Good evening',

'Failed'                    => 'Unknown user or password!',

# -----------------------------------------------------------------------------
));
# -----------------------------------------------------------------------------

Translation::Define('LoginHelp', array(
# -----------------------------------------------------------------------------
'Cookie'                    => 'html:<div class="li">When signing in, you can choose to have your computer
remember your sign-in information for one day so that you don\'t have to
re-enter your User ID and password.</div>
<div class="li">Your password will be saved until you sign out, even if you disconnect from
the Internet, close your browser, or turn off your computer.</div>',
# -----------------------------------------------------------------------------
));