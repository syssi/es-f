<?php
/**
 * @package Module-Auction
 * @subpackage Languages
 * @desc German language definitions
 */

defined('_ESF_OK') || die('No direct call allowed.');

// ---------------------------------------------------------------------------
Translation::Define('Auction', array(
// ---------------------------------------------------------------------------

'Title'                     => 'Auktionen',
'TitleIndex'                => 'Auktionen',
'TitleEditAuction'          => 'Auktion bearbeiten',
'TitleEditGroup'            => 'Biet-Gruppe bearbeiten',
'TitleDelete'               => 'Auktion löschen',

// menu
'Menu'                      => 'Auktionen',
'MenuHint'                  => 'Liste der Auktionen',

'MenuDeleteEnded'           => 'Bereinigen',
'MenuHintDeleteEnded'       => 'Alle beendeten Auktionen löschen',

// table
'Image'                     => 'Bild',
'Auction'                   => 'Auktion',
'Auctions'                  => 'Auktionen',
'EndTime'                   => 'endet',
'Ended'                     => 'Beendet',
'Endless'                   => 'Ohne Endedatum',
'RemainingTime'             => 'Restzeit',
'NoOfBids'                  => 'Gebote',
'NoBids'                    => '--',
'CurrentPrice'              => 'aktueller Preis',
'HighBidder'                => 'Höchstbieter',
'Currency'                  => 'Währung',
'Shipping'                  => 'Versand',
'ShippingFree'              => 'Frei Haus',
'Seller'                    => 'Verkäufer',
'GetFromEbay'               => 'von eBay lesen',
'Quantity'                  => 'Menge',
'Bid'                       => 'Gebot',
'MyBid'                     => 'Mein Gebot',

'URL'                       => 'URL',
'ImageRotate'               => 'Bild drehen',
'ImageRotateNo'             => 'nein',
'ImageRotateLeft'           => ' nach links ',
'ImageRotateRight'          => ' nach rechts ',

'AddAuctions'               => 'Auktionen hinzufügen',
'Price'                     => 'Preis',
'Comment'                   => 'Kommentar',
'Piece'                     => 'St.',
'Available'                 => 'verfügbar',
'Category'                  => 'Kategorie',
'Categories'                => 'Kategorien',
'NoCategory'                => 'ohne Kategorie',
'AuctionBid'                => "Auktions-\nGebot",
'GroupBid'                  => 'Gruppen-Gebot',
'GroupSingle'               => 'Grundpreis',
'GroupTotal'                => 'Gesamtpreis (incl. Versand)',
'Group'                     => 'Gruppe',
'Groups'                    => 'Gruppen',
'InclCategory'              => 'incl. Kategorie',
'ShouldReadAutomatic'       => 'Diese Daten sollten bereits automatisch ermittelt worden sein',
'ImageUrl'                  => 'Bild-URL',
'YourAuctionSettings'       => 'Deine Auktions-Einstellungen',
'DifferentFromGroup'        => 'abweichend vom Gruppengebot',
'BidNow'                    => 'Platziere Gebot jetzt',
'UseToBreakBuyNow'          => 'benutze dies z.B. um einen Sofort-Kauf zu verhindern',
'AddAuctions'               => 'Auktionen hinzufügen',

'Actions'                   => 'Aktionen',
'EditAuction'               => 'Auktion ändern',
'EditGroup'                 => 'Gruppe ändern',

'ConfirmDelete'             => 'Löschen bestätigen',
'PleaseConfirmDelete'       => 'Bitte Löschung bestätigen!',

'DeleteAuction'             => 'Auktion löschen',
'DeleteAuctionsOfGroup'     => 'Alle Auktionen der Gruppe [%s] werden gelöscht.',
'CleanupAuctions'           => 'Beendete Auktionen löschen',
'DeleteGroup'               => 'Alle Auktionen der Gruppe löschen',

'CategoryIgnoredOnGroup'    => 'Wenn eine vorhandene Gruppe ausgewählt wird, wird die Kategorie aus dieser Gruppe übernommen!',
// %1$s => category
'ShowAuctionsOfCategory'    => 'Zeige/verberge alle Auktionen der Kategorie "%s"',

'ShowMultiAddRow'           => 'Zeige Zeile zum Hinzufügen von Auktionen',

// %1$s : item id, %2$s : item name
'ConfirmDeleteAuction'      => 'html:Möchten Sie die Auktion<br><br><strong>%2$s</strong><br><br>wirklich löschen?',
'ConfirmCleanupAuctions'    => 'Möchten Sie wirklich alle beendeten Auktionen löschen?',

'Yes'                       => 'Ja',
'No'                        => 'Nein',

'Rename'                    => 'Umbenennen',
'Start'                     => 'Gruppe starten',
'Stop'                      => 'Gruppe anhalten',
'Startstop'                 => 'Gruppe starten/anhalten',
'Save'                      => 'Sichern',
'NoBidDefinedYet'           => 'Noch kein Gebot angegeben!',

'StartGroup'                => 'Gruppe starten',
'GroupComment'              => 'Gruppen-Kommentar',
'EsniperIsRunning'          => 'esniper läuft und ist bereit zu bieten...',

'ClickForEdit'              => 'Klick für "inline" Bearbeitung',

'RemoveGroupWillSplit'      => 'Wenn Sie den Gruppenname leeren, werden die Auktionen wieder einzeln geführt.',

'EditSaveAuction'           => 'Sichern',
'EditSaveGroup'             => 'Sichern',
'EditStartGroup'            => 'Starten',
'EditCancel'                => 'Abbrechen',
'Cancel'                    => 'Abbrechen',

'MarkedAuctions'            => 'markierte '."\n".'Auktionen',
'Or'                        => 'oder',
'Select'                    => 'Auswahl',
'MoveToCategory'            => 'Nach Kategorie verschieben',
'MoveToGroup'               => 'Nach Gruppe verschieben',
'SetImage'                  => 'Bild ersetzen',
'SetComment'                => 'Kommentar setzen',
'SetBid'                    => 'Auktions-Gebot setzen',
'SetCurrency'               => 'Währung zuweisen',
'Refresh'                   => 'Auktion aktualisieren',
'RefreshAuctions'           => 'Auktionen aktualisieren',
'RefreshCategory'           => 'Auktionen der Kategorie aktualisieren',
'RefreshGroup'              => 'Auktionen der Gruppe aktualisieren',
'Go'                        => 'Los',

// errors
'Error'                     => 'FEHLER',
'NoItem'                    => 'Keine Auktionsnummer angegeben!',
'GroupBidUpdated'           => 'Gebot der Gruppe [%1$s] wurde von der Auktion übernommen.',
'MissingAmount'             => 'Kein Gebot eingegeben!',

// messages
// %1$s : Auction title
'AuctionSaved'              => 'Auktion [%1$s] gespeichert.',
// %1$s : Auction title
'AuctionDeleted'            => 'Auktion [%1$s] gelöscht.',
// %1$d : Count of deleted auctions
'AuctionsDeleted'           => '%1$d Auktionen gelöscht.',
// %1$d : Count of deleted auctions
'DeletedEnded'              => array( 'Eine beendete Auktion gelöscht.',
                                      '%1$d beendete Auktionen gelöscht.' ),
'NoDeletedEnded'            => 'Keine beendeten Auktionen gefunden.',
'GroupSaved'                => 'Biet-Gruppe gespeichert.',
// %1$s : group
'GroupStarted'              => 'Biet-Gruppe [%1$s] gestartet.',
// %1$s : group, %2$s : group hash (for anchor)
'GroupNotStarted'           => 'html:Biet-Gruppe [%1$s] nicht gestartet! (weitere Informationen im <a href="?module=protocol#%2$s">Auktions-Logfile</a>)',
'AuctionBiddedNow'          => 'Auktions-Gebot plaziert.',
// %1$s : group
'GroupStopped'              => 'Biet-Gruppe [%1$s] gestoppt.',
// %1$s : group, %2$s : category
'MovedGroupToCategory'      => 'Alle Auktionen der Gruppe [%1$s] wurden in die Kategorie [%2$s] verschoben.',
// %1$s : auction id, %2$s : auction name
'RefreshedJustEnded'        => 'html:Soeben beendete Auktion "%2$s" wurde aktualisiert.',
// %1$s : auction id, %2$s : auction name
'SkipMonitored'             => 'Ignoriere bereits erfasste Auktion [%1$s] "%2$s"',
// %1$s : auction id
'ErrorRetrieving'           => 'Fehler beim Lesen der HTML-Daten für Auktion [%1$s]',
// %1$s : auction id
'ErrorRetrievingTryAgain'   => 'html:Es kann sein, dass '.ESF_TITLE.' die ebay-Seite nur nicht korrekt lesen konnte, in dem Fall solltest Du '
                             . 'es <a href="?module=auction&amp;action=mrefresh&amp;auctions=%1$s">einfach nochmal versuchen</a> :-)',
// %1$s : temp. dir., %2$s : auction id, %3$s : url to create bug tracker item
'ReportAuctionFiles'        => 'html:
Wenn dieser Fehler öfter vorkommt:
<div class="li">gehe bitte in dieses Verzeichnis: %1$s</div>
<div class="li">packe folgende Dateien zusammen: %2$s.*.html</div>
<div class="li"><a href="%3$s">erstelle einen Bug-Report auf SourceForge</a> und hänge diese Dateien an</div>',

// %s : new version
'Upgrade'                   => 'Aktualisiere alle Auktionen auf Version %s',
'Upgraded'                  => 'Auktionen wurden auf Version %s aktualisiert.',

// drag'n'drop
'Dragger'                   => 'Ziehe dies auf ein Drag\'n\'Drop Ziel, um die Auktion zu verschieben.',
'Droptarget'                => 'Drag\'n\'Drop Ziel',
'DropRemoveGroup'           => 'Lege eine Auktion hier ab, um sie aus ihrer Gruppe zu entfernen.',
'DropCategory'              => 'Lege eine Auktion hier ab, um sie in diese Kategorie zu verschieben.',
'DropGroup'                 => 'Lege eine Auktion hier ab, um sie in diese Gruppe zu verschieben.',

// -----------------------------------------------------------------------------
));
// -----------------------------------------------------------------------------

// -----------------------------------------------------------------------------
Translation::Define('AuctionHelp', array(
// -----------------------------------------------------------------------------
'Category'                  => 'html:
Kategorie|
<p>Die Kategorie (nur in <tt>'.ESF_TITLE.'</tt>) ist eine einfache Klassifizierung zur Unterscheidung von Auktions-Gruppen, also z.B. "Computer", "Musik" etc.</p>
<p>Die Verwendung dieses Levels ist optional, allerdings werden sonst alle Auktionen nach Enddatum (durcheinander) sortiert gelistet!</p>
<p>Die Auktionen werden also zuerst nach Kategorien sortiert.</p>',

'Group'                     => 'html:
Gruppe|
<p>Die Bietgruppe (<tt>'.ESF_TITLE.'</tt> und esniper) ist exakt die gleiche Funktionalität wie in esniper, d.h. mehrere ähnliche Auktionen werden zusammen beboten bis die "angeforderte" Menge erreicht ist.</p>
<p>Der Preis der Gruppe gilt für alle Auktionen der Gruppe, die keinen eigenen Preis zugewiesen bekommen haben (z.B. bei anderen Währungen als der Standard-Währung notwendig).</p>
<p>Die Auktionen werden nun (innerhalb der Kategorie) nach Gruppen sortiert.</p>',

'AddMultipleAuctions'       => 'html:
Mehrere Auktion auf einmal hinzufügen|
<div class="li">Gibt die Auktionsnummern getrennt durch eine &quot;Nicht-Ziffer&quot; wie z.B. Leerzeichen oder Komma ein.</div>
<div class="li">Wähle ggf. eine vorhandene Kategorie / Gruppe aus den Auswahllisten oder gibt eine Neue ein.</div>
<div class="li">Lege Menge und Gebot fest und sichere / starte die Auktionen.</div>',
// -----------------------------------------------------------------------------
));
