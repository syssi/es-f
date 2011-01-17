<?php
/** @defgroup hash Several additional hashing functions
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-43-g9eb0fbd - Tue Jan 11 21:51:29 2011 +0100 $
 */

/**
 * @{
 * Multible MD5 hashing
 */

/**
 * MD5 2 times
 *
 * @ingroup hash
 * @param string $data Data to calculate hash for
 */
function md52( $data ) { return md5(md5($data));  }

/**
 * MD5 3 times
 *
 * @ingroup hash
 * @param string $data Data to calculate hash for
 */
function md53( $data ) { return md5(md52($data)); }

/**
 * MD5 4 times
 *
 * @ingroup hash
 * @param string $data Data to calculate hash for
 */
function md54( $data ) { return md5(md53($data)); }

/** @} */

/**
 * @{
 * Combinations of MD5 & SHA1
 */

/**
 * MD5 & SHA1
 *
 * @ingroup hash
 * @param string $data Data to calculate hash for
 */
function md5sha1( $data ) { return sha1(md5($data)); }

/**
 * SHA1 & MD5
 *
 * @ingroup hash
 * @param string $data Data to calculate hash for
 */
function sha1md5( $data ) { return md5(sha1($data)); }

/** @} */

/**
 * @{
 * Multible SHA1 hashing
 */

/**
 * SHA1 2 times
 *
 * @ingroup hash
 * @param string $data Data to calculate hash for
 */
function sha12( $data ) { return sha1(sha1($data)); }

/**
 * SHA1 3 times
 *
 * @ingroup hash
 * @param string $data Data to calculate hash for
 */
function sha13( $data ) { return sha1(sha12($data)); }

/**
 * SHA1 4 times
 *
 * @ingroup hash
 * @param string $data Data to calculate hash for
 */
function sha14( $data ) { return sha1(sha13($data)); }

/** @} */

/**
 * @defgroup emd5 Extended MD5
 * @{
 *
 * Server unique key code
 */
define( 'EMD5_CODE', md5(__FILE__) );

/**
 * Extended MD5, use also server unique key
 *
 * @param string $data Data to calculate hash for
 */
function emd5( $data ) {
  // make 256 char long binary code strings from md5 hash
  $data1 = md5(EMD5_CODE);
  for ($i=0, $bin1=''; $i < 32;
       $bin1 .= str_pad(decbin(ord($data1{$i++})), 8, '0', STR_PAD_LEFT) );

  $data2 = md5(trim($data));
  for ($i=0, $bin2=''; $i < 32;
       $bin2 .= str_pad(decbin(ord($data2{$i++})), 8, '0', STR_PAD_LEFT) );

  // combine (XOR) these codes into another code
  for ($i=0, $bin3=''; $i < 256; $bin3 .= (int)($bin1{$i} XOR $bin2{$i++}));

  // hash the combined binary string
  return md5($bin3);
}

/** @} */
