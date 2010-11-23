<?php
/**
 * @package Module-Login
 * @subpackage Languages
 * @desc English language definitions
 */

defined('_ESF_OK') || die('No direct call allowed.');

// ---------------------------------------------------------------------------
Translation::Define('Register', array(
// ---------------------------------------------------------------------------

'Title'                     => 'Register',

'ApplyForAccount'           => 'No Account yet?',
'Apply'                     => 'Apply now!',

// form
'AccountPasswords'          => 'Define here your eBay account and your eBay and '.ESF_TITLE.' passwords.',

'Account'                   => 'Your eBay account',
'AccountComment'            => 'This will be also your '.ESF_TITLE.' account.',

'EbayPassword'              => 'Your eBay password (twice)',
'EbayPasswordComment'       => 'This password will be stored always encrypted and is only decryptable to plain text with your '.ESF_TITLE.' password.',

'EsfPassword'               => 'Define your '.ESF_TITLE.' password (twice)',
'EsfPasswordComment'        => 'With this password and your eBay user you can login into '.ESF_TITLE,

'MsgForAdmin'               => 'An optional message for the admin about your registration request',
'Register'                  => 'Register',
'ThankYouForRegister'       => 'Thank you for your registration.',

// messages
'FieldMissing'              => 'Please fill out all fields!',
'PasswordsNotEqual'         => 'Your passwords for eBay or '.ESF_TITLE.' are not the same!',

// admin

// %1$d : count
'RegistrationsPending'      => array (
                                 'There is one pending registration!',
                                 'There are %1$d pending registrations!'
                               ),

'RegistrationsEdit'         => 'Edit them here.',

'PendingRegistrations'      => 'Pending registrations',

'RejectAccount'             => 'reject',
'AcceptAccount'             => 'accept',
'IgnoreAccount'             => 'ignore yet',

// %1$s : user name
'AcceptedUser'              => 'Saved accepted user [%1$s]',
// %1$s : user name
'RejectedUser'              => 'Removed registration request of user [%1$s].',

'Process'                   => 'Process requests',

// ---------------------------------------------------------------------------
));