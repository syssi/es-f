<?php
/**
 * Core english translation
 *
 * @package    es-f
 * @subpackage Languages
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @since      File available since Release 0.0.1
 */

defined('_ESF_OK') || die('No direct call allowed.');

// ---------------------------------------------------------------------------
Translation::Define('Core', array(
// ---------------------------------------------------------------------------

'Welcome'                   => 'Welcome',
'Login'                     => 'Login',
'LastUpdate'                => 'Last auction update',

'Error'                     => 'Error',

// Messages
'EsniperEncounteredBug'     => 'ATTENTION: esniper encountered a bug. '
                             . 'Please take a look into [%s] or use Module Logfiles!',

// %1$s - Auction name
'AuctionSaved'              => 'Auction [%1$s] saved.',
'GroupSaved'                => 'Group saved.',
'ModuleNotFound'            => 'Module [%1$s] not found or not activated!',
'InvalidItem'               => 'Invalid item number (%1$s) or [Buy-It-Now] article!',
'DeleteInvalid'             => 'Delete invalid item',

'NoItem'                    => 'Got no item number!',
'ToMuchProcesses'           => 'There are more than 1 running esniper for group [%1$s]!',

'Version'                   => 'Version',
'Layout'                    => 'Layout',

// Selects
'SelectNone'                => '-- none --',
'SelectFromGroup'           => '- from group -',

// efa
'EFAbigger'                 => 'Increase font size',
'EFAreset'                  => 'Reset font size',
'EFAsmaller'                => 'Decrease font size',

// ---------------------------------------------------------------------------
));

// ---------------------------------------------------------------------------
Translation::Define('EBAY', array(
// ---------------------------------------------------------------------------

// ebaysearchform
'Homepage'                  => 'eBay Homepage',
'Find'                      => 'Find',

// ---------------------------------------------------------------------------
));

// ---------------------------------------------------------------------------
Translation::Define('CoreHelp', array(
// ---------------------------------------------------------------------------

'RefreshEnding'             => 'Refresh the page automatic, if an auction is ending soon.',

// ---------------------------------------------------------------------------
));
