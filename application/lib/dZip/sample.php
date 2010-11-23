<?
require_once dirname(__FILE__)."/dUnzip2.inc.php";
require_once dirname(__FILE__)."/dZip.inc.php";

######## Create a ZIP file dinamically ########
# // Initialize dZip class
# echo "Initializing dZip class...<br>";
# $newzip = new dZip('dUnzip2.zip');

# // Create a folder in the ZIP, to store dZip and dUnzip files
# echo "Creating folder to store both classes<br>";
# $newzip->addDir('class dZip');
# $newzip->addDir('class dUnzip2');

# // Put the files
# echo "Adding files to the zip<br>";
# $newzip->addFile('dUnzip2.inc.php',  'class dUnzip2/dUnzip2.inc.php');
# $newzip->addFile('documentation.txt','class dUnzip2/documentation.txt');
# $newzip->addFile('dZip.inc.php',     'class dZip/dZip.inc.php');
# $newzip->addFile('sample.php',       'sample.php');

# // Save the new file
# echo "Finalizing the created file<br>";
# $newzip->save();

######## Then, load the file again. Now, to unzip it ########
echo "<hr>";
$zip = new dUnzip2('dUnzip2.zip');

// Activate debug
$zip->debug = true;

// Unzip all the contents of the zipped file to a new folder called "uncompressed"
$zip->getList();
$zip->unzipAll('uncompressed');

echo "Checking attributes for dUnzip2.gif<br>";
$d = $zip->getExtraInfo('dUnzip2.gif');
echo ($d['external_attributes1']&1 )?"File is read only.":"File is NOT read-only."; echo "<br>";
echo ($d['external_attributes1']&2 )?"File is hidden.":"File is NOT hidden.";       echo "<br>";
echo ($d['external_attributes1']&4 )?"File is system.":"File is NOT system.";       echo "<br>";
echo ($d['external_attributes1']&16)?"It's directory.":"It's NOT a directory.";     echo "<br>";
echo ($d['external_attributes1']&32)?"File is archive":"File is NOT archive";       echo "<br>";

// No secrets, do you agree?
