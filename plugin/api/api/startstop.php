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