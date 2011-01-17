<?php
/**
 * Set some layout specific data
 *
 * @ingroup    Module-Auction
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license
 * @version    $Id: v2.4.1-46-gfa6b976 - Sat Jan 15 13:42:37 2011 +0100 $
 */
class esf_Plugin_Large_Module_Auction extends esf_Plugin {

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
    TplData::set('ThumbSize', 80);
  }

}

Event::attach(new esf_Plugin_Large_Module_Auction);