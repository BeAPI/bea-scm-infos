# BEA SCM Infos #

## Description ##

Get infos from versionning system you use

## Features

* basic infos GIT (branch, tag, last commit)
* tool page, path to .git folder can be changed
* disponible en Français

## TODO

* svn & mercurial parts
* dashboard widget


## Important to know ##

By default only super admin (or admin on single install) can see admin bar infos.

To get this work, use composer :

```
git clone https://github.com/BeAPI/bea-scm-infos && cd bea-scm-infos
composer install
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
* BEA/SCM/show_admin_bar

## Screenshots

admin bar view :

![admin bar infos](/assets/img/screen-admin-bar.png?raw=true)

tool page :

![set options](/assets/img/screen-options.png?raw=true)

## Changelog ##

### 0.19

* initial
