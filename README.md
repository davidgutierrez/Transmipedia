
sudo apt-get update
sudo apt-get install apache2 mysql-server php php-mysql libapache2-mod-php php-xml php-mbstring php-apcu php-intl imagemagick inkscape php-gd php-cli php-curl git

cd /tmp/

wget https://releases.wikimedia.org/mediawiki/1.31/mediawiki-1.31.0.tar.gz

tar -xvzf /tmp/mediawiki-*.tar.gz

sudo mkdir /var/lib/mediawiki

sudo mv mediawiki-*/* /var/lib/mediawiki

mysql -u root -p

CREATE USER 'transmiwikiuser'@'localhost' IDENTIFIED BY 'password';

CREATE DATABASE transmiwikidb;

use transmiwikidb;

GRANT ALL ON transmiwikidb.* TO transmiwikiuser@localhost;

quit;

#Para configurar el php
sudo vi /etc/php/7.0/apache2/php.ini

memory_limit = 128M #aumentar memoria linea 389
upload_max_filesize = 20M #aumentar el tamaño de los archivos que se pueden subir linea 809

sudo phpenmod mbstring
sudo phpenmod xml
sudo systemctl restart apache2.service

sudo ln -s /var/lib/mediawiki /var/www/html/mediawiki
https://35.196.10.6/mediawiki

git clone https://github.com/davidgutierrez/Transmipedia.git

sudo mv Transmipedia/LocalSettings.php /var/lib/mediawiki/

sudo mv Transmipedia/TransMilenio.png /var/www/html/mediawiki/resources/assets/

wget https://extdist.wmflabs.org/dist/extensions/Scribunto-REL1_31-106fbf4.tar.gz
tar -xzf Scribunto-REL1_31-106fbf4.tar.gz -C /var/www/html/mediawiki/extensions/

wget https://getcomposer.org/composer.phar

sudo mv composer.phar /usr/local/bin/composer

sudo chmod 755 /usr/local/bin/composer 

cd /var/www/html/mediawiki/extensions/

git clone https://gerrit.wikimedia.org/r/mediawiki/extensions/Wikibase.git

cd Wikibase

git submodule update --init --recursive # get the dependencies using submodules

cd /var/www/html/mediawiki

composer install --no-dev

php maintenance/update.php

cd extensions/Wikibase

php lib/maintenance/populateSitesTable.php

php repo/maintenance/rebuildItemsPerSite.php

php client/maintenance/populateInterwiki.php

# Si no pregunta por password

sudo service mysql stop

sudo mkdir /var/run/mysqld; sudo chown mysql /var/run/mysqld

sudo mysqld_safe --skip-grant-tables&

sudo mysql --user=root mysql

update user set authentication_string=PASSWORD('new-password') where user='root';
flush privileges;

exit;

sudo service mysql start
########################
Enable Kubernete
 https://console.cloud.google.com/apis/api/container.googleapis.com/overview?project=wikipedia-208621 

git clone https://github.com/davidgutierrez/Transmipedia.git

cd Transmipedia

gcloud container clusters create transmipedia-cluster --num-nodes=3

gcloud container clusters get-credentials transmipedia-cluster

gcloud compute disks create --size 200GB transmipedia-disk

kubectl create secret generic mysql --from-literal=password=password

docker pull wikibase/wikibase

kubectl create -f transmipedia.yaml

kubectl get pod -l app=transmipedia

kubectl describe pods

# Transmipedia
docker-compose -f stack.yml up

tar -xzf extensions/Scribunto-REL1_30-4e3a20b.tar.gz -C extensions/

docker cp extensions/. transmipedia_mediawiki_1:/var/www/html/extensions/

docker cp LocalSettings.php transmipedia_mediawiki_1:/var/www/html/LocalSettings.php

docker exec transmipedia_mediawiki_1 php /var/www/html/maintenance/update.php
# Clean Docker
docker rm -f $(docker ps -a -q)

docker volume rm $(docker volume ls -q)

# Access via "http://localhost:8080"

kubectl run transmipedia-web --image=wikibase/wikibase --port 8080

docker run --env-file ./env.list --rm -p 8080:8080 wikibase/wikibase
#   (or "http://$(docker-machine ip):8080" if using docker-machine)
#  docker swarm init
#  docker stack deploy
#  docker stack deploy -c stack.yml mediawiki
#  nohup docker-compose -f stack.yml up > my.log 2>&1 &
# docker cp extensions/. docker_mediawiki_1:/var/www/html/extensions/
# docker cp --chown=docker:docker LocalSettings.php docker_mediawiki_1:/var/www/html/LocalSettings.php
#
# docker pull wikibase/wikibase 
# Posible errors
# error:    failed to register layer: Untar error on re-exec cmd: fork/exec /proc/self/exe: stale NFS file handle google console
# solution: service docker restart
#
# error: denied: requested access to the resource is denied
# solution: export PROJECT_ID="$(gcloud config get-value project -q)"
#
# tag wikibase/wikibase gcr.io/${PROJECT_ID}/wikibase
# kubectl run wiki-web --image=gcr.io/${PROJECT_ID}/wikibase:v1 --port 8080
# docker run --rm -p 8080:8080 -e MW_ADMIN_NAME="admin" -e MW_ADMIN_PASS="password" -e MW_WG_SECRET_KEY="psecretkey" -e DB_SERVER="35.193.128.232:3306" -e DB_USER="wikiuser" -e DB_PASS="password" -e DB_NAME="my_wiki" gcr.io/${PROJECT_ID}/wikibase
# kubectl run wiki-web --image=gcr.io/${PROJECT_ID}/wikibase --port 8080 --env="MW_ADMIN_NAME=admin" --env="MW_ADMIN_PASS=password" --env="MW_WG_SECRET_KEY=psecretkey" --env="DB_SERVER=35.193.128.232:3306" --env="DB_USER=wikiuser" --env="DB_PASS=password" --env="DB_NAME=my_wiki"
# kubectl expose deployment wiki-web --type=LoadBalancer --port 80 --target-port 8080
version: '2'

gcloud config set project wikipedia
gcloud config set compute/zone us-central1-a
git clone https://github.com/davidgutierrez/Transmipedia.git
cd mediawiki-docker/stable/
export PROJECT_ID="$(gcloud config get-value project -q)"
docker build -t gcr.io/${PROJECT_ID}/transmipedia .
docker images
gcloud docker -- push gcr.io/${PROJECT_ID}/
docker run --rm -p 8080:8080 gcr.io/${PROJECT_ID}/transmipedia
docker run --rm -p 8080:8080 -e MW_ADMIN_NAME="admin" -e MW_ADMIN_PASS="password" -e MW_WG_SECRET_KEY="psecretkey" -e DB_SERVER="35.193.128.232:3306" -e DB_USER="wikiuser" -e DB_PASS="password" -e DB_NAME="my_wiki" gcr.io/${PROJECT_ID}/transmipedia


test2

gcloud config set compute/zone us-central1-a
docker run --name transmipedia -p 8080:80 -d mediawiki
docker cp LocalSettings.php transmipedia:/var/www/html/LocalSettings.php
tar -xzf extensions/Scribunto-REL1_31-106fbf4.tar.gz -C /extensions
docker cp extensions/. transmipedia:/var/www/html/extensions/

docker exec some-mediawiki
docker exec transmipedia rm /var/www/html/LocalSettings.php

Error: error response from daemon: Could not find the file /var/www/html/LocalSettings.php in container docker_mediawiki_1
docker exec docker_mediawiki_1 find -name "LocalSettings.php"
docker cp LocalSettings.php docker_mediawiki_1:./bitnami/mediawiki/LocalSettings.php
docker exec docker_mediawiki_1 rm ./bitnami/mediawiki/LocalSettings.php


Cannot nest 'ETLValidaciones.test/src/main/java' inside 'ETLValidacione
