#!/bin/bash

SUBJECT=contact_importer
LOCK_FILE=/tmp/$SUBJECT.lock

if [ -f "$LOCK_FILE" ]; then
   echo "Script is already running"
   exit
fi

trap "rm -f $LOCK_FILE" EXIT
touch $LOCK_FILE
cd ./eloquent
php index.php
