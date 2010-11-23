<html>
<head>
  <title>DebugStack example</title>
</head>
<body>
<pre>
<?php
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

$a = array( 1, 2 );
DebugStack::Debug($a);

DebugStack::Warning('A Warning.');
DebugStack::Error('An ERROR!');

function DoTrace( $level=1, $full=FALSE ) {
  DebugStack::Trace($level, $full);
}

DoTrace();

// Force an error to capture
$a = $b;

// Let's output all
// 1st finalize it
DebugStack::Finalize();

// Output with the default script and CSS
DebugStack::Output(TRUE, TRUE);
?>
</body>
</html>