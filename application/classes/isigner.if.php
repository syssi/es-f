<?php
/**
 * Signer to handle (mostly) signed session / cookie data
 *
 * Inspired by Nelmio SecurityBundle, (c) Nelmio <hello@nelm.io>
 *
 * https://github.com/nelmio/NelmioSecurityBundle
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 * @revision   $Rev$
 *
 */
interface ISigner {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Sign a value
   *
   * @param  string $value Value to sign
   * @return string        Value incl. signature
   */
  public function sign( $value );

  /**
   * Check signed value and return raw value without signature
   *
   * @param  string $value Value to check
   * @return string        Raw value without signature
   */
  public function get( $signed );

  /**
   * Verify valid value
   *
   * @param  string $value Value to check
   * @return bool
   */
  public function verify( $signed );

}