<html>
<head>
  <title>DebugStack example</title>
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

// Handle all errors by DebugStack
error_reporting(-1);

// Add version infos, set BEFORE include!
$DEBUGSTACK_ADD_VERSIONS = TRUE;

require_once 'debugstack.class.php';

// Register error handler
DebugStack::Register();

DebugStack::Info('An information...');
DebugStack::Code('<b>code example</b>');
DebugStack::State('State');
DebugStack::SQL('SELECT * FROM table');
DebugStack::Debug(array( 1, 2 ));
DebugStack::Warning('A Warning.');
DebugStack::Error('An ERROR!');

function DoTrace( $level=1, $full=FALSE ) {
  DebugStack::Trace($level, $full);
}

DebugStack::Info('Trace 1:');  DoTrace();
DebugStack::Info('Trace 2:');  DoTrace(0, TRUE);

function Error(&$a) {
  // Force an error to capture
  $a = $b;
}
Error($a);

// Let's output all
// 1st finalize it
DebugStack::Finalize();
// Output with the default script and CSS
DebugStack::Output(TRUE, TRUE);
// <<
?>
</body>
</html>