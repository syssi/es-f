<?php
/**
 * @category   Module
 * @package    Module-GoogleGadgets
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.1.0
 */

/**
 * Homepage module
 *
 * @category   Module
 * @package    Module-GoogleGadgets
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
 */
class esf_Module_GoogleGadgets extends esf_Module {

  /**
   *
   */
  public function IndexAction() {
    TplData::set('Gadgets', $this->Gadgets);
  }

}