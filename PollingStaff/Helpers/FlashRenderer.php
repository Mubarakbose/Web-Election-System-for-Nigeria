<?php

/**
 * FlashRenderer - Standardized Flash Message Rendering
 * 
 * Provides consistent, accessible flash message rendering across the portal.
 * Supports success, error, warning, and info message types.
 */

class FlashRenderer
{
    /**
     * Render all flash messages from session
     * 
     * @param array $flashMessages Optional array to use instead of $_SESSION['flash']
     * @param bool $renderContainer Whether to wrap in flash-container div
     * @return string HTML for flash messages, empty string if none
     */
    public static function renderAll($flashMessages = null, $renderContainer = true)
    {
        if ($flashMessages === null) {
            $flashMessages = $_SESSION['flash'] ?? [];
        }

        if (empty($flashMessages)) {
            return '';
        }

        $html = $renderContainer ? '<div class="flash-container" role="region" aria-live="polite" aria-atomic="true">' : '';

        // Handle both single message and array of messages
        if (!is_array($flashMessages) || !isset($flashMessages[0])) {
            // Single message (associative array)
            $flashMessages = [$flashMessages];
        }

        foreach ($flashMessages as $flash) {
            if (!is_array($flash)) {
                continue;
            }

            $type = isset($flash['type']) ? htmlspecialchars($flash['type']) : 'info';
            $msg = isset($flash['text']) ? htmlspecialchars($flash['text']) : (isset($flash['message']) ? htmlspecialchars($flash['message']) : '');

            if (empty($msg)) {
                continue;
            }

            $html .= self::renderMessage($msg, $type);
        }

        if ($renderContainer) {
            $html .= '</div>';
        }

        // Auto-dismiss flash messages after 10 seconds with a smooth fade-out
        $html .= '<script>
            (function(){
                function autoDismissFlash() {
                    var flashes = document.querySelectorAll(".flash-message");
                    flashes.forEach(function(el){
                        setTimeout(function(){
                            el.classList.add("fade-out");
                            setTimeout(function(){
                                if (el && el.parentNode) {
                                    el.parentNode.removeChild(el);
                                }
                            }, 600);
                        }, 10000);
                    });
                }
                if (document.readyState === "loading") {
                    document.addEventListener("DOMContentLoaded", autoDismissFlash);
                } else {
                    autoDismissFlash();
                }
            })();
        </script>';

        // Clear flash messages after rendering
        unset($_SESSION['flash']);

        return $html;
    }

    /**
     * Render a single flash message
     * 
     * @param string $message The message text
     * @param string $type The message type (success, error, warning, info)
     * @return string HTML for the flash message
     */
    public static function renderMessage($message, $type = 'info')
    {
        $type = in_array($type, ['success', 'error', 'warning', 'info']) ? $type : 'info';
        $message = htmlspecialchars($message);

        return sprintf(
            '<div class="flash-message %s" role="alert">' .
                '<span class="flash-icon" aria-hidden="true"></span>' .
                '<span class="flash-text">%s</span>' .
                '<button class="flash-close" onclick="this.parentElement.remove()" aria-label="Close message">&times;</button>' .
                '</div>',
            $type,
            $message
        );
    }

    /**
     * Check if there are any flash messages to display
     * 
     * @return bool True if flash messages exist, false otherwise
     */
    public static function hasMessages()
    {
        return !empty($_SESSION['flash']);
    }

    /**
     * Get the count of flash messages
     * 
     * @return int Number of flash messages
     */
    public static function count()
    {
        $flash = $_SESSION['flash'] ?? [];
        if (empty($flash)) {
            return 0;
        }

        // If single message (associative array), count as 1
        if (isset($flash[0]) || !isset($flash['type'])) {
            return is_array($flash[0] ?? null) ? count($flash) : 1;
        }

        return 1;
    }
}
