<?php
/**
 * Template cache class
 *
 * Caches static content
 *
 * @ingroup  Core
 * @version  1.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
abstract class Yuelo_Cache {

  /**
   * Enable / Disable caching
   *
   * @param bool $Active
   */
  public static function Active( $Active=TRUE ) {
    self::$Active = (bool)$Active;
  }

  /**
   * Set unique identifier (cache file prefix) for this application context
   *
   * Uses $_SERVER['HTTP_HOST'] as default, set only ONCE
   *
   * @param string $UniqueId
   */
  public static function UniqueId( $UniqueId='' ) {
    if (!self::$UniqueId)
      self::$UniqueId = !empty($UniqueId) ? $UniqueId : $_SERVER['HTTP_HOST'];
  }

  /**
   * Set Path to cache directory
   *
   * @param string $CacheDir
   */
  public static function CacheDir( $CacheDir, $throw=TRUE ) {
    if (!is_dir($CacheDir)) {
      if ($throw)
        throw new Yuelo_Exception('"'.$CacheDir.'" is not a directory!');
      else
        $CacheDir = FALSE;
    } elseif (!is_writable($CacheDir)) {
      if ($throw)
        throw new Yuelo_Exception('"'.$CacheDir.'" is not writable!');
      else
        $CacheDir = FALSE;
    }
    self::$CacheDir = $CacheDir;
    if (!self::$CacheDir) self::Active(FALSE);
  }

  /**
   * Set Cache file extension
   *
   * @param string $CacheExt
   */
  public static function CacheExt( $CacheExt ) {
    self::$CacheExt = $CacheExt;
  }

  /**
   * Set Cache life time in sec.
   *
   * @param int $CacheLifeTime
   */
  public static function CacheLifeTime( $CacheLifeTime ) {
    self::$CacheLifeTime = (int)$CacheLifeTime;
  }

  /**
   * Set Garbage collection probability in %
   *
   * Set to 0 (zero) to disable garbage collection
   *
   * @param string $Probability
   */
  public static function gcProbability( $Probability=0 ) {
    if ($Probability < 0) $Probability = 0;
    elseif ($Probability > 100) $Probability = 100;
    self::$Probability = $Probability;
  }

  /**
   * Main public static function for caching
   *
   * @usage
   * @code
   * while (Yuelo_Cache::Save('MyUniqueCacheId')) {
   *   // generate some output
   *   // This can also be nested, but Ids have to unique!
   *   while (Yuelo_Cache::Save('MyOtherUniqueCacheId')) {
   *     // generate another output
   *   }
   * }
   * @endcode
   *
   * @param string $CacheId Cache id, have to be unique per application!
   * @param boolean $disable Don't cache this special Id (for debugging)
   * @return boolean
   */
  public static function Save( $CacheId, $disable=FALSE ) {
    $filename = self::CacheFileName($CacheId);
    $verbose = Yuelo::get('Verbose') & Yuelo::VERBOSE_COMMENTS;
    $active = (self::$Active AND !$disable);

    if ($CacheId == end(self::$Stack)) {
      if ($active) {
        $content = ob_get_clean();
        // Is content is cachable?
        if (strpos($content, Yuelo::CACHETAG) !== FALSE) {
          $content = str_replace(Yuelo::CACHETAG, '', $content);
          if ($verbose)
            echo '<!-- WRITE CACHE ', $filename, ' -->', "\n";
          if (file_exists($filename) AND !is_writable($filename))
            throw new Yuelo_Exception('Cache file "'.$filename.'" is not writeable!');
          if (!file_put_contents($filename, $content))
            throw new Yuelo_Exception('Can\'t write to cache file "'.$filename.'"!');
        }
        echo $content, "\n";
      }
      // done, remove id from stack
      array_pop(self::$Stack);
      return FALSE;
    } elseif (count(self::$Stack) AND in_array($CacheId, self::$Stack)) {
      throw new Yuelo_Exception('Cache stack problem: Level '
                               .end(self::$Stack).' was not properly finished!');
    } else {
      if ($active AND file_exists($filename) AND
          time()-filemtime($filename) < self::$CacheLifeTime) {
        if ($verbose) echo '<!-- BEGIN CACHE ', $filename, ' -->', "\n";
        readfile($filename); echo "\n";
        if ($verbose) echo '<!-- END CACHE ', $filename, ' -->', "\n";
        // Content found in cache, done
        return FALSE;
      } else {
        if ($active) ob_start();
        // return size of current stack for next loop
        return array_push(self::$Stack, $CacheId) + 1;
      }
    }
  }

  /**
   * Delete specific cache file
   *
   * @param string $CacheId Cache id, have to be unique per application!
   * @return boolean
   */
  public static function Delete( $CacheId ) {
    $file = self::CacheFileName($CacheId);
    file_exists($file) && unlink($file);
  }

  /**
   * Clear cache
   *
   * @return void
   */
  public static function Clear() {
    foreach (glob(self::CacheFileName('*')) as $file) unlink($file);
  }

  /**
   * Garbage collection
   *
   * @usage
   * @code
   * // Simply add this at the end of your script
   * Yuelo_Cache::gc();
   * @endcode
   *
   * @param string $Probability Garbage collection probability in %
   *        Optional, if no probability was provided use self::$Probability
   *        Set to 0 (zero) to disable garbage collection
   *
   * @return void
   */
  public static function gc( $Probability=NULL ) {
    if (!isset($Probability)) $Probability = self::$Probability;
    // Should we garbage collect ?
    if ($Probability <= 0) return;

    $ts = time();
    mt_srand($ts);

    if (mt_rand()*100/mt_getrandmax() > $Probability) return;

    $i = 0;
    foreach (glob(self::CacheFileName('*')) as $file) {
      if (filemtime($file)+self::$CacheLifeTime < $ts) {
        unlink($file);
        $i++;
      }
    }
    return $i ? sprintf('Yuelo::gc() removed %d files.', $i) : NULL;
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * Set to FALSE to disable caching at all
   */
  private static $Active = TRUE;

  /**
   * Unique installation ID
   */
  private static $UniqueId = FALSE;

  /**
   * Cache directory
   */
  private static $CacheDir = '/tmp';

  /**
   * Extension for cache files
   */
  private static $CacheExt = '.cache.htm';

  /**
   * Cache life time, default is 10 min.
   */
  private static $CacheLifeTime = 600;

  /**
   * Garbage collection probability, default is 1%
   */
  private static $Probability = 1;

  /**
   * Stack of chached output IDs
   */
  private static $Stack = array();

  /**
   * Build cache file name
   */
  private static function CacheFileName( $CacheId ) {
    // make sure to have a global cache id and to check the cache directory
    if (!self::$UniqueId) self::UniqueId();
    return self::$CacheDir . DIRECTORY_SEPARATOR . self::$UniqueId . '-' . $CacheId . self::$CacheExt;
  }
}
