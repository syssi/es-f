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
 * @package Module-Auction
 * @subpackage Module-Configuration
 * @desc German configuration language definitions
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
Translation::Define('AuctionConfiguration', array(
# -----------------------------------------------------------------------------

'NAME' => 'Auktionen',
'DESCRIPTION' => 'Dieses Modul kann nicht deaktiviert werden.',
'DESCRIPTION_LONG' => '',

// Variables descriptions
'LAYOUTENDED' => 'Verwende für beendete Auktionen/Gruppen anderes (kleineres) Layout',

'IMAGESIZE' => 'Max. Bild-Breite/-Höhe im Anzeige-Modus (PopUp) [px <small>(0 für keine Beschränkung)</small>]',

'NOIMAGE' => 'Ersatzbild-URL, wenn kein Bild bei ebay gefunden werden konnte [(rel. oder absoluter HTML-Pfad)]',

'CURRENCY' => 'Standard-Währung',

'REFRESHBUTTONS'   => 'Extra Aktualisierungs-Icons auf welcher Ebene',
'REFRESHBUTTONS>0' => 'Keine extra Icons',
'REFRESHBUTTONS>1' => 'Nur auf Kategorie-Ebene',
'REFRESHBUTTONS>2' => 'Auf Gruppen- und Kategorie-Ebene',
'REFRESHBUTTONS>3' => 'Auf Auktions-, Gruppen- und Kategorie-Ebene',

'COUNTDOWN'   => 'Zähle Auktions-Restzeit mittels JS herunter',
'COUNTDOWN>0' => 'Nein (statische Zeit)',
'COUNTDOWN>1' => 'Nur nächstendende Auktion',
'COUNTDOWN>2' => 'Alle Auktionen',

'POPUPEDIT' => 'Benutze Popup-Dialoge zum Ändern bzw. für Lösch-Bestätigung',

# -----------------------------------------------------------------------------
));