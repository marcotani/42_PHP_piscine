#/bin/sh

for dir in */; do
  echo "In $dir:"
  (cd "$dir" && composer show monolog/monolog | grep versions)
  echo
done
