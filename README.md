# Git

A featherweight class for calling Git commands using PHP.

<a href="https://travis-ci.com/DirectoryTree/Git">
    <img src="https://img.shields.io/travis/DirectoryTree/Git.svg?style=flat-square"/>
</a>
<a href="https://scrutinizer-ci.com/g/DirectoryTree/Git/?branch=master">
    <img src="https://img.shields.io/scrutinizer/g/DirectoryTree/Git/main.svg?style=flat-square"/>
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

## Requirements

- PHP >= 7.3

## Installation

```bash
composer require directorytree/git
```

Before getting started, you must ensure you change PHP's working directory to the root of your Git repository using `chdir()`:

```php
// The current working directory:
chdir(getcwd());

// A specific directory:
chdir('/usr/sbin/httpd');
```

This package also assumes that Git has been installed and is available in your systems `PATH`, so it can be called globally.

## Usage

Create a new `Git` instance, and set the remote you want to work with:

```php
use DirectoryTree\Git\Git;

$git = new Git($remote = 'origin');
```

### Available Commands

#### Pull

Returns `true` or `false`.

```php
$git = new \DirectoyTree\Git\Git();

$successful = $git->pull('master');

$successful = $git->pull('v1.0.1');
```

#### Fetch

Returns `true` or `false`.

```php
$git = new \DirectoyTree\Git\Git();

$successful = $git->fetch();
```

#### Reset

Returns `true` or `false`.

> Note: A `hard` reset will always be performed by default unless specified otherwise.

```php
$git = new \DirectoyTree\Git\Git();

$successful = $git->reset($commitOrTag = 'v0.0.9');

$successful = $git->reset($commitOrTag = 'v0.0.9', $mode = 'soft');
```

#### Remotes

##### Get

Returns an `array` of remote URLs (empty array on failure):

```php
$urls = $git->getRemote('origin');
```

##### Get All

Returns an `array` of remotes, with their URLs (empty array on failure):

```php
$remotes = $git->getRemotes();
```

##### Add

Returns `true` or `false`.

```php
$success = $git->addRemote('origin', 'https://github.com/DirectoryTree/Git');
```

##### Set URL

Returns `true` or `false`.

```php
$successful = $git->setRemoteUrl('origin', 'https://github.com/DirectoryTree/Git');
```

##### Remove 

Returns `true` or `false`.

```php
$successful = $git->removeRemote('origin');
```

#### Tags

##### Get All 

Returns an `array` of tags (empty `array` on failure):

```php
$tags = $git->getTags();
```

##### Get Current

Returns the current repository's tag (`false` on failure).

```php
$currentTag = $git->getCurrentTag();
```

##### Get Latest

Returns the current repository's latest (`false` on failure).

```php
$latestTag = $git->getLatestTag();
```

##### Get Next

Returns the current repository's tag that is directly after the given (`false` on failure).

```php
$nextTag = $git->getNextTag('v1.0.0');
```

##### Get Previous

```php
$previousTag = $git->getPreviousTag('v1.0.1');
```

#### Commits

#### Get All

Returns an `array` of commits (empty `array` on failure):

```php
$commits = $git->getCommits();

$commits = $git->getCommits(['from' => '9d26e0']);

$commits = $git->getCommits(['from' => '9d26e0', 'to' => '8bf0de6']);
```

##### Get Between

Shorthand for the above method.

```php
$commits = $git->getCommitsBetween($from = '9d26e0', $to = '8bf0de6');
```

## Testing

Git uses the PHP package [TitasGailius/terminal](https://github.com/TitasGailius/terminal) for
running all commands. This means you can utilize it's testing framework for executing all Git commands:

```php
use DirectoryTree\Git\Git;

class GitTest extends TestCase
{
    public function test_git_pull()
    {
        Terminal::fake(['git pull {{ $remote }} {{ $commit }} --ff-only' => Terminal::response()->successful()]);

        $this->assertTrue((new Git)->pull('v1.0.0'));
    }
}
```
