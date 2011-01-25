<?php
/**
 * Delete auction
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
function API_Delete ( $params, &$result ) {
  if (isset($params['item'])) {
    if ($item = $params['item'] AND !$a = esf_Auctions::Delete($item, FALSE)) {
      if (!Registry::get('esf.SimulateDelete')) {
        $result['rc'] = 1;
        $result['msg'] = 'Error deleting auction "'.$item.'"!';
      }
    }
  } else {
    $result['rc'] = -1;
    $result['msg'] = 'Missing parameter "item"!';
  }
}

/**
 * API_Start_Info
 */
function API_Delete_Info () {
return <<<EOT
API function to delete an auction.<br>
<tt>Usage: ...?api=delete&amp;item=&lt;auction&gt;</tt>
EOT;
}