#!/bin/sh

## set -x

if [ -z "$2" ]; then
  echo
  echo "Usage"
  echo " $0 PathToWorkingDir LanguageCode"
  echo
  echo "Example"
  echo "  Prepare translation to french somwhere in your home directory"
  echo "  $0 ~ fr"
  echo "  This will copy all english language files below ~/es-f/fr"
  echo
  exit 1
fi

base=$(dirname $(dirname $(readlink -f $0)))/

find $base -name en.php | \
while read fs; do

  # Abolute path of found source file
  p=$(dirname $fs)

  # Make path relative, remove base path
  p=$(echo $p | sed -e "s|$base||g")

  # Append relative path to destination directory
  p="$1/es-f/$2/$p"

  # Build destination file name
  fd="$p/$2.php"

  echo "Copy $fs"
  echo "  to $fd"

  # Create the destination directory and copy the file
  mkdir -p "$p" && cp "$fs" "$fd"
done

set +x
