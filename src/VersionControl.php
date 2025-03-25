<?php

namespace Bekhruz\Bek;

class VersionControl 
{
    private $repositoryPath;

    public function __construct($path = '.')
    {
        $this->repositoryPath = realpath($path);
    }

    public function init()
    {
        $vcsDir = $this->repositoryPath . '/.vcs';

        if (!is_dir($vcsDir)) {
            mkdir($vcsDir);
            mkdir($vcsDir . '/commits');
            file_put_contents($vcsDir . '/log.txt', "Version Control System Initialized\n");
            return "Repository initialized successfully!";
        }

        return "Repository already exists!";
    }

    public function commit($filename)
    {
        $fullPath = $this->repositoryPath . '/' . $filename;

        if (!file_exists($fullPath)) {
            throw new \Exception("Error: File '$filename' does not exist!");
        }

        $vcsDir = $this->repositoryPath . '/.vcs';
        if (!is_dir($vcsDir)) {
            throw new \Exception("Error: Repository not initialized! Run `bek init` first.");
        }

        $commitId = time(); 
        $content = file_get_contents($fullPath);
        
        file_put_contents("{$vcsDir}/commits/{$commitId}.txt", $content);
        file_put_contents("{$vcsDir}/log.txt", "Commit $commitId: $filename saved\n", FILE_APPEND);

        return "Committed: $filename -> .vcs/commits/$commitId.txt";
    }
}