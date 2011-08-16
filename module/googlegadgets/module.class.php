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
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Module_GoogleGadgets extends esf_Module {

  /**
   *
   */
  public function IndexAction() {
    TplData::set('Gadgets', $this->Gadgets);
  }

}