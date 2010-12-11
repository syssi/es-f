<?php
/**
 * Set some layout specific data
 *
 * @package    Plugin-AuctionLayout
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class esf_Plugin_DarkNight_Module_Auction extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('OutputStart');
  }

  /**
   *
   */
  function OutputStart( &$tpldata ) {
    TplData::set('ImgBorderColor', '#555');
  }

}

Event::attach(new esf_Plugin_DarkNight_Module_Auction);
