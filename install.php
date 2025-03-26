#!/usr/bin/env php
<?php

class BekInstaller {
    private $systemPaths = [
        '/usr/local/bin',
        '/usr/bin'
    ];

    public function install()
    {
        // Determine the base directory of the current script
        $currentDir = getcwd();
        
        // Find the bek binary in the vendor/bin directory
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

                // Create symlink with error handling
                $result = @symlink($bekBinary, $symlinkPath);
                
                if ($result) {
                    echo "Bek has been installed globally. You can now use 'bek init' anywhere!\n";
                    echo "Symlink created at: $symlinkPath\n";
                    return true;
                }
            }
        }

        // Fallback instructions if symlink creation fails
        echo "Could not create global symlink automatically.\n";
        echo "Manual installation instructions:\n";
        echo "1. Run: sudo ln -s $bekBinary /usr/local/bin/bek\n";
        echo "2. Or add the following to your PATH:\n";
        echo "   export PATH=\"$currentDir/vendor/bin:\$PATH\"\n";

        return false;
    }
}

// Check if script is being run directly
if (php_sapi_name() === 'cli') {
    $installer = new BekInstaller();
    $exitCode = $installer->install() ? 0 : 1;
    exit($exitCode);
}