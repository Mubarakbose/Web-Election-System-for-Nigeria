<?php

/**
 * StaffConstants
 * Constants for the Polling Staff portal (upload paths, limits, validation rules).
 */

class StaffConstants
{
    // Pagination
    const ITEMS_PER_PAGE = 15;

    // File upload settings
    const UPLOAD_MAX_SIZE = 5000000; // 5MB
    const UPLOAD_VALID_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];
    const UPLOAD_VALID_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif'];

    // Upload directories (relative to PollingStaff/)
    const VOTER_UPLOAD_DIR = __DIR__ . '/../VotersImages';
}
