#!/bin/bash

php composer.phar install

rm -Rf var/cache/*
rm -Rf var/logs/*
rm -Rf var/sessions/*

php bin/console -e=$SYMFONY_ENV doctrine:migrations:migrate

rm -Rf var/cache/*
rm -Rf var/logs/*
rm -Rf var/sessions/*
