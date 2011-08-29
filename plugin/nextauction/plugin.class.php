<?php
/**
 * Link to jump to next auction
 *
 * @ingroup    Plugin-NextAuction
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-54-g83ea36d 2011-01-17 20:17:17 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_NextAuction extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LanguageSet', 'Start', 'OutputStart');
  }

  /**
   *
   */
  public function Start() {
    $this->Active = Request::check('auction', 'index') AND esf_User::isValid();
  }

  /**
   *
   */
  public function OutputStart() {
    if (!$this->Active OR !$content = $this->content()) return;

    switch ($this->Style) {
      case 0:
        $data['NEXTAUCTION'] = $content;
        TplData::add('Header_Center', $this->Render('content', $data));
        break;
      case 1:
        Messages::Info($content, TRUE);
        break;
    }
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   *
   */
  private $Active;

  /**
   *
   */
  private function content() {
    if (esf_Auctions::Count() < $this->Count) return FALSE;

    $time = $_SERVER['REQUEST_TIME'];
    $next = FALSE;

    foreach (esf_Auctions::$Auctions as $auction)
      // skip endless and ended auctions (endts == 0 or lower than actual time)
      if ($auction['endts']-$time > 0 AND
          (!$next OR $auction['endts'] < $next['endts']))
        $next = $auction;

    if (!$next) return FALSE;

    $prefix = 'nextauction_';
    $remain = sprintf('<tt id="%s%s">%s</tt>', $prefix, $next['item'],
                      esf_Auctions::Timef($next['endts']-$time));
    $html = Translation::get('NEXTAUCTION.MESSAGE', $next['item'], $next['name'], $remain, $next['category'])
           .'<script type="text/javascript">
             // <![CDATA[
             esf_CountDownExtra[esf_CountDownExtra.length] = "'.$prefix.'";
             // ]]>
             </script>'."\n";

    // remove empty category
    return preg_replace('~\(\s*\)~', '' ,$html);
  }

}

Event::attach(new esf_Plugin_NextAuction, -1);