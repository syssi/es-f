<?php
/*
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
 */

defined('_ESF_OK') || die('No direct call allowed.');

/**
 * Input:
 *   $param
 *
 * Return:
 *   $result = array( 'rc'=>0, 'result'=>'', 'msg'=>'' );
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