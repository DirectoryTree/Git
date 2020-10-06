<?php

namespace DirectoryTree\Git\Tests;

use DirectoryTree\Git\Tag;

class TagTest extends TestCase
{
    public function test_is_newer_than()
    {
        $tag = new Tag('v1.0.0');

        $this->assertTrue($tag->isNewerThan('v0.0.9'));
        $this->assertTrue($tag->isNewerThan('v0.0.1'));
        $this->assertTrue($tag->isNewerThan('v000'));

        $this->assertFalse($tag->isNewerThan('v1.0.0'));
        $this->assertFalse($tag->isNewerThan('v1.0.1'));
        $this->assertFalse($tag->isNewerThan('v100'));
        $this->assertFalse($tag->isNewerThan('100'));
    }

    public function test_is_older_than()
    {
        $tag = new Tag('v1.0.0');

        $this->assertTrue($tag->isOlderThan('v1.0.1'));
        $this->assertTrue($tag->isOlderThan('v100'));
        $this->assertTrue($tag->isOlderThan('100'));

        $this->assertFalse($tag->isOlderThan('v1.0.0'));
        $this->assertFalse($tag->isOlderThan('v0.0.9'));
        $this->assertFalse($tag->isOlderThan('v0.0.1'));
        $this->assertFalse($tag->isOlderThan('v000'));
    }

    public function test_is_equal_to()
    {
        $tag = new Tag('v1.0.0');

        $this->assertTrue($tag->isEqualTo('v1.0.0'));
        $this->assertFalse($tag->isEqualTo('v1.0.1'));
        $this->assertFalse($tag->isEqualTo('v0.0.1'));
    }
}
