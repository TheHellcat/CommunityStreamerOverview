#!/bin/bash

if [ "$SYMFONY_ENV" == "hcwww2" ]; then
   sed -i 's#"symfony/symfony": "3.3.*"#"symfony/symfony": "3.2.*"#g' composer.json
   php composer.phar update
fi

php composer.phar install

rm -Rf var/cache/*
rm -Rf var/logs/*
rm -Rf var/sessions/*

php bin/console -e=$SYMFONY_ENV doctrine:migrations:migrate

rm -Rf var/cache/*
rm -Rf var/logs/*
rm -Rf var/sessions/*
