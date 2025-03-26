#!/usr/bin/env php
<?php

class BekInstaller {
    private $systemPaths = [
        '/usr/local/bin',
        '/usr/bin'
    ];

    public function install()
    {
        // Find the bek binary in the current vendor directory
        $currentDir = getcwd();
        $bekBinary = $currentDir . '/vendor/bin/bek';

        if (!file_exists($bekBinary)) {
            echo "Error: Bek binary not found. Ensure you've run 'composer require'.\n";
            return false;
        }

        // Try to find a writable system path
        foreach ($this->systemPaths as $path) {
            if (is_dir($path) && is_writable($path)) {
                $symlinkPath = $path . '/bek';
                
                // Remove existing symlink if it exists
                if (is_link($symlinkPath)) {
                    unlink($symlinkPath);
                }

                // Create symlink
                $result = symlink($bekBinary, $symlinkPath);
                
                if ($result) {
                    echo "Bek has been installed globally. You can now use 'bek init' anywhere!\n";
                    echo "Symlink created at: $symlinkPath\n";
                    return true;
                }
            }
        }

        // Fallback instructions
        echo "Could not create global symlink automatically.\n";
        echo "Manual installation instructions:\n";
        echo "1. Run: sudo ln -s $bekBinary /usr/local/bin/bek\n";
        echo "2. Or add the following to your PATH:\n";
        echo "   export PATH=\"$currentDir/vendor/bin:\$PATH\"\n";

        return false;
    }
}

// Run the installer
$installer = new BekInstaller();
$installer->install();