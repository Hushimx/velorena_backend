#!/bin/bash

# Test script to send push notification using curl
# Replace YOUR_TOKEN_HERE with your actual Expo push token

TOKEN="ExponentPushToken[YOUR_TOKEN_HERE]"
URL="https://exp.host/--/api/v2/push/send"

# Create the notification payload
NOTIFICATION='{
  "to": "'$TOKEN'",
  "title": "Test from Curl",
  "body": "This is a direct test notification sent via curl",
  "data": {
    "type": "test",
    "timestamp": "'$(date -u +%Y-%m-%dT%H:%M:%S.000Z)'"
  },
  "sound": "default",
  "badge": 1
}'

echo "Sending notification..."
echo "Token: $TOKEN"
echo "URL: $URL"
echo "Payload: $NOTIFICATION"
echo ""

# Send the notification
curl -X POST "$URL" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Accept-Encoding: gzip, deflate" \
  -d "$NOTIFICATION" \
  -w "\nHTTP Status: %{http_code}\n" \
  -v

echo ""
echo "Done!"
