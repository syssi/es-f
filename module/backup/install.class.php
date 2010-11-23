<?php
/**
 *
 */

/**
 * Class for Extension installation
 */
class esf_Install_Module_Backup extends esf_Install {

  /**
   * Class constructor
   */
  public function __construct() {
    parent::__construct();
    if (esf_User::isValid()) $this->BackupDir = esf_User::UserDir().'/backup';
  }

  /**
   *
   */
  public function Info() {
    return '
      <p>Store deleted auctions in <tt>&lt;USERDIR&gt;/backup</tt></p>
      <p>This directory (and ALL saved auctions) will removed, when you deinstall the module!</p>
    ';
  }

  /**
   * Install module
   *
   * Create directory
   */
  public function Install() {
    return $this->CreateDirectory($this->BackupDir);
  }

  /**
   * Deinstall module
   *
   * Remove directory
   */
  public function Deinstall() {
    return $this->RemoveDirectory($this->BackupDir);
  }

}