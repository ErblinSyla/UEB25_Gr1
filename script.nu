#!/usr/bin/env nu

sudo docker-compose down
sudo rm -rf ./mysql-data
sudo docker-compose up -d
