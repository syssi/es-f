<?php
/**
 * esniper processes module
 *
 * @ingroup    Module-Process
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class esf_Module_Process extends esf_Module {

  /**
   *
   */
  public function IndexAction() {
    $user = esf_User::getActual(TRUE);
    $cmd = array('Process::RUNNING', $user);
    Exec::getInstance()->ExecuteCmd($cmd, $processes);

    if (!empty($processes)) {
      foreach ($processes as $id => $line) {
        $p['PROCESS'] = $line;

        $g = preg_split('~\s+~', trim($line));

        // PID
        $p['PID'] = $g[0];

        // get last word as auction file ...
        $g = $g[count($g)-1];
        // ... and remove user name
        $p['GROUP'] = str_replace('.'.$user, '', $g);

        TplData::add('Processes', $p);
      }
    } else {
      Messages::Info(Translation::get('Process.NoProcesses'));
    }
  }

  /**
   *
   */
  public function KillAction() {
    if ($this->isPost() AND $this->Request('pid')) {
      $pids = esf_Auctions::PIDs();
      // check, that only allowed processes (own auctions) could be killed ;-)
      if (isset($pids[$this->Request('pid')])) {
        $cmd = array('Process::KILL', $this->Request('pid'));
        if (Exec::getInstance()->ExecuteCmd($cmd, $res, Registry::get('SuDo'))) {
          Messages::Error($res);
        }
      }
    }
    $this->forward();
  }

}