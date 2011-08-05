<?php
/**
 * Escapes the message
 *
 * This is the default filter, if no other flter was defined
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 * @revision   $Rev$
 */
class Translation_Filter_Escape implements Translation_Filter {

  /**
   * Escape the message
   *
   * @param string &$message  The message to modify
   * @param string $namespace The namespace the message comes from
   * @param string $id        The ID which the message stands for
   */
  public function process( &$message, $namespace, $id ) {
    $message = htmlspecialchars($message);
  }

}