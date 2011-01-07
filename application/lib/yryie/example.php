<?php
/**
 * Yryie example
 *
 * @ingroup    Yryie
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
?>
<html>
<head>
  <title>Yryie example</title>
  <style type="text/css">
  .linenum{
      text-align:right;
      background:#FDECE1;
      border:1px solid #cc6666;
      padding:0px 1px 0px 1px;
      font-family:Courier New, Courier;
      float:left;
      width:17px;
      margin:3px 0px 30px 0px;
      }
  code    {/* safari/konq hack */
      font-family:Courier New, Courier;
  }
  .linetext{
      width:700px;
      text-align:left;
      background:white;
      border:1px solid #cc6666;
      border-left:0px;
      padding:0px 1px 0px 8px;
      font-family:Courier New, Courier;
      float:left;
      margin:3px 0px 30px 0px;
  }
  br.clear    {
      clear:both;
  }
  </style>
</head>
<body>
<pre>
<?php
$html = file_get_contents(__FILE__);
preg_match('~^// >>(.*)^// <<~ms', $html, $args);
highlight_string('<?php'."\n".trim($args[1]));

// >>

// Handle all errors by Yryie
error_reporting(-1);

require_once 'yryie.class.php';

// Add version infos, set BEFORE include!
Yryie::Versions();

// Register error handler
Yryie::Register();

Yryie::Info('An information...');
Yryie::Code('<b>code example</b>');
Yryie::State('State');
Yryie::SQL('SELECT * FROM table');
Yryie::Debug(array( 1, 2 ));
Yryie::Warning('A Warning.');
Yryie::Error('An ERROR!');

function DoTrace( $level=1, $full=FALSE ) {
  Yryie::Trace($level, $full);
}

Yryie::Info('Trace 1:');  DoTrace();
Yryie::Info('Trace 2:');  DoTrace(0, TRUE);

function Error(&$a) {
  // Force an error to capture
  $a = $b;
}
Error($a);

// Let's output all
// 1st finalize it
Yryie::Finalize();
// Output with the default script and CSS
Yryie::Output(TRUE, TRUE);
// <<
?>
</body>
</html>