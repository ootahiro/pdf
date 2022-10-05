<?php
spl_autoload_register(static function ($class) {
    if (str_starts_with($class, 'App\Slip\\')) {
        $class = str_replace('App\Slip\\', "", $class);
        $filename = str_replace('\\', DIRECTORY_SEPARATOR, $class) .'.php';
        $path = __DIR__ . DIRECTORY_SEPARATOR . $filename;

        if (is_file($path)) {
            require_once $path;
        }
    }
});