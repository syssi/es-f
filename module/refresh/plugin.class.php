<?php
/**
 * Module Refresh plugin
 *
 * @ingroup    Plugin
 * @ingroup    Module-Refresh
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_Module_Refresh extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LanguageSet', 'BuildMenu');
  }

  /**
   *
   */
  function BuildMenu() {
    if (!esf_User::isValid()) return;

    if (Registry::get('esf.Module') == 'auction')
      esf_Menu::addModule( array('module' => 'refresh') );

    if (Registry::get('esf.Module') != 'refresh')
      Session::set('Module.Refresh.Module', Registry::get('esf.Module'));
  }

}

Event::attach(new esf_Plugin_Module_Refresh, 100);