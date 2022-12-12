<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit585ece1569092c7f4ed4d4485e4a94f1
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit585ece1569092c7f4ed4d4485e4a94f1', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit585ece1569092c7f4ed4d4485e4a94f1', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit585ece1569092c7f4ed4d4485e4a94f1::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
