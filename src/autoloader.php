<?php
class Autoloader {
    public static function load($className) {
        $filePath = str_replace('\\', '/', $className) . '.php';
        $filePath = '../'.$filePath;
        if (file_exists($filePath)) {
            require_once($filePath);
        } else {
            echo "Class file not found: $filePath\n";
        }
    }
}

spl_autoload_register('Autoloader::load');
?>