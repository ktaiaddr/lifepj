#!/bin/bash

is_source(){

  if test "${FUNCNAME[1]}" = 'source'; then
    return 0;
  else
    return 1;
  fi;

}
is_source
IS_SOURCE=$?

if [ $IS_SOURCE = 1 ]; then
  echo 'sourceで呼び出してください';
  exit 0
fi;

DB_PASS=$1
if [ -z "$DB_PASS" ]; then
  echo 引き数でDB_PASSを指定してください
fi

export DB_PASS="$DB_PASS";

if [ ! -e .dpass ]; then
  echo "$DB_PASS" > .dpass
else
  echo "$DB_PASS" > pass.tmp
  cat .dpass > pass2.tmp
  cat pass.tmp pass2.tmp | uniq > .dpass
fi

