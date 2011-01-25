<?php
/**
@defgroup Module-GoogleGadgets Module GoogleGadgets

*/

/**
 * Module GoogleGadget
 *
 * @ingroup    Module
 * @ingroup    Module-GoogleGadgets
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-51-gfeddc24 - Sun Jan 16 21:09:59 2011 +0100 $
 */
class esf_Module_GoogleGadgets extends esf_Module {

  /**
   * @return array Array of actions handled by the module
   */
  public function handles() {
    return array('index');
  }

  /**
   *
   */
  public function IndexAction() {
    TplData::set('Gadgets', $this->Gadgets);
  }

}