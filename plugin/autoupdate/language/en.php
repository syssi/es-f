<?php
/**
 *
 * @package Plugin-esfVersion
 * @subpackage Languages
 * @desc English language definitions
 */

defined('_ESF_OK') || die('No direct call allowed.');

// ---------------------------------------------------------------------------
Translation::Define('AutoUpdate', array(
// ---------------------------------------------------------------------------

// %1$s : version, %2$s : comment, %3$s : URL
'LatestAppRelease' => 'html:The newest <tt>'.ESF_TITLE.'</tt> version is &nbsp; <tt> %1$s</tt><br>'
                    . '%2$s<br>'
                    . 'You can <a href="%3$s">upgrade</a>!',

// %1$d : Files count
'FilesUpdatable'   => 'There are %1$d file(s) ready for update!',

'FileNotWritable'  => 'File is not writable, required for automatic update!',

'UpdateNow'        => 'Update now',

'UpdateFiles'      => 'Update files:',

// ---------------------------------------------------------------------------
));