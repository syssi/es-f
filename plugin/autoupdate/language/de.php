<?php
/**
 *
 * @package Plugins
 * @subpackage ESF-Version
 * @desc German language definitions
 */

defined('_ESF_OK') || die('No direct call allowed.');

// ---------------------------------------------------------------------------
Translation::Define('AutoUpdate', array(
// ---------------------------------------------------------------------------

// %1$s : version, %2$s : comment, %3$s : URL
'LatestAppRelease' => 'html:Die neueste <tt>'.ESF_TITLE.'</tt> Version ist &nbsp; <tt>%1$s</tt><br>'
                    . '%2$s<br>'
                    . 'Du kannst <a href="%3$s">upgraden</a>!',

// %1$d : Files count
'FilesUpdatable'   => 'Es sind %1$d Datei(en) aktualisierbar!',

'FileNotWritable'  => 'Datei ist nicht beschreibbar, erforderlich für automatischen Update!',

'UpdateNow'        => 'Jetzt aktualisieren',

'UpdateFiles'      => 'Aktualisiere Dateien:',

// ---------------------------------------------------------------------------
));