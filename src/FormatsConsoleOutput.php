<?php

namespace DirectoryTree\Git;

use TitasGailius\Terminal\Response;

trait FormatsConsoleOutput
{
    /**
     * Get lines from the console command response.
     *
     * @param Response $response
     *
     * @return array
     */
    protected function getLinesFromResponse(Response $response)
    {
        return array_filter(
            preg_split('/\n+/', $response->output()) ?? []
        );
    }

    /**
     * Trims the console output of tabs and spaces.
     *
     * @param string $output
     *
     * @return string
     */
    protected function trimOutput($output)
    {
        $split = $this->splitLineOutput($output);

        return reset($split);
    }

    /**
     * Split the line output by spaces.
     *
     * @param string $output
     *
     * @return array
     */
    protected function splitLineOutput($output)
    {
        return preg_split('/\s+/', $output) ?? [];
    }
}
