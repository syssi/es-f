<?php
/**
 * Add an auction direct from an eBay page
 *
 * @ingroup    Plugin-API
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */

defined('_ESF_OK') || die('No direct call allowed.');

/**
 * @param string $params API parameter
 * @param array &$result array( 'rc'=>0, 'result'=>'', 'msg'=>'' );
 */
function API_Snipe( $params, &$result ) {
  if (strtoupper($_SERVER['REQUEST_METHOD']) == 'GET' AND isset($params['url'])) {
    $params['snipe'] = $params['url'];
    unset($params['url']);
    Core::Redirect(Core::URL(array('module'=>'snipe', 'params'=>$params), TRUE));
  }
}

/**
 * API function informations
 * 
 * @return string
 */
function API_Snipe_Info() {
  $bm = esf_Template::getInstance()->Render('inc.snipe', FALSE, 'module/index/layout');

  return <<<EOT
    API function to add an auction to |es|f|<br>
    <tt>Usage: ...?api=snipe&amp;url=&lt;auction url&gt;[&amp;title=&lt;auction title&gt;[&amp;comment=&lt;auction title&gt;]]</tt><br>
    Usable e.g. via a bookmarklet: $bm
EOT;
}
