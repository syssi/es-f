<?php
/**
 * Check for new esniper releases
 *
 * @ingroup    Plugin-esniperVersion
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-58-g537daad 2011-01-22 22:15:25 +0100 $
 * @revision   $Rev$
 */
class esf_Plugin_esniperVersion extends esf_Plugin {

  /**
   * @return array Array of events handled by the plugin
   */
  public function handles() {
    return array('ProcessStart');
  }

  /**
   * Handle ProcessStart notification
   */
  public function ProcessStart() {
    $file = Registry::get('RunDir').'/.esniper-version';
    if (/* on session start */
        !Session::get('esniperVersion') OR
        /* every ? hour(s) */
        $_SERVER['REQUEST_TIME'] > File::MTime($file)+60*60*$this->period) {
      // read esniper version
      $cmd = array('EsniperVersion::Version', Registry::get('bin_esniper'));
      // alarm in case of new version
      if (Exec::getInstance()->ExecuteCmd($cmd, $res) OR count($res) > 1) {
        Messages::Error($res);
        // read ChangeLog
        $cl = file($this->changelog);
        $i = 0;
        foreach ($cl as $line) {
          $line = trim($line);
          // Skip on 1st empty line
          if (empty($line)) break;
          $i++;
        }
        // remove older ChangeLogs
        array_splice($cl, $i);
        Messages::Info('<pre>'.implode($cl).'</pre>', TRUE);
      }
      $ver = trim(implode("\n", $res));
      file_put_contents($file, $ver);
      Session::set('esniperVersion', $ver);
    }
    // once per script run
    Event::dettach($this);
  }

}

Event::attach(new esf_Plugin_esniperVersion);