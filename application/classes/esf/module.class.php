<?php
/** @defgroup Module Modules

*/

/**
 * Abstract Module class
 *
 * @ingroup    Module
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
abstract class esf_Module extends esf_Extension {

  /**
   * Class constructor
   */
  public function __construct() {
    parent::__construct();
    $this->Forwarded = FALSE;
  }
  /**
   * Called before the Action function is processed
   */
  public function Before() {
    if ($this->LoginRequired AND !esf_User::isValid())
      $this->redirect('login');
  }

  /**
   * Called after the Action function is processed
   */
  public function After() {
    $dir = np('module/'.$this->ExtensionName);
    $layout = Session::get('Layout');
    $html = '';
    foreach (array('style.css', 'print.css', 'script.js') as $id => $f) {
      // common for all layouts
      $file = $dir.'/layout/'.$f;
      file_exists(np($file)) AND $html .= sprintf($this->formats[$id], $file);
      // custom common for all layouts
      $file = $dir.'/layout/custom/'.$f;
      file_exists(np($file)) AND $html .= sprintf($this->formats[$id], $file);
      // for specific layout ...
      $file = $dir.'/layout/'.$layout.'/'.$f;
      file_exists(np($file)) AND $html .= sprintf($this->formats[$id], $file);
      // custom for specific layout ...
      $file = $dir.'/layout/'.$layout.'/custom/'.$f;
      file_exists(np($file)) AND $html .= sprintf($this->formats[$id], $file);
    }
    TplData::add('HtmlHeader.raw', $html);
  }

  /**
   * Handle not defined action routines
   *
   * @param string $name Method name
   * @param array $arguments
   * @return void
   */
  public function __call( $name, $arguments ) {
    if (DEVELOP) die('Not handled action "'.$name.'" in '.get_class($this));
    // redirect to Index action of module
    $this->forward();
  }

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   * Set if the internal method flow goes through $this->forward()
   *
   * @var bool $Forwarded
   */
  protected $Forwarded;

  /**
   *
   */
  protected $formats = array (
    '  <link type="text/css" rel="stylesheet" href="%s">',
    '  <link type="text/css" rel="stylesheet" href="%s" media="print">',
    '  <script type="text/javascript" src="%s"></script>',
  );

  /**
   * Actual module action
   *
   * @var string $Action
   */
  protected $Action;

  /**
   * Forward to another action
   *
   * @param $action string
   */
  protected function forward( $action='index' ) {
    $this->Action = $action;
    Registry::set('esf.Action', $this->Action);
    $this->Forwarded = TRUE;
    $method = $this->Action.'Action';
    $this->$method();
  }

  /**
   * Redirect to another module/action
   *
   * @param $module string
   * @param $action string
   * @param $params array Additional parameters
   * @param $anchor string
   */
  protected function redirect( $module=NULL, $action=NULL, $params=array(), $anchor=NULL ) {
    Core::redirect(Core::URL(array('module'=>$module, 'action'=>$action,
                                   'params'=>$params, 'anchor'=>$anchor)));
  }

}