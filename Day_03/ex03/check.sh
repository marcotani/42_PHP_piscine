#!/bin/bash

for folder in install update; do
  echo "===== $folder ====="

  LOCKFILE="$folder/composer.lock"
  LOCKHASH_BEFORE=""
  LOCKHASH_AFTER=""

  if [ ! -f "$LOCKFILE" ]; then
    echo "No composer.lock found in $folder"
    echo
    continue
  fi

  # Calculate hash before
  LOCKHASH_BEFORE=$(sha256sum "$LOCKFILE" | awk '{print $1}')
  echo "composer.lock hash before command: $LOCKHASH_BEFORE"

  # Run command depending on folder
  if [ "$folder" = "install" ]; then
    echo "Running composer install in $folder..."
    (cd "$folder" && composer install --no-interaction --no-progress >/dev/null)
  else
    echo "Running composer update in $folder..."
    (cd "$folder" && composer update --no-interaction --no-progress >/dev/null)
  fi

  # Calculate hash after
  LOCKHASH_AFTER=$(sha256sum "$LOCKFILE" | awk '{print $1}')
  echo "composer.lock hash after command:  $LOCKHASH_AFTER"

  # Compare
  if [ "$LOCKHASH_BEFORE" = "$LOCKHASH_AFTER" ]; then
    echo "composer.lock did NOT change after the command."
  else
    echo "composer.lock WAS UPDATED after the command."
  fi

  echo
done

