<?php
/**
 * Add Debug & Trace links to system menu
 *
 * @category   Plugin
 * @package    Plugin-Debug
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Plugin_Debug extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('BuildMenu');
  }

  /**
   *
   */
  function BuildMenu() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    // cast to string "0" (FALSE) or "1" (TRUE)
    $dbg = (int)_DEBUG;

    esf_Menu::addSystem( array(
      'url'   => _DEBUG ? '?STOP' : '?DEBUG',
      'title' => Translation::get('Debug.MenuDebug_'.$dbg),
      'hint'  => Translation::get('Debug.MenuDebugHint_'.$dbg),
      'img'   => 'plugin/debug/images/debug-'.$dbg.'.png',
      'alt'   => 'D',
      'style' => 'image',
      'id'    => 9995 )
    );

    esf_Menu::addSystem( array(
      'url'   => '?TRACE',
      'title' => Translation::get('Debug.MenuTrace'),
      'hint'  => Translation::get('Debug.MenuTraceHint'),
      'img'   => 'plugin/debug/images/trace.png',
      'alt'   => 'T',
      'style' => 'image',
      'id'    => 9996 )
    );

    // remove from event stack
    Event::dettach($this);
  }

}

Event::attach(new esf_Plugin_Debug);