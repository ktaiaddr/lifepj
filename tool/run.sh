if [ "$1" = "--help" ] || [ "$1" = "-h" ]; then
  echo usage
  echo "run     コンテナ起動"
  echo "down      終了"
  echo "down withv      終了＋ボリューム削除"
  echo "test      PHPUnit実行"
  echo "select DB名 テーブル名      select実行"
  echo "showtable DB名 テーブル名 ・・・ show table実行"
  echo "desc DB名 テーブル名 ・・・ desc実行"
  echo "login web|db ・・・ web|db コンテナにログイン"
  echo "artisan 引き数 ・・・ artisanを実行"
  echo "webpack  ・・・ webpackを実行"
  exit 0
fi


#プロジェクトのルートディレクトリの定義
THISDIR=$(cd $(dirname $0)/..;pwd)

##コンテナ関連定義
#ネットワーク名
CONTAINER_NETWORK="lifenetwork"
#イメージ名
WEB_IMAGE_NAME="alpine-fpm_nginx:latest"
#WEB_IMAGE_NAME="lifepj---php--8.1.0rc1-fpm-alpine3.13:20210921"
MYSQL_IMAGE_NAME="mysql:8.0.24"
NODEJS_IMAGE_NAME="node:14.16.1-alpine"
#コンテナ名
WEB_CONTAINER_NAME="life_web_container"
MYSQL_CONTAINER_NAME="life_mysql_container"
NODEJS_CONTAINER_NAME="lifepj_node"
MAILHOG_CONTAINER_NAME="mailhog"
#ボリューム名
MYSQL_VOLUME_NAME="lifemysql_data"


#################################################################################
#コンテナ群を起動
#################################################################################
start_container(){

  echo run
  #############################
  #MySQL用のボリュームの存在を確認
  #############################
  MYSQL_VOLUME_COUNT=$(docker volume ls | grep -F ${MYSQL_VOLUME_NAME} | wc -l)
  echo MYSQL_VOLUME_COUNT:${MYSQL_VOLUME_COUNT}
  #############################
  #存在しなければボリュームを作成
  if [ $MYSQL_VOLUME_COUNT -eq 0 ]; then
    docker volume create $MYSQL_VOLUME_NAME
  fi

  #########################
  #コンテナネットワークの作成
  #　※存在してたらエラーになる
  #########################
  docker network create $CONTAINER_NETWORK;

  #########################
  #MySQLのコンテナ作成
  #########################
  docker run \
      --network $CONTAINER_NETWORK \
      --rm \
      -v $MYSQL_VOLUME_NAME:/var/lib/mysql \
      --name $MYSQL_CONTAINER_NAME \
      -e MYSQL_ROOT_PASSWORD=$DB_PASS \
      -e TZ=Asia/Tokyo \
      -e BIND-ADDRESS=0.0.0.0 \
      -v $(pwd)/docker_config/mysql_db/my.cnf:/etc/mysql/my.cnf \
      -p 30306:3306 \
      -itd \
      ${MYSQL_IMAGE_NAME} \
      --character-set-server=utf8mb4 \
      --collation-server=utf8mb4_bin

  #########################
  #MailHogのコンテナ作成
  #########################
  docker run \
      --network ${CONTAINER_NETWORK} \
      --rm \
      --name ${MAILHOG_CONTAINER_NAME} \
      -p 8025:8025 \
      -d mailhog/mailhog

  ###########################
  #nginx/php-fpmのコンテナ作成
  ###########################
  docker run \
      --network $CONTAINER_NETWORK \
      --name $WEB_CONTAINER_NAME \
      --rm \
      -itd \
      -v "$THISDIR":/opt \
      -w /opt \
      --entrypoint="/bin/sh" \
      -p 9000:80 \
      -e DB_PASSWORD="$DB_PASS" \
      ${WEB_IMAGE_NAME} \
      -c "
      deluser www-data;
      adduser -u $(id -u) -s /sbin/nologin -D www-data;
      addgroup nginx www-data ;
      addgroup www-data www-data;
      nginx;
      docker-php-entrypoint php-fpm
      "
  ###########################
  #nodejsのコンテナ作成
  ###########################
  docker run \
      -itd \
      --rm \
      -w /opt/react_app \
      -u "$(id -u)" \
      -v "$(pwd)":/opt \
      --name ${NODEJS_CONTAINER_NAME} \
      ${NODEJS_IMAGE_NAME}

  ###########################
  #MySQLのデータ調整
  ###########################
  if [ "$MYSQL_VOLUME_COUNT" -eq 0 ]; then

    for (( i = 0 ; i < 50; i = i+1))
    do
      echo "waiting for MySQL Server Ready ${i}";
      sleep 1

      ##コンテナのログメッセージを監視
      container_ready=$(docker logs $MYSQL_CONTAINER_NAME | grep -F 'MySQL Community'| grep 3306 | wc -l)
      ##MySQLサーバが起動したことを確認
      if [ "$container_ready" -ge 1 ]; then
        echo ""
        echo "########################################################################"
        echo "  MySQL Server Ready!"
        echo "########################################################################"
        echo ""
          ##データベースを作成
          docker exec -it $MYSQL_CONTAINER_NAME /bin/bash -c \
            "mysql -uroot -p${DB_PASS} -e 'create database lifepj; create database lifepj_test;'"
        break
      fi
    done

  fi

  ##########################################
  #Laravelのマイグレーションを実行 ※WEBコンテナ
  ##########################################
  if [ "$MYSQL_VOLUME_COUNT" -eq 0 ]; then
    docker exec -it $WEB_CONTAINER_NAME /bin/sh -c \
        '(cd laravel_app; php artisan migrate; php artisan migrate --env=testing)'
  fi

  ################################################
  #restore の引き数が渡されたら、dumpファイルを取りこみ
  ################################################
  if [ "$MYSQL_VOLUME_COUNT" -eq 0 -a "$1" = "restore" ]; then

    echo restoreします

    #dumpファイルをホストからコンテナ内にコピー
    docker cp "${THISDIR}"/lifepj.dump      $MYSQL_CONTAINER_NAME:/
    docker cp "${THISDIR}"/lifepj_test.dump $MYSQL_CONTAINER_NAME:/

    #dumpファイルを取りこみ
    docker exec -it $MYSQL_CONTAINER_NAME /bin/sh -c \
    "
    MYSQL_PWD=${DB_PASS} mysql -uroot lifepj < /lifepj.dump;
    MYSQL_PWD=${DB_PASS} mysql -uroot lifepj_test < /lifepj_test.dump;
    "
    echo restoreしました

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
if [ "$1" = run ];then

  if [ -z "${DB_PASS}" ] ; then
    echo DB_PASSを設定してください
    exit 1
  fi

  docker ps -a

  if [ -n "$2" -a "$2" = "restore" ] ; then
    start_container restore
  else
    start_container
  fi

  docker ps -a

##################################################################################
# コンテナストップ
##################################################################################
elif [ "$1" = down ];then

  docker ps -a

  #mysqlのダンプを出力
  docker exec -it $MYSQL_CONTAINER_NAME /bin/bash -c \
    "
    MYSQL_PWD=${DB_PASS} mysqldump -uroot lifepj      > lifepj.dump;
    MYSQL_PWD=${DB_PASS} mysqldump -uroot lifepj_test > lifepj_test.dump;
    "
  #ダンプをローカルにコピー
  docker cp $MYSQL_CONTAINER_NAME:/lifepj.dump ./
  docker cp $MYSQL_CONTAINER_NAME:/lifepj_test.dump ./

  echo down
  docker stop \
          ${MYSQL_CONTAINER_NAME} \
          ${WEB_CONTAINER_NAME} \
          ${MAILHOG_CONTAINER_NAME} \
          ${NODEJS_CONTAINER_NAME};

  docker network rm $CONTAINER_NETWORK;
  #DBvolumeも消す場合
  if [ -n "$2" -a "$2" = withv ]; then
    docker volume rm $MYSQL_VOLUME_NAME;
  fi

  docker ps -a

##################################################################################
# PHPUnit実行
##################################################################################
elif [ "$1" = test ];then
  echo "$2"
  unittest "$2"

##################################################################################
# MySQL select
##################################################################################
elif [ "$1" = select ];then

  DATABASE=$2
  TABLE=$3
  if [ -z "$DB_PASS" ];then
    echo DB_PASSを設定してください
    exit 1
  fi

  if [ -z "$DATABASE" -o -z "$TABLE" ];then
    echo ターゲットテーブルが指定されていません
    exit 1
  fi

  docker exec -it $MYSQL_CONTAINER_NAME /bin/sh -c \
          'mysql -uroot -p'$DB_PASS' -e "select version();select * from '$DATABASE'.'$TABLE';"'

##################################################################################
# MySQL テーブル構造確認
##################################################################################
elif [ "$1" = showtable ];then

  DATABASE=$2
  TABLE=$3
  if [ -z "$DB_PASS" ];then
    echo DB_PASSを設定してください
    exit 1
  fi

  if [ -z "$DATABASE" ];then
    echo ターゲットDBが指定されていません
    exit 1
  fi

  COMMAND="mysql -uroot -p$DB_PASS -e 'show tables from $DATABASE'"
  echo "${COMMAND}"
  docker exec -it $MYSQL_CONTAINER_NAME /bin/bash -c "${COMMAND}"

##################################################################################
# MySQL テーブル構造確認
##################################################################################
elif [ "$1" = desc ];then

  DATABASE=$2
  TABLE=$3
  if [ -z "$DB_PASS" ];then
    echo DB_PASSを設定してください
    exit 1
  fi

  if [ -z "$DATABASE" -o -z "$TABLE" ];then
    echo ターゲットテーブルが指定されていません
    exit 1
  fi

  echo "mysql -uroot -p${DB_PASS} -e 'desc ${DATABASE}.${TABLE}'"
  docker exec -it ${MYSQL_CONTAINER_NAME} /bin/bash -c "mysql -uroot -p${DB_PASS} -e 'desc ${DATABASE}.${TABLE}'"

##################################################################################
# ログイン
##################################################################################
elif [ "$1" = login ];then

  if [ -z "$2" ]; then
    echo 対象コンテナを入力してください
    exit 1
  fi

  if [ "$2" = "web" ]; then docker exec -it -u "$(id -u)" $WEB_CONTAINER_NAME /bin/sh; exit 0; fi
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

  COMMAND="(cd laravel_app; php artisan $2;)"
  echo "${COMMAND}"
  docker exec -it -u www-data $WEB_CONTAINER_NAME /bin/sh -c "${COMMAND}"

##################################################################################
# webpack
##################################################################################
elif [ "$1" = webpack ];then

  docker exec -it -w /opt/react_app -u "$(id -u)" ${NODEJS_CONTAINER_NAME} npx webpack -w
  exit 0

fi


