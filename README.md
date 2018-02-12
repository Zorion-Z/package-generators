# Lumen generators

<!-- [![Build Status](https://travis-ci.org/webNeat/lumen-generators.svg?branch=master)](https://travis-ci.org/webNeat/lumen-generators)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/webNeat/lumen-generators/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/webNeat/lumen-generators/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/838624c3-208d-4ba5-84aa-3afc76b093bb/mini.png)](https://insight.sensiolabs.com/projects/838624c3-208d-4ba5-84aa-3afc76b093bb)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://github.com/webNeat/lumen-generators/blob/master/LICENSE)

A collection of generators for [Lumen](http://lumen.laravel.com) and [Laravel 5](http://laravel.com/). -->

## Contents

- [Why ?](#why)

- [Installation](#installation)

- [Quick Usage](#quick-usage)

- [Development Notes](#development-notes)

- [Contributing](#contributing)

## Why ?

In order to develop your tools as a single package, you have to follow many rules to create the package directory and files under it, and these steps may waste some not short time.

So, this command will help you to create an example basic-package to help you develop faster.You should only type in your vendor and package-name to make it set up automatically.

This packages was mainly built to be used with Lumen, but it should work fine with Laravel 5 too.

## Installation

Nowtimes only support local-develop methods.

1. Download or sync this package to your ***{root documen}/packages/*** directory;

2. Add this code to your ***composer.json***
    ```json
    "repositories": [
    {
       "type": "path",
       "url": "{root_path}/packages/Local/package-generators"
    },
    ```

    if the "repositories" has been existed, only copy 
    ```json
    {
       "type": "path",
       "url": "{root_path}/packages/Local/package-generators"
    },
    ```
    under it.

    Then, Add 
    ```json
    "local/package-generators": "dev-master"
    ```
    under 'require' attribute.

3. Then add the service provider in the file ***app/Providers/AppServiceProvider.php*** like the following:
    ```php
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Generators\CommandsServiceProvider');
        }
    }
    ```
    under the register function

4. Turn to ***bootstrap\app.php***
   Add
    ```php
    $app->register(Generators\commandsServiceProvider::class);
    ```
under it anywhere except the place where before `require` and `use` and after the last code `return $app;`;

5. The last step, go to ***cmd console***, and change directory to your lumen root, and execut
    ```
    composer update
    ```
And waiting for successfully install

### Then you can try it with
```
php artisan list
```

If the `package` command shown in the list, means it was successfully installed.

**Don't forget to include the application service provider on your `bootstrap/app.php` and to enable Eloquent and Facades if you are using Lumen**


## Quick Usage

To generate a package resource for your application , you simply need to run one single command. For example: 

```
php artisan package localtest testNewPackage
```

will generate many files under ***package*** directory

```yaml
packages:
+ Localtest
++ TestNewPackage
+++ config
   - CONST.php
   - database.config.php
   - main.config.php
+++ route
   - web.php
+++ src
++++ Argument
    - ArgumentFormat.php
    - ArgumentFormatLoader.php
    - ArgumentParser.php
++++ Commands
    - BaseCommand.php
    - ExampleCommand.php
++++ Exceptions
    - ArgumentFormatException.php
    - ArgumentParserException.php
    - DefaultException.php
++++ Handlers
    - ExampleHandler.php
++++ Helpers
    - FileHelper.php
    - LogHelper.php
    - SystemConfig.php
++++ Models
    - dbModel.php
    - exampleModel.php
++++ Template
    - Template.php
    - TemplateLoader.php
---- CommandsServiceProvider.php
---composer.json
```

Then you can use 
```
php artisan list
```
to check if the `example` command in the list.

## Development Notes

- **Version 1.0.0**

    - The base package generators

## Contributing

Pull requests are welcome :D

