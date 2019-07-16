<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcd3150f22cba679edbb01f728266c4db
{
    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'think\\composer\\' => 15,
            'think\\' => 6,
        ),
        'g' => 
        array (
            'gmars\\rbac\\' => 11,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'think\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-installer/src',
        ),
        'think\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-image/src',
        ),
        'gmars\\rbac\\' => 
        array (
            0 => __DIR__ . '/..' . '/gmars/tp5-rbac/src',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/application',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcd3150f22cba679edbb01f728266c4db::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcd3150f22cba679edbb01f728266c4db::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
