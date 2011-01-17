<?php
/**
 * Creates pagination links
 *
 * @ingroup    Pagination
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-42-g440d05f - Sun Jan 9 21:40:58 2011 +0100 $
 */
class Pagination {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   *
   */
  public function __construct( $options=array() ) {
    $this->options = array_merge($this->options, $options);
  }

  /**
   *
   */
  public function Show() {
    echo $this->HTML();
  }

/*
#pagination {
  text-align: center;
  padding: 0.75em 0;
  font-family: monospace;
  font-size: 1.25em;
}

#pagination img {
  vertical-align: middle;
}

#pagination select {
  margin: 0 5px;
}

#pagination .page {
  font-size: 1.4em;
}
*/

  /**
   *
   */
  public function HTML() {
    $p = '';
    $tpl = $this->options['template'];

    foreach ($this->options['linkparameters'] as $key=>$value)
      $p .= $key . '=' . urlencode($value) . '&amp;';

    $page = $this->getPage();
    $link = '<a href="?'.$p.$this->options['parameter'].'=%1$d" title="'
          . str_replace('{PAGE}', '%1$d', $this->options['pagehint']).'">%2$s</a>';

    // wide step
    $wide = $this->options['widestep'] < 0
          ? $this->options['pages'] * -$this->options['widestep'] / 100
          : $this->options['widestep'];

    // show first/last
    $first = (strpos($this->options['template'], '{FIRST') !== FALSE);
    $last = (strpos($this->options['template'], '{LAST') !== FALSE);
    // make form
    $form = ((strpos($this->options['template'], '{INPUT')  !== FALSE) OR
             (strpos($this->options['template'], '{SELECT') !== FALSE));

    //                    {TAG[:param1[,param2]]}
    if (preg_match_all('~\{([A-Z]+):?([^}]*?)\}~s', $tpl, $tags, PREG_SET_ORDER)) {

      foreach ($tags as $tag) {
        // Tramsform NOT HTML tags into HTML
        $tagname = strtoupper($tag[1]);
        if (strpos($tagname, 'HTML') !== FALSE) {
          $param = $tag[2];
          $tagname = str_replace('HTML', '', $tagname);
        } else {
          $param = htmlspecialchars($tag[2]);
        }
        
        $str = '';

        switch ($tagname) {

          // ----------------------
          case 'FIRST':
            if ($page > 1)
              $str = sprintf($link, 1, sprintf($this->param($param, '|<'), 1));
            break;

          // ----------------------
          case 'PREVWIDE':
            $h = $page - $wide;
            if ($h > 1)
              $str = sprintf($link, $h, sprintf($this->param($param, '<<'), 1));
            break;

          // ----------------------
          case 'PREVSHORT':
            $h = explode(',', $this->param($param, '%d, | '));
            if (empty($h[1])) $h[1] = ' | ';
            $low = $page - $this->options['shortcount'];
            if ($low < 1) $low = 1;
            // if show {FIRST}, down't show first page here
            if ($first AND $low == 1) $low = 2;
            $links = array();
            for ($i=$low; $i<$page; $i++)
              $links[] = sprintf($link, $i, sprintf($h[0], $i));
            $str = implode($h[1], $links);
            if ($str) $str .= $h[1];
            break;

          // ----------------------
          case 'PREV':
            if ($page > 1)
              $str = sprintf($link, $page-1, $this->param($param, '<'));
            break;

          // ----------------------
          case 'PAGE':
            $str = sprintf($this->param($param, '%d'), $page);
            break;

          // ----------------------
          case 'PAGES':
            $str = sprintf($this->param($param, '%d'), $this->options['pages']);
            break;

          // ----------------------
          case 'NEXT':
            if ($page < $this->options['pages']) {
              $str = sprintf($link, $page+1, $this->param($param, '>'));
            }
            break;

          // ----------------------
          case 'NEXTSHORT':
            $h = explode(',', $this->param($param, '%d, | '));
            if (empty($h[1])) $h[1] = ' | ';
            $high = $page + $this->options['shortcount'];
            if ($high > $this->options['pages']) $high = $this->options['pages'];
            // if show {LAST}, down't show last page here
            if ($last AND $high == $this->options['pages']) $high = $this->options['pages'] - 1;
            $links = array();
            for ($i=$page+1; $i<=$high; $i++)
              $links[] = sprintf($link, $i, sprintf($h[0], $i));
            $str = implode($h[1], $links);
            if ($str) $str = $h[1] . $str;
            break;

          // ----------------------
          case 'NEXTWIDE':
            $h = $page + $wide;
            if ($h < $this->options['pages'])
              $str = sprintf($link, $h, sprintf($this->param($param, '>>'), 1));
            break;

          // ----------------------
          case 'LAST':
            if ($page < $this->options['pages'])
              $str = sprintf($link, $this->options['pages'], sprintf($this->param($param, '>|'), $this->options['pages']));
            break;

          // ----------------------
          case 'INPUT':
            $str = '<input style="display:inline" name="'.$this->options['parameter'].'">';
            break;

          // ----------------------
          case 'SELECT':
            $param = $this->param($param, 1);
            $step = ceil($param < 0 ? $this->options['pages'] * -$param / 100 : $param);

            $str = '<select style="display:inline" name="'.$this->options['parameter'].'" '
                 . 'onchange="this.form.submit()">';

            for ($i=1; $i<=$this->options['pages']; $i+=$step)
              $str .= '<option'.(($i==$page)?' selected':'').'>'.$i.'</option>';

            // add last page if not yet in select
            if ($i > $this->options['pages'])
              $str .= '<option'.(($this->options['pages']==$page)?' selected':'').'>'
                    . $this->options['pages'].'</option>';

            $str .= '</select>';
            break;

          // ----------------------
          case 'SUBMIT':
            if ($form) {
              $str = '<input style="display:inline" type="submit" value="'.$this->param($param, 'Go').'">';
            }
            break;

          // ----------------------
          // unknown tag
          default:
            trigger_error(__CLASS__.': Unknown tag "'.$tag[0].'"');
            break;

        } // switch

        if ($str)
          $str = sprintf('<span class="%s">%s</span>', strtolower($tagname), $str );

        $tpl = str_replace($tag[0], $str, $tpl);
      }
    }

    if ($form)
      $tpl = '<form style="display:inline" method="get">' . $tpl . '</form>';

    return $tpl;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  private $options = array(

    /**
     * Page count
     */
    'pages' => 1,

    /**
     * URL parameter
     */
    'parameter' => '_page',

    /**
     * Count of prev./next page links
     */
    'shortcount' => 5,

    /**
     * Step range for wide prev./next links
     * - negative value: in percent
     * - positive value: absolute step width
     */
    'widestep' => -5,

    /**
     *
     */
    'pagehint' => 'Go to page {PAGE}',

   /**
     * Template for pagination
     *
     * Possible tags:
     * <code>
     * {FIRST:|<}              // default
     * {FIRST:%d}
     * {PREVWIDE:<<,100}       // pos. value: absolute step width
     * {PREVWIDE:<<,-5}        // neg. value: procentual, here step width 5%
     * {PREVSHORT:%d, | }      // implode links with 2nd parameter
     * {PREV:<}
     * {PAGE:%d}
     * {PAGES:%d}
     * {NEXT:>}
     * {NEXTSHORT:%d, | }      // implode links with 2nd parameter
     * {NEXTWIDE:>>,100}       // pos. value: absolute step width
     * {NEXTWIDE:>>,-5}        // neg. value: procentual, here step width 5%
     * {LAST:>|}               // default
     * {LAST:%d}
     * {INPUT}
     * {SELECT:1}              // pos. value: absolute step width
     * {SELECT:-5}             // neg. value: procentual, here step width 5%
     * {SUBMIT:Go}';
     * </code>
     *
     * To put HTML in the tag (e.g. images), use this notation:
     *
     * <code>
     * {FIRSTHTML:<img...>}
     * {PREVWIDEHTML:<img...>}
     * {PREVSHORTHTML:<img...>}
     * {PREVHTML:<img...>}
     * {PAGEHTML:<img...>}
     * {PAGESHTML:<img...>}
     * {NEXTHTML:<img...>}
     * {NEXTSHORTHTML:<img...>}
     * {NEXTWIDEHTML:<img...>}
     * {LASTHTML:<img...>}
     * </code>

     */
    'template' => '{FIRST} {PREVWIDE} {PREVSHORT} {PREV} {PAGE} [{PAGES}] {NEXT}
                   {NEXTSHORT} {NEXTWIDE} {LAST} | {INPUT} {SELECT} {SUBMIT}',

    /**
     * Extra link parameters
     */
     'linkparameters' => array(),
  );

  /**
   *
   */
  private function getPage() {
    $page = isset($_REQUEST[$this->options['parameter']])
          ? intval($_REQUEST[$this->options['parameter']])
          : 1;
    if ($page < 1) $page = 1;
    if ($page > $this->options['pages']) $page = $this->options['pages'];
    return $page;
  }

  /**
   * Tag parameter default
   */
  private function param( $param, $default ) {
    if (!$param) $param = htmlspecialchars($default);
    return $param;
  }

  /**
   * Remove not required whitespaces
   *
   * @param string $str
   * @return string
   */
  private function condense( $str ) {
    return preg_replace('~\s\s+~', ' ', $str);
  }

}