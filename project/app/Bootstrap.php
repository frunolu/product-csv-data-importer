<?php
declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use RuntimeException;

class Bootstrap
{
//declare(strict_types=1);
//
//namespace App;
//
//use Nette\Bootstrap\Configurator;
//use Nette\DI\Container;
//use Nette\DI\ContainerLoader;
//use RuntimeException;
//
//class Bootstrap
//{
//    public static function boot(): Container
//    {
//        $appDir = dirname(__DIR__);
//        $loader = new ContainerLoader($appDir . '/temp', true);
//        $class = $loader->load(function ($compiler) use ($appDir) {
//            $compiler->loadConfig($appDir . '/config/config.neon');
//        });
//
//
//        $container = new $class();
//        if ($container instanceof Container === false) {
//            throw new RuntimeException('Unable to create DI container.');
//        }
//
//        return $container;
//    }


    public static function boot(): Configurator
    {
        $configurator = new Configurator;
        $appDir = dirname(__DIR__);

        //$configurator->setDebugMode('secret@23.75.345.200'); // enable for your remote IP
        $configurator->setDebugMode(true);

        $configurator->setTempDirectory($appDir . '/temp');

        $configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();

        $configurator->addConfig($appDir . '/config/config.neon');
        $configurator->addConfig($appDir . '/config/local.neon');

        return $configurator;
    }
}


