<?php
/**
 * Transform \n\n to </p><p>, \n to <br> and sourounds message with <p>...</p>
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 * @revision   $Rev$
 */
class Translation_Filter_p implements Translation_Filter {

  /**
   * Transform \n\n to </p><p>, \n to <br> and sourounds message with <p>...</p>
   *
   * @param string &$message  The message to modify
   * @param string $namespace The namespace the message comes from
   * @param string $id        The ID which the message stands for
   */
  public function process( &$message, $namespace, $id ) {
    $message = str_replace("\r", '', $message);
    $message = str_replace("\n\n", '</p><p>', $message);
    $message = '<p>' . nl2br($message) . '</p>';
  }

}