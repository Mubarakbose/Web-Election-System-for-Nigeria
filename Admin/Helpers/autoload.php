<?php

/**
 * AdminHelpers Autoloader
 * Include this file once to auto-load all Admin helper classes.
 */

$helperDir = __DIR__;

$helpers = [
    'RequestInput.php',
    'FileUploadValidator.php',
    'ErrorHandler.php',
    'AdminConstants.php',
    'Auth.php',
    'Pagination.php',
];

foreach ($helpers as $helper) {
    $filePath = $helperDir . '/' . $helper;
    if (file_exists($filePath)) {
        require_once $filePath;
    }
}
