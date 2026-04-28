<?php

spl_autoload_register(function ($class_name) {
    $directories = [
        __DIR__ . '/src/Core/',
        __DIR__ . '/src/Models/',
        __DIR__ . '/src/Repositories/',
        __DIR__ . '/src/Controllers/'
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Backward compatibility for scripts using $pdo globally
$pdo = Database::getInstance()->getConnection();
?>
