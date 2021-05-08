#! /bin/bash
APPLICATION_NAME=creativestreams

ssh-add ~/.ssh/id_rsa
echo "DOCKER_BUILDKIT=1 docker build -t $APPLICATION_NAME ."
DOCKER_BUILDKIT=1 docker build -t "$APPLICATION_NAME" . --progress=plain
