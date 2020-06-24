#!/usr/bin/env bash

echo "Updating repository"
git pull

# exit when any command fails
set -e

echo "Installing composer dependencies"
composer install

echo ""
echo "Running tests"
vendor/bin/phpstan analyze
vendor/bin/phpunit --testsuite Unit
