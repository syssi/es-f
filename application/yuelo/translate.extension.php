<?php
/**
 * Translate a text Id using parameters
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2006-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 */
class Yuelo_Extension_Translate extends Yuelo_Extension {

  /**
   * Uses Translation::get()
   *
   * @return string
   */
  public static function Process() {
    return Translation::get(func_get_args());
  }

}