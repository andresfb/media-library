#!/bin/sh

SOURCE_PATH=../src/
EXCLUDE_FILE=exclude.txt
DESTINATION_PATH=/data1/media-library

echo "Syncing code..."
rsync -ravuh --delete --progress --exclude-from $EXCLUDE_FILE $SOURCE_PATH $DESTINATION_PATH