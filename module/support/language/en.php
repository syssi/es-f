<?php
/**
 * @package Module-Support
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
Translation::Define('SUPPORT', array(
# -----------------------------------------------------------------------------

'Title'                     => 'Support',

# menu
'Menu'                      => 'Support',
'Menuhint'                  => 'Hints about support and system informations',

'Support'                   => 'file:'.dirname(__FILE__).'/support.en.htm',

# -----------------------------------------------------------------------------
));
# -----------------------------------------------------------------------------
