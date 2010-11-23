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
 *   $group
 *
 * Return:
 *   $result = array( 'rc'=>0, 'result'=>'', 'msg'=>'' );
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