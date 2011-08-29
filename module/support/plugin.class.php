<?php
/**
 * Support plugin
 *
 * @ingroup    Module-Support
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-54-g83ea36d 2011-01-17 20:17:17 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_Module_Support extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LanguageSet', 'BuildMenu', 'OutputStart');
  }

  /**
   *
   */
  function BuildMenu() {
    // disable on mobile layouts
    if (Session::get('Mobile') AND !$this->Mobile) return;

    // require valid login
    if (!esf_User::isValid()) return;

    esf_Menu::addSystem( array( 'module' => 'support', 'id' => 99 ) );
  }

  /**
   * Output HTML here, all data filled
   */
  public function OutputStart() {
    if (!Request::check('support', 'download')) return;

    $file = sprintf('%s-%s.htm', $_SERVER['HTTP_HOST'], date('YmdHi'));

    $style = file_get_contents('module/support/layout/style.css');
    if (preg_match('~/\* download >> \*/(.*?)/\* << download \*/~si', $style, $args))
      $style = $args[1];

    // parse own template
    $body = $this->Render('content.index', TplData::getAll());
    // extract infos to download
    if (preg_match('~<!-- download >> -->(.*)<!-- << download -->~si', $body, $args))
      $body = $args[1];

    $page = '<html><head><title>'.$file.'</title>'
           .'<style type="text/css">'.$style.'</style>'
           .'<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'
           .'</head><body>'.$body.'</body></html>';

    // send as HTML file
    Header('Content-Type: text/html');
    Header('Content-Disposition: attachment; filename="'.$file.'"');
    Header('Content-Length: '.strlen($page));
    die($page);
  }
}

Event::attach(new esf_Plugin_Module_Support);