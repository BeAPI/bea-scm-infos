# BEA SCM Infos #

## Description ##

Get infos from versionning system you use

## Requirements

* proc_open enabled on PHP installation => means it runs on PHP safe mod unlike exec()
* composer

## Features

* basic infos GIT (branch, tag, last commit)
* tool page, path to .git folder can be changed
* disponible en Français

## TODO

* dashboard widget


## Important to know ##

By default only super admin (or admin on single install) can see admin bar infos.

To get this work, use composer :

```
git clone https://github.com/BeAPI/bea-scm-infos && cd bea-scm-infos && composer install
```

Then go to your dashboard

In case you want to include this small plugin to your project running composer you can add this line to your composer.json :

```
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/BeAPI/bea-scm-infos"
    }
  ]
```

then run the command :

```
composer require bea/bea-scm-infos dev-master
```

## Filters

* BEA/SCM/git_folder_path
* BEA/SCM/items_args
* BEA/SCM/hide_admin_bar
* BEA/SCM/hide_admin_footer_text

## Screenshots

admin bar view :

![admin bar infos](/assets/img/screen-the-admin-bar.png?raw=true)

tool page :

![set options](/assets/img/screen-tool-page.png?raw=true)

error case :

![set options](/assets/img/screen-the-error.png?raw=true)

## Changelog ##

### 3.0
* March 2018
* Change lib to provide compat for PHP 7.0


### 2.2
* 09 November 2017
* change filter name admin bar that can be confusing
* add info as admin footer text too
* new filter BEA/SCM/hide_admin_footer_text

### 2.1
* 03 November 2017
* composer json type : wordpress-plugin

### 2.0
* lib Gitter is no longer supported so include it as library
* delete composer require Gitter
* reformat admin page
* delete useless filter footer text
* add more filters such as BEA/SCM/transient_expiration

### 1.0
* first release
* update composer
* disable option switch versioning system

### 0.20
* handle errors in a better way
* red color for error GIT or path - green whan it's all right
* add link in admin footer text + avoid overriding plugins that already use the filter

### 0.19
* initial
