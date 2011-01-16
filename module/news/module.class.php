<?php
/**
 * News module
 *
 * @ingroup    Module-News
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class esf_Module_News extends esf_Module {

  /**
   * @return array Array of actions handled by the module
   */
  public function handles() {
    return array('index');
  }

  /**
   *
   */
  public function IndexAction() {

    Loader::Load(dirname(__FILE__).'/classes/rdfrssNoDomReader.class.php');

    $news = new RdfRssNoDomReader;
    $news->cachepath = TEMPDIR;

    foreach ($this->URLs as $url) {
      $data = array();

      $count = !empty($url['count']) ? $url['count'] : 100;

      if ($news->getFile($url['url']) AND $items = $news->getLinkArray($count)) {
        $ok = TRUE;
        foreach ($items as $item) {
          $desc = nl2br($item['description']);
          $desc = preg_replace('~<br.*>\s*<br.*>~isU', '</p><p>', $desc);
          $desc = preg_replace('~<br.*>~iU', ' ', $desc);
          $data[] = array(
            'TITLE'       => $item['title'],
            'LINK'        => Core::Link($url, $name),
            'DESCRIPTION' => '<p>'.$desc.'</p>',
            'URL'         => $item['link'],
            'LINK'        => Core::Link($item['link'], $item['title']),
            'PUBDATE'     => $item['pubdate'],
            'CATEGORY'    => $item['category'],
          );
        }
      } else {
        $ok = FALSE;
      }

      TplData::add('News', array(
        'URL'   => $url['url'],
        'NAME'  => $url['name'],
        'LINK'  => $news->getHeader(),
        'ERROR' => !$ok,
        'DATA'  => $data,
      ));
    }
  }

}
