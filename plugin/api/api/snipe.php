<?php
/**
 * Copyright (c) 2006-2009 Knut Kohl <knutkohl@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 * @package Plugin-API
 * @subpackage Snipe
 * @desc Add an auction direct from an eBay page
 */

defined('_ESF_OK') || die('No direct call allowed.');

/**
 * API function
 * 
 * Result structure:
 * <code>
 * array( 'rc' => 0, 'result' => '', 'msg' => '' )
 * </code>
 * 
 * @param string $params API parameter
 * @param mixed &$result Pointer to the result structure
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

?>