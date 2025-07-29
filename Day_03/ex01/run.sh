#/bin/sh

for dir in */; do
  cd $dir
    echo "Running composer in $dir"
    composer install
    cd ../
done
