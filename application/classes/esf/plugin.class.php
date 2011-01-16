<?php
/**
 * Abstract Plugin class
 *
 * @package    Plugin
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */

/**
 *
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
