# Simple Booking

This is a simple booking system demo in Symfony 5.3

# Installation

This document assumes that you have docker, docker-compose, PHP 7.4, composer and Symfony command installed in your system.

1. cd into the project directory and run ```composer install```
2. Copy the .env.local.example file and remove the .example part ```cp .env.local.example .env.local```
3. Run the docker-compose file ```docker-compose up -d```
4. Run the migrations ```php bin/console doctrine:migrations:migrate```
5. Run the symfony server ```symfony server:start -d```