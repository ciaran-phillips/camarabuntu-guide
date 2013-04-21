#!/bin/bash

# Get location of this bash script
pushd . > /dev/null
SCRIPT_PATH="${BASH_SOURCE[0]}";
if ([ -h "${SCRIPT_PATH}" ]) then
  while([ -h "${SCRIPT_PATH}" ]) do cd `dirname "$SCRIPT_PATH"`; SCRIPT_PATH=`readlink "${SCRIPT_PATH}"`; done
fi
cd `dirname ${SCRIPT_PATH}` > /dev/null
SCRIPT_PATH=`pwd`;
popd  > /dev/null

# install php5-cgi so that the webserver can run php files
# Needed even if php was previously set up with apache, as apache runs php
# as a module, rather than as CGI
apt-get update
apt-get install php5-cgi
apt-get install openssl
apt-get install sqlite3
apt-get install php5-sqlite
run="/run.sh"
cp camara.desktop /home/camaraadmin/Desktop/camara.desktop
cp camara.desktop /home/camara/Desktop/camara.desktop  

chmod 755 /home/camaraadmin/Desktop/camara.desktop
chown camaraadmin:camaraadmin /home/camaraadmin/Desktop/camara.desktop

chmod 755 /home/camara/Desktop/camara.desktop
chown camaraadmin:camaraadmin /home/camaraadmin/Desktop/camara.desktop

# Add the location of the run.sh script in this directory to the desktop application file
echo "Exec=$SCRIPT_PATH$run" >> ~/Desktop/camara.desktop
echo "Exec=$SCRIPT_PATH$run" >> /home/camara/Desktop/camara.desktop


# Add user.js file to firefox profile folder


shopt -s extglob
IFS=$'\n'
for folder in `find /home/camaraadmin/.mozilla/firefox/ -mindepth 1 -maxdepth 1 -type d`; do
	
	cp user.js "${folder/user.js}"
	
done

shopt -s extglob
IFS=$'\n'
for folder in `find /home/camara/.mozilla/firefox/ -mindepth 1 -maxdepth 1 -type d`; do
	
	cp user.js "${folder/user.js}"
	
done
