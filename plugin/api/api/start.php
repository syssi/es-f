<?php
/**
 * Start an auction snipe
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
function API_Start( $params, &$result ) {
  if ($group = $params['group']) {
    $result['result'] = esf_Auctions::Start($group, FALSE);
    if ($result['rc'] = (int)($result['result'] === 0)) {
      if (!@esf_Auctions::$Groups[$group]['q'] OR !@esf_Auctions::$Groups[$group]['b']) {
        $result['msg'] = 'Missing bid quantity or bid amount!';
      } else {
        $result['msg'] = 'Can\'t start group "'.$group.'"!'."\n\n"
                        .'Please take a look into the protocol for more informations!';
      }
    } else {
      $result['msg'] = 'Group "'.$group.'" started!';
    }
  } else {
    $result['rc'] = -1;
    $result['msg'] = 'Missing parameter "group"!';
  }
}

/**
 * API_Start_Info
 */
function API_Start_Info () {
return <<<EOT
API function to start an auction group.<br>
<tt>Usage: ...?api=start&amp;group=&lt;group name&gt;</tt>
EOT;
}
