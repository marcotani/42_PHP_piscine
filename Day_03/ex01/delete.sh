#/bin/sh

for dir in */; do
  rm -rf "$dir"/vendor "$dir"/composer.lock
done
