<?php
/**
 * Rewrite urls
 *
 * @ingroup    Module-Analyse
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license
 * @version    $Id$
 */
class esf_Plugin_Module_Analyse extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('BuildMenu');
  }

  /**
   *
   */
  public function BuildMenu() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    // require valid login
    if (!esf_User::isValid() OR !Request::check('auction')) return;

    // link as sub-item to auctions
    esf_Menu::addModule( array( 'module' => 'analyse', 'id' => 10 ));

    ModuleRequireModule( 'Analyse', 'Auction', '0.4.0' );

    Event::dettach($this);
  }
}

Event::attach(new esf_Plugin_Module_Analyse);