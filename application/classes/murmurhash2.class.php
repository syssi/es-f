<?php
/**
 * MurmurHash 2.0
 *
 * MurmurHash is a non-cryptographic hash function suitable for general
 * hash-based lookup. It was created by Austin Appleby in 2008 and exists in
 * a number of variants, all of which have been released into the public domain.
 *
 * Sources:
 * - http://en.wikipedia.org/wiki/MurmurHash
 * - http://sites.google.com/site/murmurhash
 * - v3: http://code.google.com/p/smhasher/source/browse/trunk/MurmurHash3.cpp?r=75
 *
 * Adapted from JS version: https://gist.github.com/588423
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
abstract class murmurHash2 {

  /**
   *
   */
  public static function get( &$str, $seed ) {
    $h = $seed ^ strlen($str);

    $len = strlen($str);
    $pos = 0;

    while ($len >= 4) {
      $k = self::UInt32($str, $pos);

      self::Umul32($k);
      $k ^= $k >> self::$r;
      self::Umul32($k);

      self::Umul32($h);
      $h ^= $k;

      $pos += 4;
      $len -= 4;
    }

    switch ($len) {
      case 3:
        $h ^= self::UInt16($str, $pos);
        $h ^= ord($str{$pos+2}) << 16;
        self::Umul32($h);
        break;

      case 2:
        $h ^= self::UInt16($str, $pos);
        self::Umul32($h);
        break;

      case 1:
        $h ^= ord($str{$pos});
        self::Umul32($h);
        break;
    }

    $h ^= $h >> 13;
    self::Umul32($h);
    $h ^= $h >> 15;

    return str_pad(sprintf('%x', $h), 8, '0', STR_PAD_LEFT);
  }

  /**
   * @name Mixing constants
   * @{
   * 'm' and 'r' are mixing constants generated offline.
	 * They're not really 'magic', they just happen to work well.
	 *
	 * @var int $m
   */
  private static $m = 0x5bd1e995;

  /**
	 * @var int $r
   */
  private static $r = 24;
  /** @} */

  /**
   *
   */
  private static function UInt32( $str, $pos ) {
    return ord($str{$pos++})       + ord($str{$pos++}) << 8 +
           ord($str{$pos++}) << 16 + ord($str{$pos})   << 24;
  }

  private static function UInt16( $str, $pos ) {
    return ord($str{$pos++}) + ord($str{$pos++}) << 8;
  }

  private static function Umul32( &$n ) {
    $nlo = $n & 0xFFFF;
    $nhi = $n >> 16;
    $n = $nlo * self::$m + ((($nhi * self::$m) & 0xffff) << 16);
  }

}
/*
$text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
echo murmurHash2::get($text, 0x599bd1e5), '<br>';

$text .= ' ';
echo murmurHash2::get($text, 0x599bd1e5), '<br>';

$text = 'Lorem.';
echo murmurHash2::get($text, 0x599bd1e5), '<br>';

$text .= ' ';
echo murmurHash2::get($text, 0x599bd1e5), '<br>';

$text = ' ';
echo murmurHash2::get($text, 0x599bd1e5), '<br>';

$text = '';
echo murmurHash2::get($text, 0x599bd1e5), '<br>';

374aec55
2a128e24
4395376f
7a530b5c
705282c7
57e31990
*/