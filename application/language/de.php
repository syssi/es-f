<?php
/**
 * German core translation
 *
 * @package    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @since      File available since Release 0.0.1
 */

defined('_ESF_OK') || die('No direct call allowed.');

// ---------------------------------------------------------------------------
Translation::Define('Core', array(
// ---------------------------------------------------------------------------

'Welcome'                   => 'Willkommen',
'Login'                     => 'Login',
'LastUpdate'                => 'Letzte Auktions-Aktualisierung',

'Error'                     => 'Fehler',

// Messages
'EsniperEncounteredBug'     => 'ACHTUNG: esniper hat einen Fehler-Report erstellt. '
                             . 'Sieh bitte in [%s] nach oder benutze das Module Logfiles!',

'AuctionSaved'              => 'Auktion [%1$s] gespeichert.',
'GroupSaved'                => 'Biet-Gruppe gespeichert.',
'ModuleNotFound'            => 'Modul [%1$s] ist nicht vorhanden oder nicht aktiviert!',
'InvalidItem'               => 'Ungültige Auktionsnummer (%1$s) oder [Sofortkauf]-Artikel!',
'DeleteInvalid'             => 'Ungültige Auktion löschen',

'NoItem'                    => 'Keine Auktionsnummer angegeben!',
'ToMuchProcesses'           => 'Es gibt mehr als 1 laufenden esniper Prozess für Gruppe [%1$s]!',

'Version'                   => 'Version',
'Layout'                    => 'Layout',

// Selects
'SelectNone'                => '- keine -',
'SelectFromGroup'           => '- von Gruppe -',

// efa
'EFAbigger'                 => 'Increase font size',
'EFAreset'                  => 'Reset font size',
'EFAsmaller'                => 'Decrease font size',

// ---------------------------------------------------------------------------
));

// ---------------------------------------------------------------------------
Translation::Define('Ebay', array(
// ---------------------------------------------------------------------------

// ebaysearchform
'Homepage'                  => 'eBay Homepage',
'Find'                      => 'Finden',

// ---------------------------------------------------------------------------
));

// ---------------------------------------------------------------------------
Translation::Define('CoreHelp', array(
// ---------------------------------------------------------------------------

'RefreshEnding'             => 'Aktualisiert die Seite automatisch, wenn eine Auktion bald endet.',

// ---------------------------------------------------------------------------
));
