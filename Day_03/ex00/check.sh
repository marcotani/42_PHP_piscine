#!/bin/sh

if command -v composer >/dev/null 2>&1; then
  echo "Composer is installed globally."
  composer --version
else
  echo "Composer is NOT installed globally."
fi

