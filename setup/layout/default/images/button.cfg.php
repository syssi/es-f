<?php
/**
 * button.cfg.dist.php
 *
 * Template for default settings, replaces button.ini since version 1.1.0
 *
 * Just copy this file to button.cfg.php, uncomment and configure your defaults
 *
 * LICENSE:
 *
 * This library is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * You should have received a copy of the GNU General Public License along
 * with this library; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package    button-php
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2008 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.1.0
 * @since      File available since Release 1.1.0
 */
 
/** --------------------------------------------------------------------------
 * Template image file name
 *
 * default: button.gif
 */
//$CFG['i'] = 'button.gif';

/** --------------------------------------------------------------------------
 * Button text to display
 */
//$CFG['t'] = '';

/** --------------------------------------------------------------------------
 * Alignment of text on button, effect only on buttons with a greater width
 * than required
 *
 * possible values: [l|c|r]  for left/center/right
 *
 * default: c
 */
//$CFG['a'] = 'c';

/** --------------------------------------------------------------------------
 * Extra space before AND after text in pixel
 *
 * default: 0
 */
//$CFG['o'] = 0;

/** --------------------------------------------------------------------------
 * Left offset from text in pixel
 *
 * default: 0
 */
//$CFG['l'] = 0;

/** --------------------------------------------------------------------------
 * Right offset from text in pixel
 *
 * default: 0
 */
//$CFG['r'] = 0;

/** --------------------------------------------------------------------------
 * Fixed with of resulting button in pixel
 *
 * default: 0 (fit to text width)
 */
$CFG['w'] = 200;

/** --------------------------------------------------------------------------
 * Font   - 1..5 => system font 1..5
 *        - TTF-fontname[,height] ; e.g. "arial" or "verdana,10"
 *
 * default: 0 (auto detect a system font by available button height)
 */
//$CFG['f'] = 0;

/** --------------------------------------------------------------------------
 * Text color as 6 byte hex value
 */
$CFG['c'] = 'FCC204';

/** --------------------------------------------------------------------------
 * Shaddow color
 *
 * hex value[,offset]
 *
 * default: '' (no shadow)
 * default offset: 1
 */
$CFG['s'] = '000,-1';

/** --------------------------------------------------------------------------
 * DELTA X: move text horizontal of from center
 */
//$CFG['x'] = 0;

/** --------------------------------------------------------------------------
 * DELTA Y: move text vertical from center
 */
$CFG['y'] = -1;

/** --------------------------------------------------------------------------
 * Mime type of resulting button image
 *
 * possible values: [|gif|jpg|png]
 *
 * default: same type as template image file
 */
//$CFG['m'] = '';

/** --------------------------------------------------------------------------
 * NO CACHING of button images at all
 */
//$CFG['n'] = FALSE;
