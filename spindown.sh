#!/bin/bash
APPLICATION_NAME=creativestreams

helm del $APPLICATION_NAME
docker rm -f "${APPLICATION_NAME}_local_mount"