#!/usr/bin/env php
<?php

class BekInstaller {
    private $systemPaths = [
        '/usr/local/bin',
        '/usr/bin',
        '/bin'
    ];

    public function install()
    {
        // Determine the appropriate installation path
        $installPath = $this->findInstallPath();
        
        if (!$installPath) {
            echo "Error: Could not find a suitable installation directory.\n";
            echo "Please manually symlink the bek script to a directory in your PATH.\n";
            return false;
        }

        // Path to the bek script in the vendor directory
        $vendorBekPath = getcwd() . '/vendor/bekhruz/bek/bin/bek';
        $targetBekPath = $installPath . '/bek';

        // Create symlink
        if (is_link($targetBekPath)) {
            unlink($targetBekPath);
        }

        $symlinkResult = symlink($vendorBekPath, $targetBekPath);

        if ($symlinkResult) {
            echo "Bek successfully installed globally. You can now use 'bek init' anywhere!\n";
            return true;
        } else {
            echo "Failed to create symlink. You may need to use sudo.\n";
            return false;
        }
    }

    private function findInstallPath()
    {
        // Check writable system paths
        foreach ($this->systemPaths as $path) {
            if (is_dir($path) && is_writable($path)) {
                return $path;
            }
        }

        // Fallback to user's home bin directory
        $homeBin = $_SERVER['HOME'] . '/bin';
        if (!is_dir($homeBin)) {
            mkdir($homeBin, 0755, true);
        }

        if (is_writable($homeBin)) {
            // Ensure ~/bin is in PATH
            $this->updateShellConfig($homeBin);
            return $homeBin;
        }

        return null;
    }

    private function updateShellConfig($binPath)
    {
        $shellConfigFiles = [
            $_SERVER['HOME'] . '/.bashrc',
            $_SERVER['HOME'] . '/.zshrc',
            $_SERVER['HOME'] . '/.bash_profile'
        ];

        $pathExport = "\n# Added by Bek VCS\nexport PATH=\"$binPath:\$PATH\"\n";

        foreach ($shellConfigFiles as $configFile) {
            if (file_exists($configFile)) {
                $content = file_get_contents($configFile);
                if (strpos($content, $pathExport) === false) {
                    file_put_contents($configFile, $pathExport, FILE_APPEND);
                    echo "Updated $configFile to include $binPath in PATH\n";
                }
            }
        }
    }
}

// Run the installer
$installer = new BekInstaller();
$installer->install();