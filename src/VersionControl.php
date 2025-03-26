<?php

namespace Bekhruz\Bek;

class VersionControl 
{
    private $repositoryPath;
    private $vcsDir;

    public function __construct($path = '.')
    {
        $this->repositoryPath = realpath($path);
        $this->vcsDir = $this->repositoryPath . '/.vcs';
    }

    public function init()
    {
        if (!is_dir($this->vcsDir)) {
            mkdir($this->vcsDir);
            mkdir($this->vcsDir . '/commits');
            file_put_contents($this->vcsDir . '/log.txt', "Version Control System Initialized\n");
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

        if (!is_dir($this->vcsDir)) {
            throw new \Exception("Error: Repository not initialized! Run `bek init` first.");
        }

        $commitId = $this->generateCommitId();
        $content = file_get_contents($fullPath);
        
        // Store the file content
        file_put_contents("{$this->vcsDir}/commits/{$commitId}.txt", $content);
        
        // Store metadata about the commit
        $commitMetadata = [
            'id' => $commitId,
            'filename' => $filename,
            'timestamp' => time(),
            'message' => 'Commit created'
        ];
        file_put_contents("{$this->vcsDir}/commits/{$commitId}.json", json_encode($commitMetadata));

        // Log the commit
        file_put_contents("{$this->vcsDir}/log.txt", 
            "Commit $commitId: $filename saved at " . date('Y-m-d H:i:s') . "\n", 
            FILE_APPEND
        );

        return "Committed: $filename -> .vcs/commits/$commitId.txt";
    }

    public function log()
    {
        $logFile = "{$this->vcsDir}/log.txt";

        if (!file_exists($logFile)) {
            return "No commits found.";
        }

        return file_get_contents($logFile);
    }

    public function revert($commitId, $filename)
    {
        $commitFile = "{$this->vcsDir}/commits/{$commitId}.txt";
        $metadataFile = "{$this->vcsDir}/commits/{$commitId}.json";

        // Validate commit exists
        if (!file_exists($commitFile)) {
            throw new \Exception("Error: Commit ID $commitId not found!");
        }

        // Read commit metadata
        $metadata = json_decode(file_get_contents($metadataFile), true);

        // Ensure the filename matches
        if ($metadata['filename'] !== $filename) {
            throw new \Exception("Error: Commit $commitId does not match filename $filename");
        }

        $targetFile = $this->repositoryPath . '/' . $filename;

        // Restore the file content
        $restoredContent = file_get_contents($commitFile);
        file_put_contents($targetFile, $restoredContent);

        // Log the revert
        file_put_contents("{$this->vcsDir}/log.txt", 
            "Reverted $filename to commit $commitId at " . date('Y-m-d H:i:s') . "\n", 
            FILE_APPEND
        );

        return "Restored $filename to version $commitId";
    }

    private function generateCommitId()
    {
        // Generate a more unique commit ID
        return uniqid('commit_', true);
    }

    // Optional: List available commits for a specific file
    public function listCommits($filename)
    {
        $commits = [];
        $commitFiles = glob("{$this->vcsDir}/commits/*_*.json");

        foreach ($commitFiles as $commitMetadataFile) {
            $metadata = json_decode(file_get_contents($commitMetadataFile), true);
            if ($metadata['filename'] === $filename) {
                $commits[] = $metadata;
            }
        }

        // Sort commits by timestamp
        usort($commits, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        return $commits;
    }
}