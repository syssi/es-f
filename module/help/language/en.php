<?php
/**
 * @package Module-Help
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
Translation::Define('Help', array(
# -----------------------------------------------------------------------------

'Title'                     => 'Help',
'TitleShow'                 => 'Show',
'TitleEdit'                 => 'Edit',

// menu
'Menu'                      => 'Help',
'ModuleMenu'                => 'Help',

'Menuhint'                  => 'Help for modules and plugins',
'ModuleMenuhint'            => 'Module help',

'Description'               => 'Description',

'Back'                      => '<< back',
'Edit'                      => 'Edit',

'Save'                      => 'Save',
'Saved'                     => 'File saved.',

'CloseWindow'               => 'Close window',

# -----------------------------------------------------------------------------
));