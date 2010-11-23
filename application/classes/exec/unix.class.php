<?php
/**
 * Copyright (c) 2006-2009 Knut Kohl <knutkohl@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 * @package exec
 * @subpackage unix
 */

/**
 *
 */
class Exec_Unix extends Exec {

  /**
   * mkdir -p $dir
   *
   * @param string $dir Directory name
   * @param array $result Command output
   * @param string $sudo Run command as other user
   * @return integer Return code of system command
   */
  public function MkDir( $dir, &$result, $sudo='' ) {
    $this->quote($dir);
    $cmd = 'mkdir -p '.$dir;
    return $this->Execute($cmd, $result, $sudo);
  }

  /**
   * chmod [-R] $mode $filemask
   *
   * @param string $filemask *nix file mask string
   * @param integer $mode File mode, also octal
   * @param array $result Command output
   * @param string $sudo Run command as other user
   * @return integer Return code of system command
   */
  public function ChMod( $filemask, $mode, $rec, &$result, $sudo='' ) {
    $this->quote($filemask);
    $cmd = sprintf('chmod %s %s %s', ($rec?'-R':''), $mode, $filemask);
    return $this->Execute($cmd, $result, $sudo);
  }

  /**
   * cp -r $source $dest
   *
   * @param string $source *nix file mask string
   * @param string $dest *nix file mask string
   * @param array $result Command output
   * @param string $sudo Run command as other user
   * @return integer Return code of system command
   */
  public function Copy( $source, $dest, &$result, $sudo='' ) {
    $this->quote($source);
    $this->quote($dest);
    $cmd = sprintf('cp -r %s %s 2>/dev/null', $source, $dest);
    return $this->Execute($cmd, $result, $sudo);
  }

  /**
   * mv $source $dest
   *
   * @param string $source *nix file mask string
   * @param string $dest *nix file mask string
   * @param array $result Command output
   * @param string $sudo Run command as other user
   * @return integer Return code of system command
   */
  public function Move( $source, $dest, &$result, $sudo='' ) {
    $this->quote($source);
    $this->quote($dest);
    $cmd = sprintf('mv %s %s', $source, $dest);
    return $this->Execute($cmd, $result, $sudo);
  }

  /**
   * rm -f $filemask
   *
   * @param string $filemask *nix file mask string
   * @param array $result Command output
   * @param string $sudo Run command as other user
   * @return integer Return code of system command
   */
  public function Remove( $filemask, &$result, $sudo='' ) {
    $this->quote($filemask);
    $cmd = sprintf('rm -f %s', $filemask);
    return $this->Execute($cmd, $result, $sudo);
  }

  /**
   * Execute a system command with some *nix specific settings
   *
   * @param string|array $cmd Command to execute, optional as array with sprintf parameters
   * @param array &$result Return of executed command
   * @param string $sudo Run as defined sudo user
   * @return integer Return code of system command
   */
  public function Execute( $cmd, &$result, $sudo='' ) {
    if (is_array($cmd)) {
      $c = array_shift($cmd);
      $cmd = vsprintf($c, $cmd);
    }
    if (preg_match('~^(.*?)\s*([^2]>+[^|]*)$~', $cmd, $args)) {
      $cmd = escapeshellarg('COLUMNS=500; '.$args[1]).$args[2];
    } else {
      $cmd = escapeshellarg('COLUMNS=500; '.$cmd);
    }
    // quote
    $cmd = str_replace('\\\'','"',$cmd);
    $cmd = 'sh -c '.$cmd;

    // run as another user?
    if ($sudo) $cmd = sprintf('sudo -u %s %s', $sudo, $cmd);

    // pipe error output to stadard output, if not defined
    if (!strstr($cmd, '2>')) $cmd .= ' 2>&1';

    return $this->_exec($cmd, $result);
  }

  //-------------------------------------------------------------------------
  // PRIVATE
  //-------------------------------------------------------------------------

  /**
   * Quote file masks if required
   *
   * @param string &$filemask *nix file mask string
   * @return void
   */
  private function quote( &$filemask ) {
    if (!strstr($filemask, '"')) $filemask = '"' . $filemask . '"';
  }

}