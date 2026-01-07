<?php

/**
 * PollingStaff Helpers Autoloader
 * Include this file once to auto-load all helper classes.
 */

$helperDir = __DIR__;

$helpers = [
    'RequestInput.php',
    'FileUploadValidator.php',
    'ErrorHandler.php',
    'StaffConstants.php',
    'Auth.php',
    'Pagination.php',
    'EmailSender.php',
    'StaffContext.php',
    'FlashRenderer.php',
];

foreach ($helpers as $helper) {
    $filePath = $helperDir . '/' . $helper;
    if (file_exists($filePath)) {
        require_once $filePath;
    }
}
