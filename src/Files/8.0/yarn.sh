#!/bin/sh
FILE=package.json

if [ -f "$FILE" ]; then
  yarn install --force && encore dev --watch
fi