<?php
/**
 *
 */

/**
 * Class for Extension installation
 */
class esf_Install_Plugin_RefreshBackground extends esf_Install {

  /**
   * Plugin information
   */
  public function Info() {

    $php = Exec::getInstance()->Execute('which php', $php) ? 'path/to/your/php' : $php[0];
    $path = dirname(__FILE__);

    return <<<EOT
      <p>This module can refresh the auction status complete in background.</p>
      <p>Therefore must the cli version of PHP be installed.</p>

      <p>To refresh your auctions via cron job, add e.g. the following (as one) line to your crontab (<tt>crontab -e</tt>):</p>

      <p class="code">0 8-18/2 * * * sudo -u ... $php -f $path/refresh.php</p>

      <p>to refresh <strong>all</strong> auctions of <strong>all</strong> users each 2 hours from 8am to 6pm</p>

      <p>Use for the <tt>sudo -u ...</tt> the user, under which is <strong>your</strong> web server running!</p>

      <p style="color:red">Auctions of users submited like this,
      will <strong><em>not refreshed</em></strong> automatic:</p>

      <p class="code">... refresh.php -- USER1 [USER2]</p>

      <p>To refresh only auctions of a specific user, use this syntax:</p>

      <p class="code">... refresh.php -- -force USER1</p>

      <p>The refreshing command must run via sudo as the user which also runs the web server!</p>
EOT;
  }

  /**
   * Plugin installation function
   */
  public function Install() {
    Exec::getInstance()->Execute('which php', $php, $rc);
    if (isset($php[0])) {
      $this->Message('Found cli version of PHP as "'.$php[0].'"', Messages::SUCCESS);
    } else {
      $this->Message('Cli version of PHP not found, please install before!', Messages::ERROR);
      return TRUE;
    }
  }

  /**
   * Plugin activation function
   */
  public function Enable() {
    $this->ForceInfo = TRUE;
  }

}