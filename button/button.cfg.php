<?php
/*
 * button.cfg.dist.php
 *
 * Template for default settings, replaces button.ini since version 1.1.0
 *
 * Just copy this file to button.cfg.php, uncomment and configure your defaults
 *
 * @package    button-php
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2008 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.1.0
 * @since      File available since Release 1.1.0
 */

/* --------------------------------------------------------------------------
 * Template image file name
 *
 * default: button.gif
 */
//$CFG['i'] = 'button.gif';
#$CFG['i'] = 'btn.png';

/** --------------------------------------------------------------------------
 * Button text to display
 */
#$CFG['t'] = '';

/* --------------------------------------------------------------------------
 * Alignment of text on button, effect only on buttons with a greater width
 * than required
 *
 * possible values: [l|c|r]  for left/center/right
 *
 * default: c
 */
#$CFG['a'] = 'c';

/* --------------------------------------------------------------------------
 * Extra space before AND after text in pixel
 *
 * default: 0
 */
#$CFG['o'] = 0;

/* --------------------------------------------------------------------------
 * Left offset from text in pixel
 *
 * default: 0
 */
#$CFG['l'] = 0;

/* --------------------------------------------------------------------------
 * Right offset from text in pixel
 *
 * default: 0
 */
#$CFG['r'] = 0;

/* --------------------------------------------------------------------------
 * Fixed with of resulting button in pixel
 *
 * default: 0 (fit to text width)
 */
//$CFG['w'] = 0;
$CFG['w'] = 150;

/* --------------------------------------------------------------------------
 * Font   - 1..5 => system font 1..5
 *        - TTF-fontname[,height] ; e.g. "arial" or "verdana,10"
 *
 * default: 0 (auto detect a system font by available button height)
 */
#$CFG['f'] = 0;

/* --------------------------------------------------------------------------
 * Text color as 6 byte hex value
 *
 * default: black "000000"
 */
#$CFG['c'] = '000000';
$CFG['c'] = 'FFFFFF';

/* --------------------------------------------------------------------------
 * Shaddow color
 *
 * hex value[,offset]
 *
 * default: '' (no shadow)
 * default offset: 1
 */
#$CFG['s'] = '';
#$CFG['s'] = 'DDDDDD';
$CFG['s'] = '202020';

/* --------------------------------------------------------------------------
 * DELTA X: move text horizontal of from center
 *
 * default: 0
 */
#$CFG['x'] = 0;

/* --------------------------------------------------------------------------
 * DELTA Y: move text vertical from center
 *
 * default: 0
 */
#$CFG['y'] = 0;
$CFG['y'] = 1;

/* --------------------------------------------------------------------------
 * Mime type of resulting button image
 *
 * possible values: [|gif|jpg|png]
 *
 * default: same type as template image file
 */
#$CFG['m'] = '';

/* --------------------------------------------------------------------------
 * NO CACHING of button images at all
 *
 * default: FALSE
 */
#$CFG['n'] = FALSE;
#$CFG['n'] = TRUE;
