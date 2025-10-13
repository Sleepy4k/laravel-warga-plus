# Laravel HIPMI PT Telkom University Purwokerto Content Management Service

This project is a content management service built with Laravel for HIPMI PT Telkom University Purwokerto. It enables users to register, manage their products, and submit articles, while administrators can oversee application settings, manage content, verify user data, and ensure secure operations across the platform.

## Overview

### User Dashboard

- User can see their dashboard including product, article, and much more
- Submit their spesific task easily only with some step on submission form

### Admin Dashboard

- The heart of the system, where authorized users can log in and access powerful tools.
- Admins can modify application setting, such as the title, description, and other essential information.
- Content management: Admins can update the web timeline content, ensuring it stays fresh and relevant.

### Security and Permissions

- Role-based access control (RBAC): Different admin roles (admin, petugas, team, etc.) with varying permissions.
- Authentication and authorization: Ensuring only authorized users can access the dashboard.
- Content Security Policy (CSP): Checking all content is safe to go, and prevent any xss injection for better security.

## Tech Stack

**Frontend:** Blade Engine

**Backend:** Laravel

**Database:** MySQL

**Authentication:** JWT or Sanctum

## Run Locally

Clone the project

~~~bash
git clone https://github.com/hipmi-pt-tup/cms.git
~~~

Go to the project directory

~~~bash
cd cms
~~~

Install composer dependencies

~~~bash
composer install
~~~

Install node dependencies

~~~bash
npm install
~~~

Build node project

~~~bash
npm run build
~~~

Run pre-setup command

~~~bash
php artisan naka:pre-setup
~~~

Start the server

~~~bash
composer run dev
~~~

## Notes

- Composer installation

If you want to speed up the project on production mode,
you can run installation composer dependencies using
this command to install only production dependencies,
but keep in mind after you install using this command,
you can't use any development tools like seeder and other

~~~bash
composer install --no-dev --optimize-autoloader
~~~

- Optimized current project

If you already installing this project and want to set
composer autoloader faster than you must run this command

~~~bash
composer dump-autoload --optimize
~~~

- Composer installation Error

If you met installation error with message `need github token` or `permissions are sufficient for this personal access token`,
then you need to generate your github personal access token on `https://github.com/settings/tokens`, and make sure to checklist
`read:packages` permission, and generate token, and paste on token input after type your github username, token example

~~~bash
ghp_xxxxxxxxxxxxxxxxxxxxxxxxxxx
-------------------------------
github_pat_xxxxxx_xxxxxxxxxxxxx
~~~

- Pre Setup Error

To solve this error you need to run pre setup command.
copy command below and paste it on your terminal.

~~~bash
php artisan naka:pre-setup
~~~

- Lack of Performance

If your website seems laggy, or time to load content is slow as hell.
run this command and dont forget to clear all cache before.

~~~bash
php artisan optimize:clear
php artisan optimize
~~~

or you could do simply with this command

~~~bash
php artisan naka:re-cache
~~~

- Storage symlink failed

When you deploy this on cpanel or similar, you need to change index.php base path,
after that, you can change storage link path on `config/filesystems.php`, scroll to bottom of config,
and change path, for example

~~~php
'links' => [
    public_path('../../public_html/storage') => storage_path('app/public'),
],
~~~

- Migration Error (SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 1000 bytes)

This happen when laravel default charset is different from database charset, i met this error before because of database server using `InnoDB`,
which mean it use latin1 charset, and max of string only 125 or 191 character, to fix this change your migration engine on `config/database.php`, scroll into connections list, and change your drive engine and set into `InnoDB`, for example

~~~php
'engine' => 'InnoDB',
~~~

- Sitemap Endpoint

On production maybe you need to generate sitemap again due different domain,
but don't worry about this, just run command below it will generate sitemap
automatic using current endpoint (as long as you set APP_URL on .env)

~~~bash
php artisan make:sitemap
~~~

- Public Assets

If you adding some assets from external for example adding icons from iconscout,
or something, make sure you add it on local public assets, it will boost your
load time on production and development, as long as you used the optimized one.

And if you considering server bandwitdh or had to change system structure,
change it using CDN system or using external hosting provider. I don't recommend
this way, because security things. just keep it local as long as you can.

- Database Query

When you updating this backend or reworking this project, i personal highly
recommend you to do not query all fields, just select field(s) that only
you need, you must considering this as important thing, first of all it
will takes server resources, second when you using inertia or api based,
you will exposed all fields on public, even for the unused fields.
just select for the fields that you needed.

## Security Things

- Content Security Policy (CSP)

For any reasons please don't turn on this feature, because we are implementing danger rendering on blade,
so for security issue, keep enable this feature, if you met any error such as script blocked or something,
read article about how to setting up CSP, so you can handle this error

- User Session

When this web deployed, please change session setting on `config/session.php`, keep in mind out main goal
is to secure user data, so follow this config for better session security,

~~~php
'encrypt' => env('SESSION_ENCRYPT', true)
'secure' => env('SESSION_SECURE_COOKIE', true),
~~~

- Secure Header

After all feature implemented, you can change secure heade config for better security,
so set HTTP Strict Transport Security config (force into https protocol), so it will be like this

~~~php
'hsts' => [
    'enable' => true,

    'max-age' => 31536000,

    'include-sub-domains' => false,

    'preload' => true,
],
~~~

## Environment Variables

To run this project, you will need to add the following environment variables to your .env file

`APP_NAME`
`APP_KEY`
`APP_URL`
`APP_DEBUG`
`APP_ENV`

`DB_CONNECTION`
`DB_HOST`
`DB_DATABASE`
`DB_USERNAME`
`DB_PASSWORD`

`MAIL_MAILER`
`MAIL_HOST`
`MAIL_PORT`
`MAIL_USERNAME`
`MAIL_PASSWORD`
`MAIL_ENCRYPTION`
`MAIL_FROM_ADDRESS`

## Acknowledgements

- [Laravel](https://laravel.com/docs/12.x)
- [MySQL](https://dev.mysql.com/doc)
- [Content Security Policy](https://github.com/spatie/laravel-csp)
- [Secure Headers](https://github.com/bepsvpt/secure-headers)
- [Cacheable Model](https://github.com/elipZis/laravel-cacheable-model)
- [File Export/Import](https://docs.laravel-excel.com/3.1)
- [Sweet Alert](https://realrashid.github.io/sweet-alert)
- [Page Speed](https://github.com/renatomarinho/laravel-page-speed)
- [Role and Permissions](https://spatie.be/docs/laravel-permission/v6)
- [Sitemap Generator](https://github.com/spatie/laravel-sitemap)
- [Data Tables](https://yajrabox.com/docs/laravel-datatables/12.0)
- [Browser Detect](https://github.com/hisorange/browser-detect)
- [Image Compressor](https://image.intervention.io/v3)

## Feedback

If you have any feedback, please make an issue with detail description, proof of concept, and composer dependencies list
