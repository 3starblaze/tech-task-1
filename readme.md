# TechTask1

## Setup
1. Copy `sample.env` to `.env` and adjust it for your purposes.
2. Adjust `src/config.js` for your purposes.
3. Install `npm`, `composer`, `docker`, `docker-compose` if they are not installed on your machine.
4. `cd` to the project root
5. Run `composer install`
6. Run `npm install`
7. Run `npx webpack`
8. Run `docker-compose up -d` to launch the application
9. Init MySQL
   1. Create database `<DB_NAME>`
   2. `docker exec -i <mysql container> mysql -uroot -p"<DB_ROOT_PASSWORD>" <DB_NAME> < mysql/init-script.sql` (Replace `<...>` with appropriate data.)
9. You are done

## Development

Use `npx webpack` to build the frontend of the program once or `npx webpack --watch` to watch for the changes and build automatically.

## Overview

The application runs on LAMP stack via docker and docker-compose. The PHP entry point is `./public/index.php` and the most important files are located in `./src/`. `./apache/`, `./mysql/`, `./php/`, `docker-compose.yml` are files that are related to setting up docker and docker-compose.

Routing is managed via custom class `TechTask\Router\Router` in the backend and in `./src/index.js` and `./src/config.js` in the frontend.
