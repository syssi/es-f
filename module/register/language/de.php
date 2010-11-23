<?php
/**
* @package Module-Login
* @subpackage Languages
* @desc German language definitions
*/

defined('_ESF_OK') || die('No direct call allowed.');

// ---------------------------------------------------------------------------
Translation::Define('Register', array(
// ---------------------------------------------------------------------------

'Title'                     => 'Registrierung',

'ApplyForAccount'           => 'Noch kein Account?',
'Apply'                     => 'Jetzt beantragen!',

// form
'AccountPasswords'          => 'html:Definiere hier Deinen eBay Mitgliedsnamen und Deine eBay und <tt>'.ESF_TITLE.'</tt> Passwörter.',

'Account'                   => 'Dein eBay Mitgliedsname',
'AccountComment'            => 'html:Dies ist ebenfalls Dein <tt>'.ESF_TITLE.'</tt> Account.',

'EbayPassword'              => 'Dein eBay Passwort (2 mal)',
'EbayPasswordComment'       => 'html:Dieses Passwort wird immer verschlüsselt gespeichert und ist nur mit Deinem <tt>'.ESF_TITLE.'</tt> Passwort wieder entschlüsselbar.',

'EsfPassword'               => 'html:Wähle Dein <tt>'.ESF_TITLE.'</tt> Passwort (2 mal)',
'EsfPasswordComment'        => 'html:Mit diesem Passwort loggst Du Dich bei <tt>'.ESF_TITLE.'</tt> ein.',

'MsgForAdmin'               => 'Eine optionale Nachricht an den Administrator bzgl. Deines Registrierungsantrages',
'Register'                  => 'Registrierung',
'ThankYouForRegister'       => 'Vielen Dank für Deine Registrierung.',

// messages
'FieldMissing'              => 'Bitte alle Felder ausfüllen!',
'PasswordsNotEqual'         => 'html:Deine Passwörter für eBay oder <tt>'.ESF_TITLE.'</tt> sind nicht identisch!',

// admin

// %1$d : count
'RegistrationsPending'      => array(
                                 'Es gibt einen Registrierungswusch!',
                                 'Es gibt %1$d Registrierungswünsche!',
                               ),

'RegistrationsEdit'         => 'Hier bearbeiten.',

'PendingRegistrations'      => 'Registrierungswünsche',

'RejectAccount'             => 'ablehnen',
'AcceptAccount'             => 'akzeptieren',
'IgnoreAccount'             => 'zurückstellen',

// %1$s : user name
'AcceptedUser'              => 'Akzeptierter Benutzer [%1$s] wurde gesichert.',
// %1$s : user name
'RejectedUser'              => 'Registrierungswunsch des Benutzers [%1$s] wurde abgelehnt.',

'Process'                   => 'Registrierungswünsche bearbeiten',

// ---------------------------------------------------------------------------
));
