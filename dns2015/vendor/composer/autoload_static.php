<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc474cc171e7801a4a7be7490bdfb66aa
{
    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'Jsvrcek\\ICS\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Jsvrcek\\ICS\\' => 
        array (
            0 => __DIR__ . '/..' . '/jsvrcek/ics/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc474cc171e7801a4a7be7490bdfb66aa::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc474cc171e7801a4a7be7490bdfb66aa::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
