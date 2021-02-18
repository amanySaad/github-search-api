#!/bin/bash

if [ "$1" == "bash" ]; then
        docker-compose run amany_server bash
elif [ "$1" == "build" ]; then
	docker-compose up -d --build
elif [ "$1" == "close" ]; then
	docker-compose down
elif [ "$1" == "all" ]; then
	docker container stop $(docker container ls -aq)
	docker container rm $(docker container ls -aq)
elif [ "$1" == "clear" ]; then
	docker rm -f amany_localserver
	docker rm -f amany_mail_server
	docker rm -f amany_server
else
	docker-compose up -d
fi
