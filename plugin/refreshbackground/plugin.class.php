<?php
/**
 * Refresh auctions in background process, also as cron job
 *
 * @ingroup    Plugin-RefreshBackground
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class esf_Plugin_RefreshBackground extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('Start', 'OutputStart', 'PageEnded');
  }

  /**
   *
   */
  public function Start() {
    if (esf_User::isValid() AND ModuleEnabled('LogFiles') AND $this->LogFile)
      Registry::add('Module.Logfiles.LogFile', esf_User::UserDir().'/'.$this->LogFile);
  }

  /**
   *
   */
  public function OutputStart() {
    $this->Refresh = (
      isset($_GET['FORCEREFRESHBACKGROUND']) OR
      ( !Registry::get('esf.contentonly') AND esf_User::isValid() AND
        // on delete there is a side effect that refreshes just deleted auctions...
        (Registry::get('esf.Module') != 'auction' OR Registry::get('esf.Action') != 'delete') AND
        ($_SERVER['REQUEST_TIME']-$this->MaxAge*60 > Event::ProcessReturn('getLastUpdate')) AND
        esf_Auctions::Count()
      )
    );

    if ($this->Refresh) {
      Event::ProcessInform('setLastUpdate');
      Messages::addInfo(Translation::get('Refresh_Bg.Refresh'));
    }
  }

  /**
   *
   */
  public function PageEnded() {
    if (!$this->Refresh) return;

    $Exec = Exec::getInstance();
    $Exec->ExecuteCmd('REFRESHBACKGROUND::WHICH_PHP', $res);
    $PHP = array_shift($res);

    $cmd = array('REFRESHBACKGROUND::REFRESH',
                 $PHP, dirname(__FILE__), TEMPDIR, esf_User::getActual());
    $Exec->ExecuteCmd($cmd, $res);
    if ($res)
      trigger_error('Messages from background refresh: '.implode('<br>', $res));

    Messages::addInfo(Translation::get('Refresh_Bg.Refreshed'));
  }
}

Event::attach(new esf_Plugin_RefreshBackground);