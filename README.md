# Laravel restful api
## Description
&#160; &#160; &#160; &#160;Use the laravel framework and JWT to bulid a restful api

## Features
* Restful api
* Encryption output support
* JWT
* Multi language support

## Project install and configure
* clone project `git clone https://github.com/denghuien/laravel-restful-api.git`
* run `cp .env.example .env`
* configure the **.env** your own mysql info
* run `composer install`
* run `php artisan key:generate`
* run `php artisan jwt:secret`
* run `php artisan migrate` to bulid database

## Nginx configure
* fastcgi_params add `fastcgi_param  HTTP_REQUEST_ID    $request_id`;
* nginx.conf config log_format `$request_id  - $remote_addr - $remote_user [$time_local] "$request" '
  '$status $body_bytes_sent "$http_referer" '
  '"$http_user_agent" "$http_x_forwarded_for"`
* configure server ` set $trace_id "${request_id}";
  add_header x-request-id $trace_id;` add request_id to the response header
  
## Usage
* Multi language support,add language to the request header, for example:x-api-locale:zh_CN
* Register:   
     url: hostname/api/passport/register
     method: post  
     parameter: email,password,password_confirm  
     response:
  ![](https://github.com/denghuien/laravel-restful-api/blob/main/storage/register.png)
* Login:   
    url: hostname/api/passport/login
    method: post  
    parameter: email,password  
    response:
    ![](https://github.com/denghuien/laravel-restful-api/blob/main/storage/login.png)
* User info:   
  url: hostname/api/passport
  method: get
  response:
  ![](https://github.com/denghuien/laravel-restful-api/blob/main/storage/user.png)
