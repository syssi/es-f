<?php
/** @defgroup Module-Analyse Module Analyse

*/

/**
 * Module Analyse
 *
 * @ingroup    Module
 * @ingroup    Module-Analyse
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Module_Analyse extends esf_Module {

  /**
   *
   */
  public function Before() {
    parent::Before();
    $this->group = $this->Request('group');
    esf_Auctions::Load();
  }

  /**
   *
   */
  public function IndexAction() {
    foreach (esf_Auctions::$Groups as $group => $data) {
      $groupname = ($data['a'] > 1 OR !isset(esf_Auctions::$Auctions[$group]))
                 ? $group : esf_Auctions::$Auctions[$group]['name'];
      TplData::add('Groups', array(
        'GROUP'     => $group.'|-',
        'GROUPNAME' => $groupname . ' (' . Translation::get('Analyse.WithoutShipping') . ')',
        'COUNT'     => $data['a'],
        'CATEGORY'  => $data['cat'],
        'SHOWURL'   => Core::URL(array('action'=>'show', 'params'=>array('group'=>$group.'|-'), 'anchor'=>'diagram')),
      ));
      TplData::add('Groups', array(
        'GROUP'     => $group.'|+',
        'GROUPNAME' => $groupname . ' (' . Translation::get('Analyse.WithShipping') . ')',
        'COUNT'     => $data['a'],
        'CATEGORY'  => $data['cat'],
        'SHOWURL'   => Core::URL(array('action'=>'show', 'params'=>array('group'=>$group.'|+'), 'anchor'=>'diagram')),
      ));
    }
  }

  /**
   *
   */
  public function ShowAction() {
    list($this->group, $shipping) = explode('|', $this->group);
    if (!isset(esf_Auctions::$Groups[$this->group])) {
      $this->forward();
      return;
    }
    TplData::set('GroupName', (esf_Auctions::$Groups[$this->group]['a'] > 1 OR
                               !isset(esf_Auctions::$Auctions[$this->group]))
                            ? $this->group : esf_Auctions::$Auctions[$this->group]['name']);
    TplData::set('Width',  Registry::get('Module.Analyse.Width'));
    TplData::set('Height', Registry::get('Module.Analyse.Height'));

    $AuctionData = $bids = array();
    $cnt = $mid = $hmid = 0;
    foreach (esf_Auctions::$Auctions as $item => $auction) {
      if (esf_Auctions::getGroup($auction) != $this->group) continue;

      $amount = ($shipping != '+')
              ? $auction['bid']
              : $auction['bid'] + $auction['shipping'];
      if ($auction['ended']) {
        $bids[] = $amount;
        $cnt++;
        $mid += $amount;
        $hmid += 1/$amount;
      }

      $AuctionData['endts'][] = $auction['endts'];
      $AuctionData['bid'][]   = $amount;
      $AuctionData['bids'][]  = $auction['bids'];

      $TplData = $auction;
      $TplData['AUCTIONURL'] = sprintf(Registry::get('ebay.ShowUrl'), $item);
      $TplData['END'] = strftime(Registry::get('Format.DateTimeS'), $auction['endts']);
      TplData::add('Auctions', $TplData);
    }

    if ($cnt) {
      $mid = $mid / $cnt;
      $hmid = $cnt / $hmid;
    }

    $data = serialize(array( TplData::get('Width'), TplData::get('Height'),
                             esf_Auctions::$Groups[$this->group]['b'],
                             Translation::get('Analyse.MyBid'),
                             $mid,
                             Translation::get('Analyse.Average'),
                             $hmid,
                             Translation::get('Analyse.HarmonicAverage'),
                             $AuctionData ));
    if (function_exists('gzcompress')) $data = gzcompress($data, 9);
    TplData::set('Data', base64_encode($data));

    if (!empty($bids)) {
      // at least one ended auction in group
      TplData::set('Variants', array());
      $min = min($bids);
      $max = max($bids);
      $range = $max - $min;

      $round = ceil($range * 0.1); // plus 10%

      $min = floor($min/$round)*$round;
      if ($min < 0) $min = 0;
      $max = ceil($max/$round)*$round;

      $splitrange = checkR('split', 50);
      TplData::set('SplitRange', $splitrange);

      $rows = $this->TplDataChance($min, $max, $bids, $this->calcChanceWithRange($min, $max, $bids, $splitrange), $bestrow);
      TplData::add('Variants', array(
        'CHANCE_MESSAGE_DESC' => Translation::get('Analyse.ChanceMessageDesc1', $this->group, $splitrange),
        'ROWS' => $rows,
        'BEST' => array(
          'ROW'    => $bestrow,
          'LOWER'  => $rows[$bestrow]['LOWER'],
          'UPPER'  => $rows[$bestrow]['UPPER'],
          'CHANCE' => $rows[$bestrow]['CHANCE'],
        ),
      ));

      $rows = $this->TplDataChance($min, $max, $bids, $this->calcChanceMinimal($min, $max, $bids), $bestrow);
      TplData::add('Variants', array(
        'CHANCE_MESSAGE_DESC' => Translation::get('Analyse.ChanceMessageDesc2'),
        'ROWS' => $rows,
        'BEST' => array(
          'ROW'    => $bestrow,
          'LOWER'  => $rows[$bestrow]['LOWER'],
          'UPPER'  => $rows[$bestrow]['UPPER'],
          'CHANCE' => $rows[$bestrow]['CHANCE'],
        ),
      ));
    }
  }

  /**
   *
   */
  public function ShowMultiAction() {
    if (empty($this->group)) {
      $this->forward();
      return;
    } elseif (count($this->group) == 1) {
      $this->group = reset($this->group);
      $this->forward('show');
      return;
    }

    foreach ($this->group as $group) {
      list($group, ) = explode('|', $group);
      if (!isset(esf_Auctions::$Groups[$group])) {
        Messages::Error('Missing group: '.$group);
        continue;
      }
    }

    $width  = Registry::get('Module.Analyse.WidthMulti');
    $heigth = Registry::get('Module.Analyse.HeightMulti');
    TplData::set('WIDTH', $width);
    TplData::set('HEIGHT',$heigth );

    $gdata = $cnt = $hmid = array();
    foreach (esf_Auctions::$Auctions as $item => $auction) {
      $ag = esf_Auctions::getGroup($auction);
      foreach ($this->group as $id) {
        list($group, $shipping) = explode('|', $id);
        if ($ag == $group) {
          $amount = ($shipping != '+')
                  ? $auction['bid']
                  : $auction['bid'] + $auction['shipping'];
          if (!isset($cnt[$ag])) $cnt[$id] = 0;
          if (!isset($hmid[$ag])) $hmid[$id] = 0;
          if ($auction['ended']) {
            $cnt[$id]++;
            $hmid[$id] += 1/$amount;
          }
          $gdata[$id]['endts'][] = $auction['endts'];
          $gdata[$id]['bid'][]   = $amount;
          $gdata[$id]['bids'][]  = $auction['bids'];
        }
      }
    }

    foreach ($gdata as $id => $data) {
      list($group, $shipping) = explode('|', $id);
      if ($cnt[$id]) $hmid[$id] = $cnt[$id] / $hmid[$id];
      $_data = array(
        $width, $heigth,
        esf_Auctions::$Groups[$group]['b'],
        Translation::get('Analyse.MyBid'),
        0, NULL,
        $hmid[$id],
        Translation::get('Analyse.HarmonicAverage'),
        $data
      );
      $_data = serialize($_data);

      if (function_exists('gzcompress')) $_data = gzcompress($_data, 9);

      $gname = (esf_Auctions::$Groups[$group]['a'] > 1 OR
                !isset(esf_Auctions::$Auctions[$group]))
             ? $group
             : esf_Auctions::$Auctions[$group]['name'];
      $st = ($shipping != '+') ? 'WithoutShipping' : 'WithShipping';
      $gname .= ' (' . Translation::get('Analyse.'.$st) . ')';

      $TplData[] = array(
        'GROUP'     => $group,
        'GROUPNAME' => $gname,
        'SHOWURL'   => Core::URL(array('action'=>'show', 'params'=>array('group'=>$group.'|'.$shipping), 'anchor'=>'diagram')),
        'DATA'      => base64_encode($_data),
      );
    }

    TplData::set('Groups', $TplData);
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   * Requested group(s)
   */
  private $group;

  /**
   * Calc auction win chance by a split limit
   *
   * Split price range until a range holds less or equal $break percent
   * of auctions
   *
   * @param integer $min
   * @param integer $max
   * @param integer $bids
   * @param integer $break
   * @return array
   */
  private function calcChanceWithRange( $min, $max, $bids, $break=20 ) {
    $range = $max - $min;
    $ranges = 0;
    $countbids = count($bids);

    do {
      $ranges++;
      $res = array_fill(1, $ranges, 0);
      foreach ($bids as $bid) {
        $r = ceil(($bid-$min) / ($range/$ranges));
        if ($r) $res[$r]++;
      }
    } while (max($res)*100/$countbids >= $break AND max($res) > 1 AND
             // safty belt again endless loops, in case min. 2 auctions
             // have the same end price!
             $ranges <= $countbids);

    return $res;
  }

  /**
   * Calc auction win chance with maximal price range split
   *
   * @param integer $min
   * @param integer $max
   * @param integer $bids
   * @return array
   */
  private function calcChanceMinimal( $min, $max, $bids ) {
    $range = $max - $min;
    $ranges = 0;
    $res = array(0);

    do {
      $ranges++;
      $saveres = $res;
      $res = array_fill(1, $ranges, 0);
      foreach ($bids as $bid) {
        $r = ceil(($bid-$min) / ($range/$ranges));
        if ($r) $res[$r]++;
      }
    } while (min($res) > 1 AND max($res) > 1);

    // use result saved before
    return $saveres;
  }

  /**
   * Convert chance data into template data
   *
   * @param integer $min
   * @param integer $max
   * @param integer $bids
   * @param array $res Price ranges
   * @param array &$bestrow Best chance
   * @return array
   */
  private function TplDataChance( $min, $max, $bids, $res, &$bestrow ) {
    ksort($res);
    $range = $max - $min;
    $ranges = count($res);
    $bidcount = count($bids);
    $maxres = max($res);

    $TplData = array();
    $chance = 0;
    $bestrow = $bestchance = FALSE;

    foreach ($res as $id => $cnt) {
      $chance += $cnt*100/$bidcount;
      $_tpldata = array(
        'LOWER'         => $min + $range/$ranges*($id-1),
        'UPPER'         => $min + $range/$ranges*$id-0.01,
        'COUNT'         => $cnt,
        'COUNT_PERCENT' => ($cnt/$bidcount*100),
        'CHANCE'        => $chance,
        'WIDTH'         => floor($cnt*100/$maxres),
      );
      if (!$bestrow OR $_tpldata['COUNT'] >= $bestchance) {
        $bestrow = $id;
        $bestchance = $_tpldata['COUNT'];
      }
      $TplData[$id] = $_tpldata;
    }
    // correct last upper amount
    $TplData[$id]['UPPER'] += 0.01;

    return $TplData;
  }

}