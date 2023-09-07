<?php

namespace Wer\PartnerSlider;

class Autoloader
{
    private static $directories = [
        'Utils',
        'ShortCodes', 
        'PostTypes'
    ];

    /**
     * Method to register the autoloader to prevent loading them manually
     *
     * @return void
     */
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $namespace = 'Wer\\PartnerSlider\\';
            $base_dir = plugin_dir_path( dirname( __FILE__, 2 ) );

            $relative_class = str_replace($namespace, '', $class);
            $file_path = str_replace('\\', '.', $relative_class) . '.php';

            foreach ( self::$directories as $subdirectory ) {
                $file = "{$base_dir}includes/{$subdirectory}/{$file_path}";

                if (file_exists($file)) {
                    require $file;
                    break;
                }
            }
        });
    }
}
