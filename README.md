<!-- readme.md -->

<p align="center">
    <img src="https://raw.githubusercontent.com/directorytree/rocket/master/images/logo.png" width="400">
</p>

<p align="center">
    <a href="https://travis-ci.com/DirectoryTree/Git">
        <img src="https://img.shields.io/travis/DirectoryTree/Git.svg?style=flat-square"/>
    </a>
    <a href="https://scrutinizer-ci.com/g/DirectoryTree/Git/?branch=master">
        <img src="https://img.shields.io/scrutinizer/g/DirectoryTree/Git/master.svg?style=flat-square"/>
    </a>
    <a href="https://packagist.org/packages/DirectoryTree/Git">
        <img src="https://img.shields.io/packagist/dt/DirectoryTree/Git.svg?style=flat-square"/>
    </a>
    <a href="https://packagist.org/packages/DirectoryTree/Git">
        <img src="https://img.shields.io/packagist/v/DirectoryTree/Git.svg?style=flat-square"/>
    </a>
    <a href="https://packagist.org/packages/DirectoryTree/Git">
        <img src="https://img.shields.io/github/license/DirectoryTree/Git.svg?style=flat-square"/>
    </a>
</p>

<p align="center">
    Call Git commands using PHP
</p>

## Requirements

- PHP >= 7.3

## Installation

```bash
composer require directorytree/git
```

## Usage

Before using Git, you must ensure you change PHP's working directory to the root of your Git repository using `chdir()`:

```php
chdir(getcwd());
```

This package also assumes that Git has been installed and is available in your systems `PATH`, so it can be called globally.

```
use DirectoryTree\Git\Git;

$git = new Git($remote = 'origin');

```

## Testing

