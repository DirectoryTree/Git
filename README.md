# Git

Call Git commands using PHP

---

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

$git->pull('master');

$git->pull('v1.0.1');
```

#### Fetch

Returns `true` or `false`.

```php
$git = new \DirectoyTree\Git\Git();

$git->fetch();
```

#### Reset

Returns `true` or `false`.

> Note: A `hard` reset will always be performed by default unless specified otherwise.

```php
$git = new \DirectoyTree\Git\Git();

$git->reset($commitOrTag = 'v0.0.9');

$git->reset($commitOrTag = 'v0.0.9', $mode = 'soft');
```

#### Remotes

```php
$git = new \DirectoyTree\Git\Git();

$git->getRemote('origin');

$git->getRemotes();

$git->addRemote('origin', 'https://github.com/DirectoryTree/Git');

$git->setRemoteUrl('origin', 'https://github.com/DirectoryTree/Git');

$git->removeRemote('origin');
```

#### Tags

```php
$git = new \DirectoyTree\Git\Git();

$git->getTags();

$git->getCurrentTag();

$git->getLatestTag();

$git->getNextTag();

$git->getPreviousTag();
```

#### Commits

```php
$git = new \DirectoyTree\Git\Git();

$git->getCommits();

$git->getCommitsBetween();
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
