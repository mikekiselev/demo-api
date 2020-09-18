#!/bin/bash
cd src/
docker run --rm -v $(pwd):/app composer $1 $2
#sudo chown -R $USER:$USER ~/laravel-app
