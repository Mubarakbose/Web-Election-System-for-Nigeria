<?php

/**
 * ErrorHandler
 * Centralizes error logging and user-friendly error responses.
 * Prevents error details from leaking to users; logs to file instead.
 */

class ErrorHandler
{
    private static string $logDir = __DIR__ . '/../Logs';
    private static bool $logInitialized = false;

    /**
     * Initialize the log directory if needed.
     */
    private static function initializeLog(): void
    {
        if (!self::$logInitialized) {
            if (!is_dir(self::$logDir)) {
                @mkdir(self::$logDir, 0755, true);
            }
            self::$logInitialized = true;
        }
    }

    /**
     * Log an error to a file and optionally set a flash message.
     * 
     * @param Exception|Throwable $exception The exception or error
     * @param string $context Context/description (e.g., 'AddContestant')
     * @param bool $setFlash Whether to set a flash message for the user
     */
    public static function log($exception, string $context = '', bool $setFlash = true): void
    {
        self::initializeLog();

        $timestamp = date('Y-m-d H:i:s');
        $logMessage = sprintf(
            "[%s] %s | Context: %s | Error: %s | File: %s:%d\n",
            $timestamp,
            get_class($exception),
            $context,
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );

        $logFile = self::$logDir . '/' . date('Y-m-d') . '.log';
        @error_log($logMessage, 3, $logFile);

        if ($setFlash) {
            self::setFlashError('An error occurred. Please try again later.');
        }
    }

    /**
     * Set a flash message (success).
     */
    public static function setFlashSuccess(string $message): void
    {
        self::ensureSession();
        $_SESSION['flash'][] = ['type' => 'success', 'text' => $message];
    }

    /**
     * Set a flash message (error).
     */
    public static function setFlashError(string $message): void
    {
        self::ensureSession();
        $_SESSION['flash'][] = ['type' => 'error', 'text' => $message];
    }

    /**
     * Set a flash message (warning).
     */
    public static function setFlashWarning(string $message): void
    {
        self::ensureSession();
        $_SESSION['flash'][] = ['type' => 'warning', 'text' => $message];
    }

    /**
     * Set a flash message with custom type.
     * 
     * @param string $message The message to display
     * @param string $type The message type: 'success', 'error', 'warning', 'info'
     */
    public static function setFlash(string $message, string $type = 'info'): void
    {
        self::ensureSession();
        $_SESSION['flash'][] = ['type' => $type, 'text' => $message];
    }

    /**
     * Redirect with a flash message and optional query string.
     * 
     * @param string $url URL to redirect to
     * @param string $message Flash message to display
     * @param string $type Flash type: 'success', 'error', 'warning'
     */
    public static function redirectWithFlash(string $url, string $message, string $type = 'info'): void
    {
        self::ensureSession();
        $_SESSION['flash'][] = ['type' => $type, 'text' => $message];
        header('Location: ' . $url);
        exit;
    }

    /**
     * Ensure session is started.
     */
    private static function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
    }

    /**
     * Handle a generic error with logging and user-friendly message.
     * Useful for catch blocks.
     */
    public static function handle($exception, string $context = '', string $redirectUrl = null): void
    {
        self::log($exception, $context, false);

        if ($redirectUrl) {
            self::redirectWithFlash($redirectUrl, 'An error occurred. Please try again later.', 'error');
        } else {
            self::ensureSession();
            $_SESSION['flash'][] = ['type' => 'error', 'text' => 'An error occurred. Please try again later.'];
        }
    }
}
