#/bin/bash

. ./.env

php_wait() {
    until [ "$res" = "999" ]
    do
        echo "$res"
        res=$(docker exec $PREFIX-$PHP_CONTAINER echo 999)
        sleep 1
    done
}

db_wait() {
    until [ "$res" = "999" ]
    do
        echo "$res"
        res=$(docker exec "$PREFIX-$DB_CONTAINER" echo 999)
        sleep 1
    done
}

docker_up() {
    docker-compose --project-directory ./ -f ./docker/docker-compose.yml up -d
}

docker_down() {
    docker-compose --project-directory ./ -f ./docker/docker-compose.yml down
}

app_dirs() {
    mkdir -p ./app/web/uploads
    mkdir -p ./app/web/uploads/images
}

app_permissions() {
    chmod 777 ./app/runtime
    chmod 777 ./app/web/assets
    chmod 777 ./app/web/uploads
    chmod 777 ./app/web/uploads/images
    chmod 777 ./app/web/uploads/preview
}

init () {
    start
    sleep 1
    app_dirs
    sleep 1
    app_permissions
}

start() {
    docker_up
    sleep 1
    php_wait
    sleep 1
    db_wait
    sleep 1
    require
    migrate
}

stop() {
    docker_down
}

require() {
    docker exec "$PREFIX-$PHP_CONTAINER" composer install
}

migrate() {
    docker exec "$PREFIX-$PHP_CONTAINER" /app/yii migrate/up --migrationPath=@app/modules/hosting/migrations --interactive=0
    #docker exec "$PREFIX-$PHP_CONTAINER" /app/yii migrate/up --interactive=0
}

ready_message() {
    echo -e "\nService \033[32m\"$APP_NAME\"\033[0m avaliable on url \033[32mhttp://localhost:$NGINX_EXT_PORT\033[0m\n"
}

get_version() {
    IS_DIG='^([[:digit:]])([.]{1,}[[:digit:]]+)?$'
    IS_WSL="wsl"

    # елсли указан номер версии, используем его
    if [[ $PARAM =~ $IS_DIG ]]; then
        COMPOSER_VERSION=$PARAM
        export COMPOSER_VERSION=$PARAM
    # если указан wsl, используем версию 3.3
    elif [[ $PARAM == $IS_WSL ]]; then
        COMPOSER_VERSION=3.3
        export COMPOSER_VERSION=3.3
    # в остальных случаях используем последнюю версию 3.9
    else
        COMPOSER_VERSION=3.9
        export COMPOSER_VERSION=3.9
    fi
}

check_version() {
    check_min=$(echo "scale=0; $COMPOSER_VERSION/3" | bc)
    check_max=$(echo "scale=0; $COMPOSER_VERSION/4" | bc)

    if [[ $check_min -eq 0 ]]; then
        echo -e "Error: The minimum version of docker-compose.yml must be greater than or equal to 3, $COMPOSER_VERSION given\n"
        exit
    elif [[ $check_max -ne 0 ]]; then
        echo -e "Error: The minimum version of docker-compose.yml should be less than 4, $COMPOSER_VERSION given\n"
        exit
    fi
}

usage_message() {
    echo "Usage: app.sh [init|start|stop]"
    echo "Starting app.sh for the first time may take a few minutes"
}

COMMAND=$1
PARAM=$2

get_version
check_version

case "$1" in
    start)
        start
        ready_message
        ;;
    stop)
        stop
        ;;
    init)
        init
        sleep 5
        ready_message
        ;;
    *)
        usage_message
    ;;
esac
