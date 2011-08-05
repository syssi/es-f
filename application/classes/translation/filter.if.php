<?php
/**
 * Translation filter interface
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 * @revision   $Rev$
 */
interface Translation_Filter {

  /**
   * Manipulate the message
   *
   * @param string &$message  The message to modify
   * @param string $namespace The namespace the message comes from
   * @param string $id        The ID which the message stands for
   */
  public function process( &$message, $namespace, $id );

}