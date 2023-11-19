<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1239a1f3e68760cd79129bd5c15e4482
{
    public static $files = array (
        '872c44ae2fa5f07d10e3701bc20e2ffe' => __DIR__ . '/..' . '/shawnmccool/phansi/src/functions.php',
        '9ad9cbc46a6a260d6e72fdf24cce83fd' => __DIR__ . '/../..' . '/src/Testing/testing.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'TicTacToe\\' => 10,
        ),
        'P' => 
        array (
            'PhAnsi\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'TicTacToe\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'PhAnsi\\' => 
        array (
            0 => __DIR__ . '/..' . '/shawnmccool/phansi/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1239a1f3e68760cd79129bd5c15e4482::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1239a1f3e68760cd79129bd5c15e4482::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1239a1f3e68760cd79129bd5c15e4482::$classMap;

        }, null, ClassLoader::class);
    }
}
