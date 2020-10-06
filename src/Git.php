<?php

namespace DirectoryTree\Git;

use InvalidArgumentException;
use TitasGailius\Terminal\Terminal;

class Git
{
    use FormatsConsoleOutput;

    /**
     * The git remote.
     *
     * @var string
     */
    protected $remote;

    /**
     * Constructor.
     *
     * @param string $remote
     */
    public function __construct($remote = 'origin')
    {
        $this->remote = $remote;
    }

    /**
     * Update to the given repository tag.
     *
     * @param string $commit
     *
     * @return bool
     */
    public function pull($commit)
    {
        return Terminal::with(['remote' => $this->remote, 'commit' => $commit])
            ->run('git pull {{ $remote }} {{ $commit }} --ff-only')
            ->successful();
    }

    /**
     * Fetch the repository's tags.
     *
     * @return bool
     */
    public function fetch()
    {
        return Terminal::run('git fetch --tags -f')->successful();
    }

    /**
     * Reset the repository to the HEAD, or to the given tag.
     *
     * @param string|null $commit
     * @param string      $mode
     *
     * @return bool
     */
    public function reset($commit = null, $mode = 'hard')
    {
        return Terminal::with(['commit' => $commit, 'mode' => $mode])
            ->run('git reset --{{ $mode }} {{ $commit }}')
            ->successful();
    }

    /**
     * Get all available tags.
     *
     * @return array
     */
    public function getTags()
    {
        $response = Terminal::run('git tag');

        return $response->successful()
            ? $this->getLinesFromResponse($response)
            : [];
    }

    /**
     * Get the latest repository tag.
     *
     * @return string|false
     */
    public function getLatestTag()
    {
        $tags = $this->getTags();

        return end($tags);
    }

    /**
     * Get the next tag according to the current.
     *
     * @param string|null $currentTag
     *
     * @return string|false
     */
    public function getNextTag($currentTag = null)
    {
        return $this->fetchTagByOperator(
            $currentTag ?? $this->getCurrentTag(), $this->getTags(), $operand = 'next'
        );
    }

    /**
     * Get the previous tag according to the current.
     *
     * @param string|null $currentTag
     *
     * @return string|false
     */
    public function getPreviousTag($currentTag)
    {
        return $this->fetchTagByOperator(
            $currentTag ?? $this->getCurrentTag(), $this->getTags(), $operator = 'previous'
        );
    }

    /**
     * Fetches the previous or next tag in line.
     *
     * @param string $currentTag
     * @param array  $tags
     * @param string $operator
     *
     * @return string|false
     */
    protected function fetchTagByOperator($currentTag, $tags = [], $operator = 'next')
    {
        if (($key = array_search($currentTag, $tags)) !== false) {
            return $tags[$operator == 'next' ? ++$key : --$key] ?? false;
        }

        return false;
    }

    /**
     * Get the current repository tag.
     *
     * @return string|false
     */
    public function getCurrentTag()
    {
        $response = Terminal::run('git describe --tags');

        return $response->successful()
            ? $this->trimOutput($response->output())
            : false;
    }

    /**
     * Get a list of the repository's commits.
     *
     * @param array $options
     *
     * @return array
     */
    public function getCommits($options = [])
    {
        $command = $this->buildGitLogCommand($options);

        $response = empty($options)
            ? Terminal::run($command)
            : Terminal::with($options)->run($command);

        if (! $response->successful()) {
            return [];
        }

        $lines = $this->getLinesFromResponse($response);

        $commits = [];

        foreach ($lines as $commit) {
            [$ref, $message] = str_split($commit, 40);

            $commits[$ref] = trim($message);
        }

        return $commits;
    }

    /**
     * Build the git log command.
     *
     * @param array $options
     *
     * @return string
     */
    protected function buildGitLogCommand($options)
    {
        $command = 'git log --pretty=oneline';

        $args = array_filter([
            'start' => isset($options['start']) ? '{{ $start }}' : null,
            'end' => isset($options['end']) ? '{{ $end }}' : null,
        ]);

        return sprintf('%s %s', $command, implode('...', $args));
    }

    /**
     * Get an associative array of the list of commits between two tags.
     *
     * @param string $startCommit
     * @param string $endCommit
     *
     * @return array|false
     */
    public function getCommitsBetween($startCommit, $endCommit)
    {
        return $this->getCommits(['start' => $startCommit, 'end' => $endCommit]);
    }

    /**
     * Add a remote URL to the current git repo.
     *
     * @param string $remote
     * @param string $url
     *
     * @return bool
     */
    public function addRemote($remote, $url)
    {
        return Terminal::with(['remote' => $remote, 'url' => $url])
            ->run('git remote add {{ $remote }} {{ $url }}')
            ->successful();
    }

    /**
     * Change the URLs for the remote.
     *
     * @param string $remote
     * @param string $newUrl
     * @param
     *
     * @return bool
     */
    public function setRemoteUrl($remote, $newUrl)
    {
        return Terminal::with(['remote' => $remote, 'newUrl' => $newUrl])
            ->run('git remote set-url {{ $remote }} {{ $newUrl }}')
            ->successful();
    }

    /**
     * Remove the specified remote.
     *
     * @param string $remote
     *
     * @return bool
     */
    public function removeRemote($remote)
    {
        return Terminal::with(['remote' => $remote])
            ->run('git remote rm {{ $remote }}')
            ->successful();
    }

    /**
     * Get the URLs for the remote.
     *
     * @param string $remote
     *
     * @return array
     */
    public function getRemote($remote)
    {
        foreach ($this->getRemotes() as $name => $urls) {
            if ($name == $remote) {
                return $urls;
            }
        }

        return [];
    }

    /**
     * Get the available tracked repositories
     *
     * @return array
     */
    public function getRemotes()
    {
        $response = Terminal::run('git remote -v');

        if (! $response->successful()) {
            return [];
        }

        $lines = $this->getLinesFromResponse($response);

        $remotes = [];

        foreach ($lines as $line) {
            [$remote, $url, $type] = $this->splitLineOutput($line);

            $type = str_replace(['(', ')'], '', $type);

            $remotes[$remote][$type] = $url;
        }

        return $remotes;
    }

    /**
     * Convert the given git remote URL to token.
     *
     * @param string $username
     * @param string $token
     * @param string $remote
     *
     * @return bool
     */
    public function convertRemoteToToken($username, $token, $remote)
    {
        if (empty($token)) {
            throw new InvalidArgumentException('A token must be provided.');
        }

        if (! $urls = $this->getRemote($remote)) {
            return false;
        }

        return $this->setRemoteUrl(
            $remote, $this->makeTokenBasedUrl($username, $token, $urls['push'])
        );
    }

    /**
     * Make a token based URL from the given.
     *
     * @param string $username
     * @param string $token
     * @param string $url
     *
     * @return string
     */
    protected function makeTokenBasedUrl($username, $token, $url)
    {
        $parts = parse_url($url);

        return implode('/', [
            $parts['scheme'].':/',
            $username.':'.$token.'@'.$parts['host'].$parts['path'],
        ]);
    }
}
