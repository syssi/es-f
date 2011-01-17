<?php
/**
 * Yuelo Extension help
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2006-2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-43-g9eb0fbd - Tue Jan 11 21:51:29 2011 +0100 $
 */

/**
 * Yuelo Extension help
 *
 * Usage Example:
 * <pre>
 * Template:
 *   {help:"WhatsThis"}
 *
 * Output:
 *   &lt;a href="?module=help&action=popup&topic=WhatsThis"&gt;&lt;img src="image..."&gt;&lt;/a&gt;
 * </pre>
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2006-2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 */
class Yuelo_Extension_Help extends Yuelo_Extension {

  /**
   * Yuelo Extension help
   *
   * @static
   * @param string $textid Id to translate
   * @param string $params Parameters optional
   * @return string
   */
  public static function Process() {
    @list($topic) = func_get_args();
    return '<span class="helplink">'
         . '<a href="'
         . Core::URL(array('module'=>'help', 'action'=>'topic', 'params'=>array('t'=>$topic)))
         . '" onclick="return openWin(this.href,480,320)">'
         . '<img style="cursor:help" src="layout/images/help-topic.gif">'
         . '</a>'
         . '</span>';
  }

}