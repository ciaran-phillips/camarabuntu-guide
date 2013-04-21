#!/bin/bash

# Get location of this bash script
# This is actually just copypasta from stackoverflow...
pushd . > /dev/null
SCRIPT_PATH="${BASH_SOURCE[0]}";
if ([ -h "${SCRIPT_PATH}" ]) then
  while([ -h "${SCRIPT_PATH}" ]) do cd `dirname "$SCRIPT_PATH"`; SCRIPT_PATH=`readlink "${SCRIPT_PATH}"`; done
fi
cd `dirname ${SCRIPT_PATH}` > /dev/null
SCRIPT_PATH=`pwd`;
popd  > /dev/null

# Run the webserver and set its document root
$SCRIPT_PATH/mongoose/mongoose \
-s $SCRIPT_PATH/mongoose/build/ssl_cert.pem \
-r $SCRIPT_PATH/camarabuntu/&

# open firefox
firefox -new-window http://localhost:8080/index.php
