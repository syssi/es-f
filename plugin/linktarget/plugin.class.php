<?php
/**
 * Auction statistics
 *
 * @ingroup    Plugin-LinkTarget
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class esf_Plugin_LinkTarget extends esf_Plugin {

  /**
   * Class constructor
   */
  public function __construct() {
    parent::__construct();
    $this->RegExp = sprintf('~<a[^>]+%s.*?'.'>~is', $this->RegExp);
  }

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('OutputFilter');
  }

  /**
   *
   */
  public function OutputFilter( &$output ) {
    if (preg_match_all($this->RegExp, $output, $matches)) {
      foreach ($matches[0] as $id => $match) {
        // use unused index for replacement ;-)
        $matches[-1][$id] = substr($match, 0, -1).' target="_blank" '.substr($match, -1);
      }
      $output = str_replace($matches[0], $matches[-1], $output);
    }
  }

}

Event::attach(new esf_Plugin_LinkTarget);