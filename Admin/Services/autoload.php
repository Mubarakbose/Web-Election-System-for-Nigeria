<?php

/**
 * Services Autoloader
 * Include this file once to auto-load all service classes.
 * Requires that Helpers autoloader is already loaded first.
 */

$serviceDir = __DIR__;

$services = [
    'BaseService.php',
    'ContestantService.php',
    'StaffService.php',
    'PollingUnitService.php',
    'NewsService.php',
    'MessageService.php',
];

foreach ($services as $service) {
    $filePath = $serviceDir . '/' . $service;
    if (file_exists($filePath)) {
        require_once $filePath;
    }
}
