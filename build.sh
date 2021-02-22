#! /bin/bash
APPLICATION_NAME=creativestreams

ssh-add ~/.ssh/id_rsa
echo "DOCKER_BUILDKIT=1 docker build -t $APPLICATION_NAME ."
DOCKER_BUILDKIT=1 docker build -t "$APPLICATION_NAME" . --ssh github=$SSH_AUTH_SOCK --build-arg SSH_KEY="$(cat ~/.ssh/id_rsa)" --progress=plain
