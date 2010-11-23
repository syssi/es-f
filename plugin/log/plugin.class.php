<?php
/**
 * @category   Plugin
 * @package    Plugin-Log
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Auction statistics
 *
 * @category   Plugin
 * @package    Plugin-Log
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
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