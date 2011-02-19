<?php
/**
 * Class to generate and handle Universally Unique IDentifiers (UUID)
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2010-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
abstract class UUID {

  /**
   * Get an UUID for an $id
   *
   * @param string $id Identifier
   * @return string UUID
   */
  public static function UID( $id ) {
    if (!isset(self::$UID[$id])) {
      self::$UID[$id] = self::get();
    }
    return self::$UID[$id];
  }

  /**
   * Generates a Universally Unique IDentifier, version 4.
   *
   * From http://www.php.net/manual/function.uniqid.php#65879
   *
   * RFC 4122 (http://www.ietf.org/rfc/rfc4122.txt) defines a special type of
   * Globally Unique IDentifiers (GUID), as well as several methods for
   * producing them. One such method, described in section 4.4, is based on
   * truly random or pseudo-random number generators, and is therefore
   * implementable in a language like PHP.
   *
   * We choose to produce pseudo-random numbers with the Mersenne Twister, and
   * to always limit single generated numbers to 16 bits (ie. the decimal value
   * 65535). That is because, even on 32-bit systems, PHP's RAND_MAX will often
   * be the maximum *signed* value, with only the equivalent of 31 significant
   * bits. Producing two 16-bit random numbers to make up a 32-bit one is less
   * efficient, but guarantees that all 32 bits are random.
   *
   * The algorithm for version 4 UUIDs (ie. those based on random number
   * generators) states that all 128 bits separated into the various fields
   * (32 bits, 16 bits, 16 bits, 8 bits and 8 bits, 48 bits) should be random,
   * except : (a) the version number should be the last 4 bits in the 3rd field,
   * and (b) bits 6 and 7 of the 4th field should be 01. We try to conform to
   * that definition as efficiently as possible, generating smaller values where
   * possible, and minimizing the number of base conversions.
   *
   * @copyright   Copyright (c) CFD Labs, 2006.
   *              This function may be used freely for any purpose ; it is
   *              distributed without any form of warranty whatsoever.
   * @author      David Holmes <dholmes@cfdsoftware.net>
   *
   * @return  string  A UUID, made up of 32 hex digits and 4 hyphens.
   */
  public static function get() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    // 32 bits for "time_low"
                    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
                    // 16 bits for "time_mid"
                    mt_rand( 0, 0xffff ),
                    // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
                    mt_rand( 0, 0x0fff ) | 0x4000,
                    // 8 bits, the last two of which (positions 6 and 7) are 01, for "clk_seq_hi_res"
                    // (hence, the 2nd hex digit after the 3rd hyphen can only be 1, 5, 9 or d)
                    // 8 bits for "clk_seq_low"
                    bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
                    // 48 bits for "node"
                    mt_rand( 0, 0xffff ),
                    mt_rand( 0, 0xffff ),
                    mt_rand( 0, 0xffff )
                  );

  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * UUID buffer
   *
   * @var array $UID
   */
  private static $UID = array();

}