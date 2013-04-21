mv localhost\:8080 /camarabuntu
chmod -R 755 /camarabuntu
cp camaraguide.desktop /home/camara/Desktop/
cp camaraguide.desktop /home/camaraadmin/Desktop/


chmod 755 /home/camaraadmin/Desktop/camaraguide.desktop
chown camaraadmin:camaraadmin /home/camaraadmin/Desktop/camaraguide.desktop

chmod 755 /home/camara/Desktop/camaraguide.desktop
chown camaraadmin:camaraadmin /home/camaraadmin/Desktop/camaraguide.desktop


rename 's/php/html/g' /camarabuntu/index.*
sed -i 's/index.php/index.html/g' /camarabuntu/index.*
sed -i -r 's/index.html\?/index.html%3F/g' /camarabuntu/index.*
sed -i -r 's/href="\?/href="index.html%3F/g' /camarabuntu/index.*
sed -i -r 's/\/index.html/index.html/g' /camarabuntu/index.*
sed -i -r 's/http\:\/\/localhost\:8080//g' /camarabuntu/index.*

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

