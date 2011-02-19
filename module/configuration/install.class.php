<?php
/**
 * Module Configuration installer
 *
 * @ingroup    Module
 * @ingroup    Module-Configuration
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class esf_Install_Module_Configuration extends esf_Install {

  public function Info () {
    return '
      <p>This module can change the configuration of modules and plugins via web browser.</p>
      <p>The user specific configuration will stored in
         <tt>local/&lt;Scope&gt;/&lt;Extension&gt;/config.ini</tt>, with:</p>
      <div class="li"><tt>Scope      =&gt;  module|plugin</tt></div>
      <div class="li"><tt>Extension  =&gt;  module name|plugin name</tt></div>
    ';
  }

}