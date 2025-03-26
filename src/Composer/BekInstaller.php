<?php
namespace Bekhruz\Bek\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Composer\Util\Filesystem;

class BekInstaller implements PluginInterface, EventSubscriberInterface
{
    private $composer;
    private $io;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        // Required by PluginInterface
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // Required by PluginInterface
    }

    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => 'runInstaller',
            ScriptEvents::POST_UPDATE_CMD => 'runInstaller'
        ];
    }

    public static function runInstaller(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $installScript = $vendorDir . '/bekhruz/bek/install.php';
        
        if (file_exists($installScript)) {
            $io = $event->getIO();
            $io->write('<info>Running Bek installation script...</info>');
            
            passthru("php $installScript", $returnVar);
            
            if ($returnVar === 0) {
                $io->write('<info>Bek installed successfully!</info>');
            } else {
                $io->writeError('<error>Bek installation script failed.</error>');
            }
        }
    }
}