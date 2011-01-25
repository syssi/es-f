<?php
/**
@defgroup Plugin Plugins

*/

/**
 * Abstract Plugin class
 *
 * @ingroup    Plugin
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 * @version    $Id: v2.4.1-51-gfeddc24 - Sun Jan 16 21:09:59 2011 +0100 $
 */
abstract class esf_Plugin extends esf_Extension {

  /**
   *
   * /
  public function __construct() {
    parent::__construct();
    $this->Layouts = explode(',', $this->Layouts);
  }

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   * Parse plugin specific templates
   */
  protected function Render( $tpl='content', $data=array() ) {
    return esf_Template::getInstance()->Render(
      $tpl,
      DEVELOP,
      $this->ExtensionScope.'/'.$this->ExtensionName.'/layout',
      $data
    );
  }

}
