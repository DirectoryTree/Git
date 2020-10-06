<?php

namespace DirectoryTree\Git\Tests;

use DirectoryTree\Git\Git;
use TitasGailius\Terminal\Terminal;

class GitTest extends TestCase
{
    public function test_git_pull()
    {
        Terminal::fake(['git pull origin v1.0.0 --ff-only' => Terminal::response()->successful()]);

        $this->assertTrue((new Git)->pull('v1.0.0'));
    }

    public function test_git_fetch()
    {
        Terminal::fake(['git fetch --tags -f' => Terminal::response()->successful()]);

        $this->assertTrue((new Git)->fetch());
    }

    public function test_git_reset()
    {
        Terminal::fake(['git reset --hard v1.0.0' => Terminal::response()->successful()]);

        $this->assertTrue((new Git)->reset('v1.0.0'));
    }

    public function test_git_get_all_tags()
    {
        Terminal::fake([
            'git tag' => Terminal::response("v1.0.0\nv1.0.1\nv1.0.2")->output()
        ]);

        $this->assertEquals(['v1.0.0', 'v1.0.1', 'v1.0.2'], (new Git)->getAllTags());
    }

    public function test_get_latest_tag()
    {
        Terminal::fake([
            'git tag' => Terminal::response("v1.0.0\nv1.0.1\nv1.0.2")->output()
        ]);

        $this->assertEquals('v1.0.2', (new Git)->getLatestTag());
    }

    public function test_get_current_tag()
    {
        Terminal::fake(['git describe --tags' => Terminal::response('v1.0.3')->output()]);

        $this->assertEquals('v1.0.3', (new Git)->getCurrentTag());
    }

    public function test_get_commits_between()
    {
        Terminal::fake([
            'git log --pretty=oneline {{ $start }}...{{ $end }}' => [
                Terminal::line('3a7b1a21b4ab9be386869a100d22b35c2b4befd1 (tag: v1.0.2) Added build status'),
                Terminal::line('bd9d931d12f40cb84378bcdd1d4e84a198d2c54b Fixed tests')
            ]
        ]);

        $this->assertEquals([
            '3a7b1a21b4ab9be386869a100d22b35c2b4befd1' => '(tag: v1.0.2) Added build status',
            'bd9d931d12f40cb84378bcdd1d4e84a198d2c54b' => 'Fixed tests',
        ], (new Git)->getCommitsBetween('v1.0.0', 'v1.0.2'));
    }

    public function test_get_all_remotes()
    {
        Terminal::fake([
            'git remote -v' => [
                Terminal::line('origin	https://github.com/directorytree/rocket (fetch)'),
                Terminal::line('origin	https://github.com/directorytree/rocket (push)'),
            ]
        ]);

        $this->assertEquals([
            'origin' => [
                'fetch' => 'https://github.com/directorytree/rocket',
                'push' => 'https://github.com/directorytree/rocket',
            ],
        ], (new Git)->getRemotes());
    }

    public function test_get_remote()
    {
        Terminal::fake([
            'git remote -v' => [
                Terminal::line('origin	https://github.com/directorytree/rocket (fetch)'),
                Terminal::line('origin	https://github.com/directorytree/rocket (push)'),
            ]
        ]);

        $this->assertEquals([
            'fetch' => 'https://github.com/directorytree/rocket',
            'push' => 'https://github.com/directorytree/rocket',
        ], (new Git)->getRemote('origin'));
    }

    public function test_add_remote()
    {
        Terminal::fake([
            'git remote add {{ $remote }} {{ $url }}' => Terminal::response()->successful(),
        ]);

        $this->assertTrue((new Git)->addRemote('origin', 'https://github.com/directorytree/rocket'));
    }

    public function test_set_remote()
    {
        Terminal::fake([
            'git remote set-url {{ $remote }} {{ $newUrl }}' => Terminal::response()->successful(),
        ]);

        $this->assertTrue((new Git)->setRemoteUrl('origin', 'https://github.com/directorytree/rocket'));
    }

    public function test_remove_remote()
    {
        Terminal::fake([
            'git remote rm {{ $remote }}' => Terminal::response()->successful(),
        ]);

        $this->assertTrue((new Git)->removeRemote('origin'));
    }

    public function test_convert_remote_to_token_fails_with_empty_token()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Git)->convertRemoteToToken('stevebauman', '', 'origin');
    }

    public function test_get_next_tag()
    {
        Terminal::fake([
            'git tag' => Terminal::response("v1.0.0\nv1.0.1\nv1.0.2")->output()
        ]);

        $this->assertEquals('v1.0.2', (new Git)->getNextTag('v1.0.1'));
    }

    public function test_get_previous_tag()
    {
        Terminal::fake([
            'git tag' => Terminal::response("v1.0.0\nv1.0.1\nv1.0.2")->output()
        ]);

        $this->assertEquals('v1.0.1', (new Git)->getPreviousTag('v1.0.2'));
    }
}