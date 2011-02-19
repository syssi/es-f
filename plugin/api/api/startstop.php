<?php
/**
 * Start / stop an auction snipe
 *
 * @ingroup    Plugin-API
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */

defined('_ESF_OK') || die('No direct call allowed.');

# -----------------------------------------------------------------------------
/**
 * Input:
 *   $param
 *
 * Return:
 *   $result = array( 'rc'=>0, 'result'=>'', 'msg'=>'' );
 */
function API_StartStop ( $params, &$result ) {
  if (isset($params['group'])) {
    if (esf_Auctions::PID($params['group'])) {
      Loader::Load(dirname(__FILE__).'/stop.php');
      API_Stop($params, $result);
    } else {
      Loader::Load(dirname(__FILE__).'/start.php');
      API_Start($params, $result);
    }
  } else {
    $result['rc'] = -1;
    $result['msg'] = 'Missing parameter "group"!';
  }
}

/**
 * API_StartStop_Info
 */
function API_StartStop_Info () {
return <<<EOT
API function to switch auction group between running and not running.<br>
<tt>Usage: ...?api=startstop&amp;group=&lt;group name&gt;</tt>
EOT;
}