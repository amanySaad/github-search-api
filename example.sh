#!/bin/bash

#Start docker
docker-compose up -d

#auto access docker bash and run composer
docker-compose run amany_server bash -c "cd /var/www/system && composer update"

#access manually => docker-compose run amany_server bash

echo "Goto http://localhost/"