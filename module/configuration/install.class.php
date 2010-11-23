<?php
/**
 *
 */

/**
 * Class for Extension installation
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