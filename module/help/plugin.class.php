<?php
/**
 * Module Help plugin
 *
 * @ingroup    Plugin
 * @ingroup    Module-Help
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class esf_Plugin_Module_Help extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('BuildMenu', 'OutputStart');
  }

  /**
   *
   */
  function BuildMenu() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    esf_Menu::addMain( array( 'module' => 'help', 'id' => 9990 ) );

    if (!in_array(Registry::get('esf.Module'), explode(',', 'help,'.$this->DisableHelp))) {
      esf_Menu::addModule( array(
        'module' => 'help',
        'action' => 'show',
        'params' => array('ext'=>'module-'.Registry::get('esf.module')),
        'id'     => 9999
      ));
    }
  }

  /**
   * Add css for help topic link
   */
  public function OutputStart() {
    TplData::add('HtmlHeader.raw',
      '<style type="text/css">
       span.helplink   { vertical-align:super; margin:0 0.5em; cursor:help; }
       span.helplink a { font-family:monospace; font-weight:bold; color:inherit !important; cursor:inherit; }
       </style>');
  }

}

Event::attach(new esf_Plugin_Module_Help);