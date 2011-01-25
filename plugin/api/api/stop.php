<?php
/**
 * Stop an auction snipe
 *
 * @ingroup    Plugin-API
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */

defined('_ESF_OK') || die('No direct call allowed.');

/**
 * @param string $params API parameter
 * @param array &$result array( 'rc'=>0, 'result'=>'', 'msg'=>'' );
 */
function API_Stop ( $params, &$result ) {
  if ($group = $params['group']) {
    $result['result'] = esf_Auctions::Stop($group, FALSE);
    if ($result['rc'] = (int)($result['result'] !== 0)) {
      $result['msg'] = 'Can\'t stop group "'.$group.'"!';
    } else {
      $result['msg'] = 'Group "'.$group.'" stopped!';
    }
  } else {
    $result['rc'] = -1;
    $result['msg'] = 'Missing parameter "group"!';
  }
}

/**
 * API_Stop_Info
 */
function API_Stop_Info () {
return <<<EOT
API function to stop an auction group.<br>
<tt>Usage: ...?api=stop&amp;group=&lt;group name&gt;</tt>
EOT;
}