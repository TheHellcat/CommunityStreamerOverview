<?php

// The environment variable SYMFONY_ENV MUST be set
if (false === ($env = getenv('SYMFONY_ENV'))) {
    $env = 'default';
}

require 'app_' . $env . '.php';
