<?php
/**
 * @package Module-Analyse
 * @subpackage Languages
 * @desc German language definitions
 */

defined('_ESF_OK') || die('No direct call allowed.');

// ---------------------------------------------------------------------------
Translation::Define('ANALYSE', array(
// ---------------------------------------------------------------------------

# menu
'Menu'                      => 'Analysieren',
'Menuhint'                  => 'Analyse beendeter Auktion',
'Menuauction'               => 'Auktionen',
'Menuauctionhint'           => 'Liste der Auktionen',
'Menu2'                     => 'Auswahl',
'Menu2hint'                 => 'Bietgruppe auswählen',

'Title'                     => 'Analyse',
'TitleShow'                 => 'Anzeigen',
'TitleShowmulti'            => 'Mehrere anzeigen',

'Group'                     => 'Bietgruppe',
'Groups'                    => 'Bietgruppen',

'ShowAuctions'              => 'Auktionen anzeigen',
'HideAuctions'              => 'Auktionen verbergen',

'Analyse'                   => 'Bietgruppe(n) analysieren',

// table
'Auction'                   => 'Auktion',
'End'                       => 'Endzeit',
'Ended'                     => 'beendet',
'Bid'                       => 'Höchst-Gebot',
'Bids'                      => 'Gebote',

'MyBid'                     => 'Mein Gebot',
'Average'                   => 'Mittelwert',
'HarmonicAverage'           => 'harm. Mittel',

'Description'               => 'html:
Je größer und grüner ein Kreis ist, desto weniger Gebote wurden für die Auktion abgegeben.<br>
Je kleiner und roter ein Kreis ist, desto mehr Gebote wurden für die Auktion abgegeben.<br>
Noch nicht beendete Auktionen sind gelb.',

'ChanceHeader'              => 'Chancen zum Auktionsgewinn',
'ChanceHeaderVariant'       => 'Variante',

'PriceRange'                => 'Preisbereich',
'Auctions'                  => 'Auktionen',
'Chance'                    => 'Chance',

'ChanceMessageDesc1'        => 'html:
<form style="display:inline" method="post">
<input type="hidden" name="module" value="analyse">
<input type="hidden" name="action" value="show">
<input type="hidden" name="group" value="%s">
<input style="float:right;margin-left:20px" type="submit" value="Neu berechnen">
Der Preisbereich wird solange geteilt, bis ein Bereich weniger als
<input id="split" class="input" name="split" value="%d">%% der Auktionen enthält.
</form>',

'ChanceMessageDesc2'        => 'Der Preisbereich wird solange geteilt wie keine leeren Bereiche auftreten',

// %1$f : lower price, %2$f : upper price, %3$f : percent chance
'ChanceMessage'             => 'html:Der Preisbereich
<tt><strong>%1$.2f-%2$.2f</strong></tt> bietet eine Chance
von ca. <tt><strong>%3$.0f%%</strong></tt> auf einen Auktionsgewinn.',

// ---------------------------------------------------------------------------
));
