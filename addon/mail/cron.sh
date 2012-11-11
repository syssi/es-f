#!/bin/sh

# If "which php" not works, define the absolut path here
phpcli=

# ----------------------------------------------------------------------------
# Please don't change from here!
# ----------------------------------------------------------------------------

# for debugging...
## set -x

# set PHP binary
php=${phpcli:-$(which php)}

# check PHP binary is executable
if [ ! -x "$php" ]; then
  echo 'Error, missing executable PHP cli!'
  exit 1
fi

path="$(dirname $0)"

if [ ! -f "$path/mail.xml" ]; then
  echo 'Error, missing configuration (mail.xml)!'
  exit 1
fi

if [ ! -f "$path/cron.php" ]; then
  echo 'Error, missing refresh script (cron.php)!'
  exit 1
fi

# all fine, let's go
$php -d log_errors=Off $path/cron.php $*

set +x
