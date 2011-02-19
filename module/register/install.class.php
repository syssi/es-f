<?php
/**
 * Module Register installer
 *
 * @ingroup    Module
 * @ingroup    Module-Register
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class esf_Install_Module_Register extends esf_Install {

  /**
   * Module installation
   *
   * @return boolean
   */
  public function Install() {
    return $this->CreateDirectory('reg');
  }
}