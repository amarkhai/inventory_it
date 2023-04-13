#!/bin/sh

echo ELASTICSEARCH_URL: "$ELASTICSEARCH_URL"
echo INDEX_NAME: "$INDEX_NAME"
echo USER: "$USER"
echo PASSWORD: "$PASSWORD"

until $(curl -XGET --insecure --user $USER:$PASSWORD "$ELASTICSEARCH_URL/_cluster/health?wait_for_status=green" > /dev/null); do
    printf 'WAITING FOR THE ELASTICSEARCH ENDPOINT BE AVAILABLE, trying again in 1 seconds \n'
    sleep 1
done

# Create the index
curl -XPUT --insecure --user $USER:$PASSWORD "$ELASTICSEARCH_URL/$INDEX_NAME" -H 'Content-Type: application/json' -d @index-settings.json
