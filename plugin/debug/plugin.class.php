<?php
/** @defgroup Plugin-Debug Plugin Debug

Add Debug & Trace links to system menu

*/

/**
 * Plugin Debug
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-Debug
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_Debug extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function __construct() {
    parent::__construct();
    if (_TRACE) register_shutdown_function(array($this,'PageEnded'));
  }

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LanguageSet', 'BuildMenu', 'BeforeOutputHtmlEnd', 'PageEnded');
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
      'img'   => 'plugin/debug/images/debug-'.$dbg.'.gif',
      'alt'   => 'D',
      'style' => 'image',
      'id'    => 9995 )
    );

    esf_Menu::addSystem( array(
      'url'   => '?TRACE',
      'title' => Translation::get('Debug.MenuTrace'),
      'hint'  => Translation::get('Debug.MenuTraceHint'),
      'img'   => 'plugin/debug/images/trace.gif',
      'alt'   => 'T',
      'style' => 'image',
      'id'    => 9996 )
    );

    // remove from event stack
    if (!_DEBUG) Event::dettach($this);
  }

  /**
   *
   */
  function BeforeOutputHtmlEnd() {
    // Here is no check for active _DEBUG required, because this method is only
    // called, if _DEBUG is active, otherwise the plugin was dettached after
    // menu building.
    Yryie::Finalize();
    $html = '<div id="Yryie_title"
                  style="margin-top:1em;cursor:pointer;text-align:center;padding:0.25em"
                  onclick="$(\'Yryie_wrap\').toggle()"><tt><strong>Yryie</strong></tt></div>
             <div id="Yryie_wrap" style="height:40em;overflow:auto;display:none">'
          .  Yryie::getCSS() . Yryie::getJS() . Yryie::Render(TRUE)
          .  '</div>';
    TplData::set('END_OF_PAGE', $html);
  }

  /**
   *
   */
  function PageEnded() {
    if (_TRACE) Yryie::Save(_TRACE, TRUE);
  }

}

Event::attach(new esf_Plugin_Debug, 100);