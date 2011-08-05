<?php
/**
 * Load translation from file
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 * @revision   $Rev$
 */
class Translation_Filter_File implements Translation_Filter {

  /**
   * Load translation from file
   *
   * @param string &$message  The message to modify
   * @param string $namespace The namespace the message comes from
   * @param string $id        The ID which the message stands for
   */
  public function process( &$message, $namespace, $id ) {
    $file = $message;
    $message = (is_file($file) AND is_readable($file))
             ? file_get_contents($file)
             : 'Missing translation file "'.$file.'" for '.$namespace.'::'.$id;
  }

}