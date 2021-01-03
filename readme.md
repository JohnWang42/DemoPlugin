# Demo WP Plugin #

Example WordPress plugin for Inpsyde, by Johnny Wang

## CSS and JS Prep ##

Included is a gulpfile I typically use on projects to automate compilation of my SCSS. It also minifies the CSS and JS automatically. Just use the following steps:

1. Run `npm install`
2. Then use the command `gulp`. The default will convert the SCSS into a singular style file as well as minify the JS & CSS.

## Caching Implementation ##

For caching, I used the WordPress Transients API. It automatically serializes any JSON that is obtained and implements WordPress's built-in caching. This allows it to automatically integrate with any caching the user might implement such as memcached.

## Testing Implementation ##

I used the suggested combo of PHPUnit and Brain Monkey to integrate testing into this plugin. Run `./vendor/bin/phpunit --testdox tests` to see test results.

The tests implemented check if the hooks are getting implemented properly.

## Composer Packages ##

Linting and Code Style Enforcement

* `inpsyde/php-coding-standards`
* `"squizlabs/php_codesniffer`

Unit Testing

* `brain/monkey`
* `phpunit/phpunit`

PHP required version and extension

* `php`
* `ext-json` (Ensures extension is installed to enable usage of `json_encode` and `json_decode`)

Support for specifying installation paths for Composer

* `composer/installers` (in conjuction with the `type` option)
