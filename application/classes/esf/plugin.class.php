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
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
abstract class esf_Plugin extends esf_Extension implements EventHandlerI {

  /**
   *
   * /
  public function __construct() {
    parent::__construct();
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
