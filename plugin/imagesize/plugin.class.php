<?php
/**
 * Add image sizes to img tags
 *
 * @ingroup    Plugin-ImageSize
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-52-g26c3418 - Mon Jan 17 19:09:09 2011 +0100 $
 */
class esf_Plugin_ImageSize extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('OutputFilter');
  }

  /**
   *
   */
  public function OutputFilter( &$output ) {
    if (preg_match_all('~<img.*?'.'>~si', $output, $res)) {
      foreach ($res[0] as $id => $tag) {
        if (!preg_match('~width=[\'"]?\d+~i', $tag) AND
            !preg_match('~noimagesize~i', $tag) AND
            preg_match('~src=([\'"])([^\'"]+)\\1~i', $tag, $src)) {

          $file = $src[2];
          $hash = md5($file);

          if (isset(self::$Images[$hash])) {
            $size = self::$Images[$hash];
          } else {
            $width  = preg_match('~style=[\'"].*?width\s*:\s*(\d+)px~i', $tag, $p)
                    ? $p[1] : FALSE;
            $height = preg_match('~style=[\'"].*?height\s*:\s*(\d+)px~i', $tag, $p)
                    ? $p[1] : FALSE;
            if ($width AND $height) {
              $size = sprintf('width="%d" height="%d"', $width, $height);
            } elseif ($size = @GetImageSize($file)) {
              $size = $size[3];
            } else {
              $size = FALSE;
            }
          }
          if ($size) {
            $tag = preg_replace('~<img~i','$0 '.$size, $tag);
            $output = str_replace($res[0][$id], $tag, $output);
          }
        }
      }
    }
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   * Cache image hashes
   */
  private static $Images = array();

}

Event::attach(new esf_Plugin_ImageSize);