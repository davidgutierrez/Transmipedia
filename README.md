
Enable Kubernete
 https://console.cloud.google.com/apis/api/container.googleapis.com/overview?project=wikipedia-208621 
 
gcloud container clusters create transmipedia-cluster --num-nodes=3

gcloud container clusters get-credentials transmipedia-cluster

gcloud compute disks create --size 200GB transmipedia-disk
# Transmipedia
docker-compose -f stack.yml up

tar -xzf extensions/Scribunto-REL1_30-4e3a20b.tar.gz -C extensions/

docker cp extensions/. transmipedia_mediawiki_1:/var/www/html/extensions/

docker cp LocalSettings.php transmipedia_mediawiki_1:/var/www/html/LocalSettings.php

docker exec transmipedia_mediawiki_1 php /var/www/html/maintenance/update.php
# Clena Docker
docker rm -f $(docker ps -a -q)

docker volume rm $(docker volume ls -q)
# Access via "http://localhost:8080"
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
# docker run --rm -p 8080:8080 -e MW_ADMIN_NAME="admin" -e MW_ADMIN_PASS="password" -e MW_WG_SECRET_KEY="psecretkey" -e DB_SERVER="35.193.128.232:3306" -e DB_USER="wikiuser" -e DB_PASS="1q2w3e4r5t6y7u8i9o" -e DB_NAME="my_wiki" gcr.io/${PROJECT_ID}/wikibase
# kubectl run wiki-web --image=gcr.io/${PROJECT_ID}/wikibase --port 8080 --env="MW_ADMIN_NAME=admin" --env="MW_ADMIN_PASS=password" --env="MW_WG_SECRET_KEY=psecretkey" --env="DB_SERVER=35.193.128.232:3306" --env="DB_USER=wikiuser" --env="DB_PASS=1q2w3e4r5t6y7u8i9o" --env="DB_NAME=my_wiki"
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
docker run --rm -p 8080:8080 -e MW_ADMIN_NAME="admin" -e MW_ADMIN_PASS="password" -e MW_WG_SECRET_KEY="psecretkey" -e DB_SERVER="35.193.128.232:3306" -e DB_USER="wikiuser" -e DB_PASS="1q2w3e4r5t6y7u8i9o" -e DB_NAME="my_wiki" gcr.io/${PROJECT_ID}/transmipedia


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
