## Table of contents
* [General info](#general-info)
* [Technologies](#technologies)
* [Setup](#setup)

## General info
This is a Laravel 8 with Vue 3 Template 
	
## Technologies
Project is created with:
* Laravel 8
* Vue.js 3
	
## Setup
To setup and run this project, follow the steps below:

```
$ cd personal-website
$ cp .env.example .env
$ composer install 
$ php artisan storage:link
$ php artisan passport:keys
$ php artisan key:generate
$ php artisan migrate:fresh
$ php artisan db:seed
$ php artisan passport:install
$ npm install *
$ npm run watch
```
