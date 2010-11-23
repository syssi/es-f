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

# -----------------------------------------------------------------------------
/**
 * Input:
 *   $group
 *
 * Return:
 *   $result = array( 'rc'=>0, 'result'=>'', 'msg'=>'' );
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
