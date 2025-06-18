<?php
// Autoload Task1 classes via composer
if (file_exists(__DIR__ . '/../task_1/vendor/autoload.php')) {
    require __DIR__ . '/../task_1/vendor/autoload.php';
}

spl_autoload_register(static function($class) {
    $prefixes = [
        'Heliostat\\Task3\\' => __DIR__ . '/../task_3/src/',
        'Heliostat\\Task1\\' => __DIR__ . '/../task_1/src/',
    ];
    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($class, $prefix, $len) === 0) {
            $relative = str_replace('\\', '/', substr($class, $len)) . '.php';
            $file = $baseDir . $relative;
            if (file_exists($file)) {
                require $file;
            }
        }
    }
});

require __DIR__ . '/../task_4/parser.php';