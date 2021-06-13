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
      --network $CONTAINER_NETWORK \
      --name $WEB_CONTAINER_NAME \
      --rm \
      -itd \
      -v $THISDIR:/opt \
      -w /opt \
      $WEB_CONTAINER_IMAGE_NAME

  if [ $MYSQL_VOLUME_COUNT -eq 0 ]; then
    docker exec -it $WEB_CONTAINER_NAME /bin/sh -c \
        '(cd laravel_app; php artisan migrate; php artisan migrate --env=testing)'
  fi
}

unittest(){

    echo unittest

    docker exec -it $WEB_CONTAINER_NAME /bin/sh -c \
            '( cd laravel_app; php /opt/laravel_app/vendor/bin/phpunit \
            --configuration /opt/laravel_app/phpunit.xml \
            --testdox \
            --fail-on-risky \
            --colors=always \
            --verbose \
            --strict-coverage \
            --dont-report-useless-tests \
            --coverage-html coverage )'
}

#コンテナ起動
if [ $1 = run ];then

  if [ -z ${DB_PASS} ] ; then
    echo DB_PASSを設定してください
    exit 1
  fi

  start_container

#コンテナストップ
elif [ $1 = down ];then
  echo down
  docker stop $MYSQL_CONTAINER_NAME $WEB_CONTAINER_NAME;
  docker network rm $CONTAINER_NETWORK;
  #DBvolumeも消す場合
  if [ -n "$2" -a "$2" = db ]; then
    docker volume rm $MYSQL_CONTAINER_VOLUME_NAME;
  fi

#テスト実行
elif [ $1 = test ];then
  unittest

elif [ $1 = select ];then

  if [ -z $DB_PASS ];then
    echo DB_PASSを設定してください
    exit 1
  fi
  docker exec -it $MYSQL_CONTAINER_NAME /bin/sh -c \
          'mysql -uroot -p'$DB_PASS' -e "select * from lifepj_test.refuelings;"'
fi



