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
          DebugStack::Info(get_class($this).'::'.$method.'()');
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

  // Action
  // public function IndexAction() {}
  // public function ShowAction() {}

  // Action during processing
  // public function IndexHeaderAction() {}
  // public function IndexContentAction() {}
  // public function IndexFooterAction() {}

  //--------------------------------------------------------------------------
  // PROTECTED
  //--------------------------------------------------------------------------

  /**
   * @var string
   */
  protected $Action;

  /**
   *
   */
  protected function forward( $action='index' ) {
    $this->Action = $action;
    Registry::set('esf.Action', $this->Action);
  }

  /**
   *
   */
  protected function redirect( $module=NULL, $action=NULL, $params=array(), $anchor=NULL ) {
    Core::redirect(Core::URL(array('module'=>$module, 'action'=>$action,
                                   'params'=>$params, 'anchor'=>$anchor)));
  }

}