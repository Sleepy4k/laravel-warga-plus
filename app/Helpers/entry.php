<?php

// Only include PHP files in the same directory, excluding this file, and prevent directory traversal
$helperDir = realpath(__DIR__);

foreach (glob($helperDir . '/*.php') as $filename) {
    // Ensure the file is in the intended directory and not this file
    if ($filename !== __FILE__ && dirname($filename) === $helperDir) {
        require_once $filename;
    }
}
