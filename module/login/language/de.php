<?php
/**
 *
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

// Menu
'Menu'                      => 'Login',
'MenuHint'                  => 'html:<tt>'.ESF_TITLE.'</tt>-Login mit Username und Passwort',
'Title'                     => 'Login',

// Form
'Login'                     => 'Login',
'Account'                   => 'eBay-Mitgliedsname',
'Select'                    => 'Auswahl',
'Password'                  => 'html:<tt>'.ESF_TITLE.'</tt>-Passwort',
'YourAccountAndPassword'    => 'Dein eBay-Mitgliedsname und <tt>'.ESF_TITLE.'</tt>-Passwort',
'Cookie'                    => 'Für einen Tag eingeloggt bleiben.',
'CookieHint'                => 'Aktivieren Sie diese Option nicht, wenn Sie an einem öffentlichen oder von mehreren Benutzern verwendeten Computer arbeiten.',

// Messages
'GoodMorning'               => 'Guten Morgen',
'GoodDay'                   => 'Guten Tag',
'GoodAfternoon'             => 'Guten Tag',
'GoodEvening'               => 'Guten Abend',

'Failed'                    => 'Unbekannter Benutzer oder falsches Passwort!',

# -----------------------------------------------------------------------------
));
# -----------------------------------------------------------------------------

Translation::Define('LoginHelp', array(
# -----------------------------------------------------------------------------
'Cookie'                    => 'html:
<div class="li">Beim Einloggen können Sie festlegen, dass Ihre
Einlog-Informationen einen Tag lang auf dem Computer gespeichert
bleiben sollen.</div>
<div class="li">Ihr Passwort bleibt so lange gespeichert, bis Sie sich ausloggen.
Dies ist selbst dann der Fall, wenn Sie die Internetverbindung trennen,
den Browser schließen oder den Computer ausschalten.</div>',
# -----------------------------------------------------------------------------
));