<?php

/**
 * Constants
 * Centralized configuration and constants used across the Admin portal.
 */

class AdminConstants
{
    // File Upload Settings
    const UPLOAD_MAX_SIZE = 5000000; // 5MB
    const UPLOAD_VALID_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];
    const UPLOAD_VALID_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif'];

    // Upload Directories
    const CONTESTANT_UPLOAD_DIR = __DIR__ . '/../ContestantsImages/';
    const STAFF_UPLOAD_DIR = __DIR__ . '/../StaffsImages/';

    // Pagination
    const ITEMS_PER_PAGE = 10;

    // User Types
    const USER_TYPE_ADMIN = 1;
    const USER_TYPE_STAFF = 2;

    // Form Validation
    const MIN_PASSWORD_LENGTH = 6;
    const MIN_NAME_LENGTH = 2;
    const MAX_NAME_LENGTH = 100;
}
