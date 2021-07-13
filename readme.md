# Simple Booking

This is a simple booking system demo in Symfony 5.3

# Installation

This document assumes that you have npm, docker, docker-compose, PHP 7.4, composer and symfony command installed in your system.

1. Clone the repository.
2. ```cd``` into the project directory and run ```composer install```
3. Copy the .env.local.example file and remove the .example part ```cp .env.local.example .env.local```
4. Run the docker-compose file ```docker-compose up -d```
5. Run the migrations ```php bin/console doctrine:migrations:migrate```
6. Run the symfony server ```symfony server:start -d ---port=8000```
7. If you need to change the port number in above step, please change it in .env.local file.
8. Install node dependency ```npm install```
9. Run the frontend spa project ```npm run dev```
10. Open the url from the terminal in your browser.