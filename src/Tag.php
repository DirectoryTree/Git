<?php

namespace DirectoryTree\Git;

class Tag
{
    /**
     * The current repository tag.
     *
     * @var string
     */
    protected $current;

    /**
     * Constructor.
     *
     * @param string $current
     */
    public function __construct($current)
    {
        $this->current = $current;
    }

    /**
     * Determine if the current tag is newer than the given.
     *
     * @param string $tag
     *
     * @return bool|int
     */
    public function isNewerThan($tag)
    {
        return $this->compareTags($this->current, $tag, '>');
    }

    /**
     * Determine if the current tag is older than the given.
     *
     * @param string $tag
     *
     * @return bool|int
     */
    public function isOlderThan($tag)
    {
        return $this->compareTags($this->current, $tag, '<');
    }

    /**
     * Determine if the current tag is equal to the given.
     *
     * @param string $tag
     *
     * @return bool|int
     */
    public function isEqualTo($tag)
    {
        return $this->compareTags($this->current, $tag, '=');
    }

    /**
     * Version compare the first and second tag by the operator.
     *
     * @param string $first
     * @param string $second
     * @param string $operator
     *
     * @return bool|int
     */
    protected function compareTags($first, $second, $operator = '=')
    {
        return version_compare(
            $this->makeComparableTag($first),
            $this->makeComparableTag($second),
            $operator
        );
    }

    /**
     * Make a comparable tag.
     *
     * @param string $tag
     *
     * @return string
     */
    protected function makeComparableTag($tag)
    {
        return substr(ltrim(trim($tag), 'v'), 0, 5);
    }
}
