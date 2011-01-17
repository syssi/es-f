<?php
/**
 * Link to jump to next auction
 *
 * @ingroup    Plugin-NextAuction
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-42-g440d05f - Sun Jan 9 21:40:58 2011 +0100 $
 */
class esf_Plugin_NextAuction extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('Start', 'OutputStart', 'OutputFilterContent');
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
    if ($this->Active AND $this->Style == 1 AND $content = $this->content())
      Messages::Info($content, TRUE);
  }

  /**
   *
   */
  public function OutputFilterContent( &$output ) {
    if ($this->Active AND $this->Style == 0 AND
        $data['NEXTAUCTION'] = $this->content())
      $output = $this->Render('content', $data) . $output;
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

Event::attach(new esf_Plugin_NextAuction);