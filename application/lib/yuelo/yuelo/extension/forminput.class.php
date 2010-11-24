<?php
/**
 * Generic input field code, used also by other form input extensions
 *
 * @usage
 * @code
 * Content:
 *  $template->assign('INPUTTYPE', 'text');
 *  $template->assign('INPUTNAME', 'name');
 *  $template->assign('INPUTVALUE', 'value');
 *  $template->assign('INPUTCLASS', 'class');
 *  $template->assign('INPUTEXTRA', 'size="3"');
 *
 * Template:
 *   {forminput:[INPUTTYPE[,INPUTNAME[,INPUTVALUE[,INPUTCLASS[,INPUTEXTRA]]]]]}
 *
 * Output:
 *   &lt;input type="text" id="name" name="name" class="class" value="value" size="3"&gt;
 * @endcode
 *
 * @ingroup  Extensions
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Extension_FormInput extends Yuelo_Extension {

  /**
   * Generic input field code, used also by other form input extensions
   *
   * @param string $type Input type
   * @param string $name Input tag name
   * @param string $value Tag value
   * @param string $class Tag class name
   * @param string $extra Extra tag code
   * @return string
   */
  public static function Process() {
    @list($type, $name, $value, $class, $extra) = func_get_args();
    if ($type)  $type = 'type="'.$type.'"';
    if ($name) {
      $id = $name;
      $name = 'name="'.$name.'"';
      // only one input with the same id on one page
      $id_ = strtolower($id);
      if (!isset(self::$Ids[$id_]))
        self::$Ids[$id_] = TRUE;
        $name = 'id="'.$id.'" ' . $name;
      }
    }    
    if ($value) $value = 'value="'
                       . str_replace('"', '&quot;',
                                     stripslashes(htmlspecialchars(trim($value))))
                       . '"';
    if ($class) $class = 'class="'.$class.'"';
    return sprintf('<input %s %s %s %s %s>', $type, $class, $name, $value, $extra);
  }

  private static $Ids = array();

}
