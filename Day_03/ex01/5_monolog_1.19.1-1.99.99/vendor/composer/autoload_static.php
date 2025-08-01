<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdcaea176ae2fd5f7ac1b8c0f75f5b665
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdcaea176ae2fd5f7ac1b8c0f75f5b665::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdcaea176ae2fd5f7ac1b8c0f75f5b665::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdcaea176ae2fd5f7ac1b8c0f75f5b665::$classMap;

        }, null, ClassLoader::class);
    }
}
