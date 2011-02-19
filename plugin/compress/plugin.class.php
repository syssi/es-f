<?php
/** @defgroup Plugin-AuctionStats Plugin AuctionStats

*/

/**
 * Plugin Compress
 *
 * @ingroup    Plugin
 * @ingroup    Plugin-Compress
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class esf_Plugin_Compress extends esf_Plugin {

  /**
   *
   */
  public function __construct() {
    parent::__construct();
    if (DEVELOP) $this->RemoveComments = FALSE;
  }

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('OutputFilter', 'OutputFilterFooter');
  }

  /**
   *
   */
  public function OutputFilter( &$output ) {
    if (!$output) return;

    $this->Before += strlen($output);

    $tab   = '  ';
    $space = "\x01";
    $nl    = "\x02";

    $output = str_replace("\r", '', $output);

    // remove <!-- --> from script tags
    $output = preg_replace('~(<script[^>]*>)<!--(.*?)-->(</script>)~is', '$1$2$3', $output);
    // prevent <pre> tags from being reformatted
    $output = preg_replace('~<pre[^>]*>.*?<\\\?/pre>~ise',
                           '$this->MaskFormated(\'$0\',"'.$tab.'","'.$space.'","'.$nl.'")', $output);
    // prevent <tt> tags from being reformatted
    $output = preg_replace('~<tt[^>]*>.*?<\\\?/tt>~ise',
                           '$this->MaskFormated(\'$0\',"'.$tab.'","'.$space.'","'.$nl.'")', $output);
    // prevent <textarea> tags from being reformatted
    $output = preg_replace('~<textarea[^>]*>.*?<\\\?/textarea>~ise',
                           '$this->MaskFormated(\'$0\',"'.$tab.'","'.$space.'","'.$nl.'")', $output);
    // prevent <script> tags from being reformatted
    $output = preg_replace('~<script[^>]*>.*?<\\\?/script>~ise',
                           '$this->MaskScript("$0","'.$nl.'")', $output);
    // compress multiple spaces
    $output = preg_replace('~[ \t][ \t]+~', ' ', $output);
    // replace spaces between tags
    $output = preg_replace('~>\s+<~','><',$output);
    // re-break comments into separate lines
    $output = str_replace('><!--', ">\n<!--", $output);
    $output = str_replace('--><',  "-->\n<",  $output);
    // replace spaces before/after hard spaces
    $output = preg_replace('~\s+(\s&nbsp;)~','$1',$output);
    $output = preg_replace('~(&nbsp;\s)\s+~','$1',$output);

    if ($this->RemoveComments)
      $output = preg_replace('~<!--.*?-->~s', '', $output);

    // put xml and/or doctype definitions BEFORE <html...> in extra lines
    if (preg_match_all('/^(.*?)<html/', $output, $prehtml)) {
       preg_match_all('/<.*?[^-]>/', $prehtml[1][0], $prehtml);
       $output = preg_replace('/^.*?<html/', implode("\n", $prehtml[0])."\n".'<html', $output);
    }

    // un-mask <pre> conversions
    $output = str_replace($nl,    "\n", $output);
    $output = str_replace($space, ' ',  $output);

    $this->After += strlen($output);
  }

  /**
   *
   */
  public function OutputFilterFooter( &$output ) {
    if (!DEVELOP OR
        Registry::get('esf.contentonly') OR
        $this->Before == 0) return;

    $data = array(
      'before' => $this->FmtBytes($this->Before),
      'after'  => $this->FmtBytes($this->After),
      'ratio'  => $this->After / $this->Before * 100
    );

    $output .= $this->Render('content', $data);
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   * HTML size before compression
   */
  private $Before;

  /**
   * HTML size after compression
   */
  private $After;

  /**
   * Mask formated HTML code to prevent compression
   *
   * @param string $html
   * @param string $tab TAB replacement
   * @param string $space SPACE replacement
   * @param string $nl NewLine replacement
   * @return string
   */
  private function MaskFormated( $html, $tab, $space, $nl ) {
    $from = array("\t", "\n", '  ',            ' '    );
    $to   = array($tab, $nl,  $space.'&nbsp;', $space );
    $html = str_replace($from, $to, $html);
    return str_replace('\\"','"',$html);
  }

  /**
   * Mask script code to prevent compression
   *
   * @param string $html
   * @param string $nl NewLine replacement
   * @return string
   */
  private function MaskScript( $html, $nl ) {
    if (!$html) return '';

    // skip protocols like http:// etc.
    $script = preg_replace('~\s*(?<!:)//~', "\n".'//', $html);

    $script = explode("\n", $script);
    $html = array();
    $l = FALSE;

    foreach ($script as $line) {
      if (!$line = trim($line)) continue;
      if (preg_match('~^//~', $line)) {
        // comment line
        if ($l = trim($l)) {
          $html[] = $l;
          $l = FALSE;
        }
        $html[] = $line;
      } else {
        $l .= ' ' . $line;
      }
    }
    if ($l = trim($l)) $html[] = $l;

    $html = implode($nl, $html);
    $html = str_replace('\\\'', '\'', $html);

    return $html;
  }

  /**
   * Format a byte value to minimal unit
   *
   * @param integer $bytes
   * @param string $force Force to unit
   * @return string
   */
  private function FmtBytes( $bytes, $force=FALSE ) {
    // return number_format($bytes, 0, ',', '.').' Bytes';
    $unim = array('Byte', 'KByte', 'MByte', 'GByte', 'TByte', 'PByte');
    $id = 0;
    while ($bytes >= 1024 AND $force != $unim[$id]) {
      $id++;
      $bytes = $bytes / 1024;
    }
    return number_format($bytes, ($id ? 1 : 0), ',', '.').' '.$unim[$id];
  }

}

if (!isset($_GET['pretty'])) Event::attach(new esf_Plugin_Compress);