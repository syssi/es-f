<?php
/**
 * Refresh auctions in background process, also as cron job
 *
 * @ingroup    Plugin-RefreshBackground
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-67-gb76471b 2011-02-05 17:19:38 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_RefreshBackground extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('LanguageSet', 'Start', 'OutputStart', 'PageEnded');
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
      Messages::Info(Translation::get('Refresh_Bg.Refresh'));
    }
  }

  /**
   *
   */
  public function PageEnded() {
    if (!$this->Refresh) return;

    $Exec = Exec::getInstance();
    if (!$PHP = $this->PHP) {
      $Exec->ExecuteCmd('RefreshBackground::WhichPHP', $PHP);
      $PHP = array_shift($PHP);
    }

    if (empty($PHP)) {
      Messages::Error('Plugin::RefreshBackground - Can\'t find PHP cli binary!');
      $url = Core::URL(array('module'=>'configuration','action'=>'edit',
                             'params'=>array('ext'=>'plugin-refreshbackground')));
      Messages::Error('Please install OR <a href="'.$url.'">configure</a> the complete path!', TRUE);
      return;
    }

    $cmd = array('RefreshBackground::Refresh',
                 $PHP, dirname(__FILE__), TEMPDIR, esf_User::getActual());
    $Exec->ExecuteCmd($cmd, $res);
    if ($res) {
      Messages::Error('Messages from background refresh');
      Messages::Error(implode('<br>', $res), TRUE);
    }

    Messages::Info(Translation::get('Refresh_Bg.Refreshed'));
    Yryie::Debug($Exec->LastCmd);
  }
}

Event::attach(new esf_Plugin_RefreshBackground);