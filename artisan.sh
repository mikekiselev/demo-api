#!/bin/bash
cd src/
docker-compose exec app php artisan $1 $2 $3 $4 $5 $6 $7