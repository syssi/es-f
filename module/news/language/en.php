<?php
/**
 * @package Module-Process
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
Translation::Define('News', array(
// ---------------------------------------------------------------------------

'Title'                     => 'Project news',
'TitleIndex'                => 'Project news',

// menu
'Menu'                      => 'News',
'MenuHint'                  => 'Project news',

'Category'                  => 'Category',

// ---------------------------------------------------------------------------
));