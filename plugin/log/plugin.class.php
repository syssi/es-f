<?php
/** @defgroup Plugin-Log Plugin Log

*/

/**
 * Plugin Log
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-Log
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class esf_Plugin_Log extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('Log');
  }

  /**
   *
   */
  public function Log( $log ) {
    $ts = date($this->TimeStamp);
    if (is_array($log)) {
      foreach ($log as $id => $val) {
        if (is_array($val)) {
          $this->Log($val);
          return;
        }
        if (!is_int($id)) $val = $id . ': ' . $val;
        error_log($ts.': '.$val."\n", 3, $this->File);
      }
    } else {
      error_log($ts.': '.$val."\n", 3, $this->File);
    }
  }

}

Event::attach(new esf_Plugin_Log);