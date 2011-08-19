<?php
/**
 * Post processor to add width and height attributes to image tags if not defined
 *
 * Usage example:
 * @code
 * @endcode
 *
 * @ingroup    Processors
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2006-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 * @revision   $Rev$
 */
class Yuelo_Processor_ImageSize extends Yuelo_Processor {

  /**
   * Post processing method to add width and height attributes to image tags
   * if not defined
   *
   * @param string &$page Template code
   * @return void
   */
  public function PostProcess( &$page ) {
    if (!preg_match_all('~<img .*?[^?]>~si', $page, $imgs)) return;

    foreach ($imgs[0] as $id => $tag) {
          // no width and height tags
      if (!(preg_match('~width=[\'"]?\d+~i', $tag) AND
            preg_match('~height=[\'"]?\d+~i', $tag)) AND
          // get image file name
          preg_match('~src=([\'"])([^\'"]+)\\1~i', $tag, $src) AND
          // is a readable file and has content
          is_readable($src[2]) AND filesize($src[2]) AND
          // is really an image
          $ImageSize = @GetImageSize($src[2])) {
        $tag  = preg_replace('~<img ~i','$0'.$ImageSize[3], $tag);
        $page = str_replace($imgs[0][$id], $tag, $page);
      }
    }
  }
}