<?php
/**
 * Module Refresh installer
 *
 * @ingroup    Module
 * @ingroup    Module-Refresh
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class esf_Install_Module_Refresh extends esf_Install {

  /**
   * Module info
   *
   * @return string
   */
  public function Info() {
    return '
      <p>This module refreshes your auctions and read the actual data from ebay.</p>
      <p>If you configure module variable "MaxAge", auctions are refreshed auctomatic.</p>
    ';
  }
}