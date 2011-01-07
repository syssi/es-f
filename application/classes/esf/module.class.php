<?php
/**
 * Abstract Module class
 *
 * @ingroup    Module
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2010 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
abstract class esf_Module extends esf_Extension {

  /**
   * Handle called action routines
   *
   * Simple actions
   * - IndexAction
   * - ShowAction
   *
   * Actions during processing
   * - IndexHeaderAction
   * - IndexContentAction
   * - IndexFooterAction
   *
   * @param string $action
   * @param string $step Header|Content|Footer
   * @return void
   */
  public function handle( $action, $step='' ) {
    $this->Action = $action;
    do {
      $saveaction = $this->Action;
      // Check for supported action
      if (stristr($this->Actions, $this->Action)) {
        $method = $this->Action . $step . 'Action';
        // Check for method
        if (method_exists($this, $method)) {
          // >> Debug
          Yryie::Info(get_class($this).'::'.$method.'()');
          // << Debug
          $this->$method();
        }
      } else {
        if (DEVELOP)
          Messages::Error('Not handled action "'.$this->Action.'" in '.get_class($this));
        $this->redirect();
      }
    } while ($saveaction != $this->Action);
  }

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   * Actual module action
   *
   * @var string
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