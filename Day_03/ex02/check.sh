#!/bin/bash

echo PHPUnit version:
grep -A 5 '"name": "phpunit/phpunit"' composer.lock | grep '"version":' | head -1 | sed -E 's/.*"version": "([^"]+)".*/\1/'

