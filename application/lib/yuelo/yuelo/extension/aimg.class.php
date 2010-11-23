<?php

require_once dirname(__FILE__).'/a.class.php';

/**
 * Return a well formated \<a> tag with an \<img> tag inside
 *
 * @usage
 * @code
 * Content:
 *   $template->assign('URL',         'http://google.de');
 *   $template->assign('URLIMAGE',    'google.gif');
 *   $template->assign('URLIMAGEALT', '[Google]');        // optional
 *   $template->assign('URLCLASS',    'extern');          // optional
 *   $template->assign('URLTITLE',    'Suche...');        // optional
 *   $template->assign('URLTARGET',   '_blank');          // optional
 *
 * Template:
 *   {aimg:URL,URLIMAGE[,URLIMAGEALT[,URLCLASS[,URLTITLE[,URLTARGET]]]]}
 *
 * Output:
 *   &lt;a href="http://google.de" title="Suche..." target="_blank"&gt;&lt; src="google.gif" alt="[Google]"&gt&lt;/a&gt;
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_AImg extends Yuelo_Extension_A {

  /**
   * Return a well formated a tag with an image tag inside
   *
   * @static
   * @param string $url Link URL
   * @param string $name Tag name
   * @param string $class Class name for tag
   * @param string $title Tag title
   * @param string $target Link target
   * @return string
   */
  public static function Process() {
    @list($url, $img, $alt, $class, $title, $target) = func_get_args();
    return parent::Process($url, '<img src="'.$img.'" alt="'.$alt.'">', $class, $title, $target);
  }

}