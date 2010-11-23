<?php
/**
 * Return a well formated \<a> tag
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('URL',       'http://google.de');
 *   $template->assign('URLNAME',   'Google.de');       // optional
 *   $template->assign('URLCLASS',  'extern');          // optional
 *   $template->assign('URLTITLE',  'Suche...');        // optional
 *   $template->assign('URLTARGET', '_blank');          // optional
 *
 * Template:
 *   {a:URL[,URLNAME[,URLCLASS[,URLTITLE[,URLTARGET]]]]}
 *
 * Output:
 *   \<a href="http://google.de" title="Suche..." target="_blank">Google.de\</a>
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_A extends Yuelo_Extension {

  /**
   * Return a well formated a tag
   *
   * @param string $url Link URL
   * @param string $name Tag name
   * @param string $class Class name for tag
   * @param string $title Tag title
   * @param string $target Link target
   * @return string
   */
  public static function Process() {
    @list($url, $name, $class, $title, $target) = func_get_args();
    if (strpos($url, '://') === FALSE) $url = 'http://'.$url;
    if (!$title) $title = $name;
    if (!$name) $name = $url;

    return sprintf('<a href="%s" class="%s" title="%s" target="%s">%s</a>',
                   $url, $class, $title, $target, $name);
  }

}