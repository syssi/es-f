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
 * @package es-f
 * @subpackage Core
 * @desc Regular expressions for HTML parser of eBay pages
 */

/**
 * Parameters for HTML parser of eBay pages
 */
class ebayParser_co_uk extends ebayParser {

  /**
   * Class constructor
   */
  public function __construct() {
    parent::__construct('co_uk');
  }

  /**
   * individually convert end time string to a timestamp
   *
   * @param string $expr found string by reg. expression
   */
  public function getDetailEND( $dt ) {

    if (preg_match('~(\d+)-(\w{3})-(\d+)\s+(\d+):(\d+):(\d+)\s+(\w+)~', $dt, $ts)) {
      // translate month
      $ts[2] = array_search(strtoupper($ts[2]),
                            array(1=>'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN',
                                     'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'));

  # _dbg($ts);

      // correct year ???
      // if ($ts[3] < 100) $ts[3] += 2000;

      // local and ebay time zone
      $tz = array($this->Timezone, $ts[7]);

  # _dbg($tz);

      foreach ($tz as $t) {
        if (!isset($this->TimeZones[$t])) {
          trigger_error('Missing time zone definition: '.$t);
          $this->TimeZones[$t] = 0;
        }
      }

      // offset between ebay.com used time and local time plus undocumented server offset
      $offset = $this->TimeZones[$tz[1]] - $this->TimeZones[$tz[0]] - Registry::get('TZOFFSET');

  # _dbg($this->TimeZones[$tz[1]].' - '.$this->TimeZones[$tz[0]]);

      $ts = mktime($ts[4],$ts[5],$ts[6],$ts[2],$ts[1],$ts[3]);
      $ts -= $offset * 60*60;

  # _dbg(date('r',$ts));

      return $ts;
    }
    return FALSE;
  }

} // class