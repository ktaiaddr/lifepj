THISDIR=$(cd $(dirname $0)/..;pwd)

CONTAINER_NETWORK="lifenetwork"

WEB_CONTAINER_IMAGE_NAME="alpine-fpm_nginx:latest"
WEB_CONTAINER_NAME="life_web_container"

MYSQL_CONTAINER_NAME="life_mysql_container"
MYSQL_CONTAINER_VOLUME_NAME="lifemysql_data"

start_container(){

  echo run

  MYSQL_VOLUME_COUNT=$(docker volume ls | grep -F $MYSQL_CONTAINER_VOLUME_NAME | wc -l)
  echo $MYSQL_VOLUME_COUNT
  if [ $MYSQL_VOLUME_COUNT -eq 0 ]; then
    docker volume create $MYSQL_CONTAINER_VOLUME_NAME
  fi

  docker network create $CONTAINER_NETWORK;

  docker run \
      --network $CONTAINER_NETWORK \
      --rm \
      -v $MYSQL_CONTAINER_VOLUME_NAME:/var/lib/mysql \
      --name $MYSQL_CONTAINER_NAME \
      -e MYSQL_ROOT_PASSWORD=$DB_PASS \
      -e TZ=Asia/Tokyo \
      -e BIND-ADDRESS=0.0.0.0 \
      -itd mysql:5.7 --character-set-server=utf8 --collation-server=utf8_unicode_ci

  if [ $MYSQL_VOLUME_COUNT -eq 0 ]; then

    for (( i = 0 ; i < 50; i = i+1))
    do
      echo "sleep ${i}";
      sleep 1
      container_ready=$(docker logs $MYSQL_CONTAINER_NAME | grep -F 'MySQL Community'| grep 3306 | wc -l)
      if [ $container_ready -ge 1 ]
      then
        echo
        echo "########################################################################"
        echo "  MySQL Server Ready!"
        echo "########################################################################"
        echo
          docker exec -it $MYSQL_CONTAINER_NAME /bin/bash -c \
            "mysql -uroot -p${DB_PASS} -e 'create database lifepj; create database lifepj_test;'"
        break
      fi
    done

  fi

  docker run \
      --network ${CONTAINER_NETWORK} \
      --rm \
      --name mailhog \
      -p 8025:8025 \
      -d mailhog/mailhog


  docker run \
      --network $CONTAINER_NETWORK \
      --name $WEB_CONTAINER_NAME \
      --rm \
      -itd \
      -v $THISDIR:/opt \
      -w /opt \
      --entrypoint="/bin/sh" \
      -p 9000:80 \
      $WEB_CONTAINER_IMAGE_NAME \
      -c "
      deluser www-data;
      adduser -u $(id -u) -s /sbin/nologin -D www-data;
      addgroup nginx www-data ;
      addgroup www-data www-data;
      nginx;
      docker-php-entrypoint php-fpm
      "


  if [ $MYSQL_VOLUME_COUNT -eq 0 ]; then
    docker exec -it $WEB_CONTAINER_NAME /bin/sh -c \
        '(cd laravel_app; php artisan migrate; php artisan migrate --env=testing)'
  fi
}

unittest(){

    echo unittest

    if [ -n "$1" ]; then
      echo "test_file: "$1
      docker exec -it --user=www-data $WEB_CONTAINER_NAME /bin/sh -c \
              '( cd laravel_app; php /opt/laravel_app/vendor/bin/phpunit --configuration /opt/laravel_app/phpunit.xml '$1')'
    else
      docker exec -it --user=www-data $WEB_CONTAINER_NAME /bin/sh -c \
            '( cd laravel_app; php /opt/laravel_app/vendor/bin/phpunit \
            --configuration /opt/laravel_app/phpunit.xml \
            --testdox \
            --fail-on-risky \
            --colors=always \
            --verbose \
            --strict-coverage \
            --dont-report-useless-tests \
            --coverage-html coverage )'
    fi
}

##################################################################################
# コンテナ起動
##################################################################################
if [ $1 = run ];then

  if [ -z ${DB_PASS} ] ; then
    echo DB_PASSを設定してください
    exit 1
  fi

  docker ps -a

  start_container

  docker ps -a

##################################################################################
# コンテナストップ
##################################################################################
elif [ $1 = down ];then

  docker ps -a

  echo down
  docker stop $MYSQL_CONTAINER_NAME $WEB_CONTAINER_NAME mailhog;
  docker network rm $CONTAINER_NETWORK;
  #DBvolumeも消す場合
  if [ -n "$2" -a "$2" = db ]; then
    docker volume rm $MYSQL_CONTAINER_VOLUME_NAME;
  fi

  docker ps -a

##################################################################################
# PHPUnit実行
##################################################################################
elif [ $1 = test ];then
  echo $2
  unittest $2

##################################################################################
# MySQL select
##################################################################################
elif [ $1 = select ];then

  DATABASE=$2
  TABLE=$3
  if [ -z $DB_PASS ];then
    echo DB_PASSを設定してください
    exit 1
  fi

  if [ -z "$DATABASE" -o -z "$TABLE" ];then
    echo ターゲットテーブルが指定されていません
    exit 1
  fi

  docker exec -it $MYSQL_CONTAINER_NAME /bin/sh -c \
          'mysql -uroot -p'$DB_PASS' -e "select * from '$DATABASE'.'$TABLE';"'

##################################################################################
# MySQL テーブル構造確認
##################################################################################
elif [ $1 = showtable ];then

  DATABASE=$2
  TABLE=$3
  if [ -z $DB_PASS ];then
    echo DB_PASSを設定してください
    exit 1
  fi

  if [ -z "$DATABASE" ];then
    echo ターゲットDBが指定されていません
    exit 1
  fi

  COMMAND="mysql -uroot -p"$DB_PASS" -e 'show tables from "$DATABASE"'"
  echo ${COMMAND}
  docker exec -it $MYSQL_CONTAINER_NAME /bin/bash -c "${COMMAND}"

##################################################################################
# MySQL テーブル構造確認
##################################################################################
elif [ $1 = desc ];then

  DATABASE=$2
  TABLE=$3
  if [ -z $DB_PASS ];then
    echo DB_PASSを設定してください
    exit 1
  fi

  if [ -z "$DATABASE" -o -z "$TABLE" ];then
    echo ターゲットテーブルが指定されていません
    exit 1
  fi

  echo "mysql -uroot -p"$DB_PASS" -e 'desc "$DATABASE"."$TABLE"'"
  docker exec -it $MYSQL_CONTAINER_NAME /bin/bash -c "mysql -uroot -p"$DB_PASS" -e 'desc "$DATABASE"."$TABLE"'"

##################################################################################
# ログイン
##################################################################################
elif [ "$1" = login ];then

  if [ -z "$2" ]; then
    echo 対象コンテナを入力してください
    exit 1
  fi

  if [ "$2" = "web" ]; then docker exec -it -u $(id -u) $WEB_CONTAINER_NAME /bin/sh; exit 0; fi
  if [ "$2" = "db"  ]; then docker exec -it $MYSQL_CONTAINER_NAME /bin/bash; exit 0; fi

  echo 対象コンテナが見つかりません

##################################################################################
# artisanコマンド
##################################################################################
elif [ "$1" = artisan ];then

  if [ -z "$2" ]; then
    echo artisanのコマンド引数が入力されていません
    exit 1
  fi

  COMMAND="(cd laravel_app; php artisan "$2";)"
  echo ${COMMAND}
  docker exec -it -u www-data $WEB_CONTAINER_NAME /bin/sh -c "${COMMAND}"
fi



