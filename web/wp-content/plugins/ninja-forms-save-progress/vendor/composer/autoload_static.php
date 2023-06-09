<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9cde310aff8df9e4393b162464e70b3c
{
    public static $prefixLengthsPsr4 = array (
        'N' => 
        array (
            'NinjaForms\\NinjaFormsSaveProgress\\' => 34,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'NinjaForms\\NinjaFormsSaveProgress\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9cde310aff8df9e4393b162464e70b3c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9cde310aff8df9e4393b162464e70b3c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9cde310aff8df9e4393b162464e70b3c::$classMap;

        }, null, ClassLoader::class);
    }
}
