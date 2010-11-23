#!/bin/sh

# If "which php" for the web server user not works, define the absolut path here
phpcli=

# ----------------------------------------------------------------------------
# Please don't change from here!
# ----------------------------------------------------------------------------

# for debugging...
##set -x

# set PHP binary
php=${phpcli:-$(which php)}

# check PHP binary is executable
if [ ! -x "$php" ]; then
  echo 'Error, missing executable PHP cli!'
  exit 1
fi

script=$(dirname $0)/../../plugin/refreshbackground/refresh.php

if [ ! -f "$script" ]; then
  echo 'Error, missing refresh script (plugin/refreshbackground/refresh.php)!'
  exit 1
fi

# all fine, let's go
$php $script $*

set +x