<?php

namespace PollingStaff\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender
{
    /**
     * Sends simple welcome email with voter credentials (Add Voter).
     *
     * @param string $toEmail
     * @param string $firstName
     * @param string $userName
     * @param string $password
     * @return array [success => bool, message => string]
     */
    public static function sendVoterWelcome(string $toEmail, string $firstName, string $userName, string $password): array
    {
        if (empty($toEmail)) {
            return ['success' => false, 'message' => 'No recipient email provided'];
        }

        $subject = 'INEC Voting Portal | Welcome';
        $bodyHtml = (
            '<h3>Welcome to INEC Voting Portal</h3>' .
            '<p>Dear ' . htmlspecialchars($firstName) . ',</p>' .
            '<p>Thank you for registering. Your account has been created successfully.</p>' .
            '<p><strong>Your login credentials are:</strong></p>' .
            '<p>Username: <strong>' . htmlspecialchars($userName) . '</strong><br>' .
            'Password: <strong>' . htmlspecialchars($password) . '</strong></p>' .
            '<p><strong><i>Please keep these credentials safe. Your username cannot be changed.</i></strong></p>' .
            '<p>Kind regards,<br>INEC Team</p>'
        );
        $bodyText = (
            'Welcome to INEC Voting Portal' . "\r\n\r\n" .
            'Dear ' . $firstName . ',' . "\r\n" .
            'Thank you for registering. Your account has been created successfully.' . "\r\n\r\n" .
            'Your login credentials are:' . "\r\n" .
            'Username: ' . $userName . "\r\n" .
            'Password: ' . $password . "\r\n\r\n" .
            'Please keep these credentials safe. Your username cannot be changed.' . "\r\n" .
            'Kind regards, INEC Team'
        );

        return self::sendEmail($toEmail, $subject, $bodyHtml, $bodyText, $firstName);
    }

    /**
     * Sends update notification listing changed fields (Update Voter).
     *
     * @param string $toEmail
     * @param string $firstName
     * @param array $changedFields e.g., ['Email' => 'newemail@example.com', 'Phone' => '1234567890']
     * @return array [success => bool, message => string]
     */
    public static function sendVoterUpdateNotice(string $toEmail, string $firstName, array $changedFields = []): array
    {
        if (empty($toEmail)) {
            return ['success' => false, 'message' => 'No recipient email provided'];
        }

        $fieldsList = '';
        if (!empty($changedFields)) {
            $fieldsList = '<p><strong>Updated information:</strong></p><ul>';
            foreach ($changedFields as $field => $value) {
                $fieldsList .= '<li><strong>' . htmlspecialchars($field) . ':</strong> ' . htmlspecialchars($value) . '</li>';
            }
            $fieldsList .= '</ul>';
        }

        $subject = 'INEC Voting Portal | Profile Updated';
        $bodyHtml = (
            '<h3>Profile Update Confirmation</h3>' .
            '<p>Dear ' . htmlspecialchars($firstName) . ',</p>' .
            '<p>Your voter profile has been successfully updated.</p>' .
            $fieldsList .
            '<p>If you did not make this change, please contact the INEC office immediately.</p>' .
            '<p>Kind regards,<br>INEC Team</p>'
        );
        $bodyText = (
            'Profile Update Confirmation' . "\r\n\r\n" .
            'Dear ' . $firstName . ',' . "\r\n" .
            'Your voter profile has been successfully updated.' . "\r\n\r\n"
        );

        if (!empty($changedFields)) {
            $bodyText .= 'Updated information:' . "\r\n";
            foreach ($changedFields as $field => $value) {
                $bodyText .= '- ' . $field . ': ' . $value . "\r\n";
            }
            $bodyText .= "\r\n";
        }

        $bodyText .= 'If you did not make this change, please contact the INEC office immediately.' . "\r\n" .
            'Kind regards, INEC Team';

        return self::sendEmail($toEmail, $subject, $bodyHtml, $bodyText, $firstName);
    }

    /**
     * Sends voter deletion notification email.
     *
     * @param string $toEmail
     * @param string $firstName
     * @param string $staffName [optional] Name of admin who deleted
     * @return array [success => bool, message => string]
     */
    public static function sendVoterDeletionNotice(string $toEmail, string $firstName, string $staffName = ''): array
    {
        if (empty($toEmail)) {
            return ['success' => false, 'message' => 'No recipient email provided'];
        }

        $subject = 'INEC Voting Portal | Registration Deleted';
        $staffInfo = !empty($staffName) ? ' by ' . htmlspecialchars($staffName) : '';
        $bodyHtml = (
            '<h3>Voter Registration Deleted</h3>' .
            '<p>Dear ' . htmlspecialchars($firstName) . ',</p>' .
            '<p>Your voter registration on the INEC Voting Portal has been removed' . $staffInfo . '.</p>' .
            '<p>If you believe this is an error or have any questions, please contact the INEC office immediately.</p>' .
            '<p>Kind regards,<br>INEC Team</p>'
        );
        $bodyText = (
            'Voter Registration Deleted' . "\r\n\r\n" .
            'Dear ' . $firstName . ',' . "\r\n" .
            'Your voter registration on the INEC Voting Portal has been removed' . $staffInfo . '.' . "\r\n" .
            'If you believe this is an error or have any questions, please contact the INEC office immediately.' . "\r\n" .
            'Kind regards, INEC Team'
        );

        return self::sendEmail($toEmail, $subject, $bodyHtml, $bodyText, $firstName);
    }

    /**
     * Helper: Build HTML table of voter details with image.
     */
    private static function buildVoterDetailsHtml(array $voterDetails): string
    {
        $imageHtml = '';
        if (!empty($voterDetails['Image'])) {
            $imagePath = htmlspecialchars($voterDetails['Image']);
            // Assume relative path from PollingStaff/VotersImages/
            $imageUrl = 'VotersImages/' . basename($imagePath);
            $imageHtml = '<img src="' . $imageUrl . '" alt="Voter Photo" style="max-width:150px; height:auto; margin-top:15px; border:1px solid #ccc;">';
        }

        $html = '<hr><h4>Your Registration Details:</h4>';
        $html .= '<table style="width:100%; border-collapse:collapse; margin:10px 0;">';

        // Define display fields
        $fields = ['VoterID', 'FirstName', 'OtherName', 'Phone', 'Email', 'State', 'LGA', 'PostCode', 'HomeAddress', 'Gender', 'BirthDate'];
        $labels = [
            'VoterID' => 'Voter ID',
            'FirstName' => 'First Name',
            'OtherName' => 'Other Names',
            'Phone' => 'Phone',
            'Email' => 'Email',
            'State' => 'State',
            'LGA' => 'LGA',
            'PostCode' => 'Post Code',
            'HomeAddress' => 'Home Address',
            'Gender' => 'Gender',
            'BirthDate' => 'Birth Date',
        ];

        foreach ($fields as $field) {
            if (isset($voterDetails[$field])) {
                $label = $labels[$field] ?? $field;
                $value = htmlspecialchars($voterDetails[$field] ?? '');
                $html .= '<tr>';
                $html .= '<td style="padding:8px; border:1px solid #ddd; font-weight:bold; width:30%;">' . htmlspecialchars($label) . '</td>';
                $html .= '<td style="padding:8px; border:1px solid #ddd;">' . $value . '</td>';
                $html .= '</tr>';
            }
        }

        $html .= '</table>';
        $html .= $imageHtml;

        return $html;
    }

    /**
     * Core email sender using PHPMailer with fallback.
     */
    private static function sendEmail(string $toEmail, string $subject, string $bodyHtml, string $bodyText, string $toName = ''): array
    {
        // Load vendor autoload for PHPMailer
        $vendor = dirname(__DIR__, 2) . '/vendor/autoload.php';
        if (file_exists($vendor)) {
            require_once $vendor;
        }

        // Load SMTP config if available
        $configPath = dirname(__DIR__, 2) . '/Connections/email_config.php';
        if (file_exists($configPath)) {
            require_once $configPath;
        }

        $smtpHost = defined('EMAIL_SMTP_HOST') ? EMAIL_SMTP_HOST : getenv('EMAIL_SMTP_HOST');
        $smtpUser = defined('EMAIL_SMTP_USERNAME') ? EMAIL_SMTP_USERNAME : getenv('EMAIL_SMTP_USERNAME');
        $smtpPass = defined('EMAIL_SMTP_PASSWORD') ? EMAIL_SMTP_PASSWORD : getenv('EMAIL_SMTP_PASSWORD');
        $smtpPort = defined('EMAIL_SMTP_PORT') ? EMAIL_SMTP_PORT : (getenv('EMAIL_SMTP_PORT') ?: 587);
        $smtpSecure = defined('EMAIL_SMTP_SECURE') ? EMAIL_SMTP_SECURE : (getenv('EMAIL_SMTP_SECURE') ?: 'tls');
        $fromAddress = defined('EMAIL_FROM_ADDRESS') ? EMAIL_FROM_ADDRESS : (getenv('EMAIL_FROM_ADDRESS') ?: 'no-reply@votingportal.ng');
        $fromName = defined('EMAIL_FROM_NAME') ? EMAIL_FROM_NAME : (getenv('EMAIL_FROM_NAME') ?: 'INEC Voting Portal');

        // If we have PHPMailer and SMTP config, use SMTP
        if (class_exists(PHPMailer::class) && !empty($smtpHost) && !empty($smtpUser) && !empty($smtpPass)) {
            try {
                $mail = new PHPMailer(true);
                $mail->SMTPDebug = 0; // Disable debug output now that it's working
                $mail->isSMTP();
                $mail->Host = $smtpHost;
                $mail->SMTPAuth = true;
                $mail->Username = $smtpUser;
                $mail->Password = $smtpPass;
                $mail->SMTPSecure = $smtpSecure;
                $mail->Port = (int)$smtpPort;

                $mail->setFrom($fromAddress, $fromName);
                $mail->addAddress($toEmail, $toName ?: $toEmail);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $bodyHtml;
                $mail->AltBody = $bodyText;

                $sent = $mail->send();
                return ['success' => $sent, 'message' => $sent ? 'Email sent via SMTP' : 'Failed to send via SMTP'];
            } catch (Exception $e) {
                $errorMsg = 'SMTP Error: ' . $e->getMessage();
                if (class_exists('ErrorHandler')) {
                    \ErrorHandler::log($e, 'EmailSender SMTP', false);
                }
                error_log("[" . date('Y-m-d H:i:s') . "] EmailSender SMTP failed: " . $errorMsg);
                // fall through to mail() fallback below
            }
        }

        // Fallback to PHP mail()
        $headers = "From: $fromAddress\r\n" .
            "Reply-To: $fromAddress\r\n" .
            "MIME-Version: 1.0\r\n" .
            "Content-Type: text/html; charset=UTF-8\r\n";

        $ok = @mail($toEmail, $subject, $bodyHtml, $headers);
        if ($ok) {
            return ['success' => true, 'message' => 'Email sent via mail() fallback'];
        }

        // Ultimate fallback: Log email to file (for development when sender not verified)
        $logDir = dirname(__DIR__, 2) . '/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $logFile = $logDir . '/emails.log';
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = <<<LOG
================================================================================
[{$timestamp}] EMAIL LOG
To: {$toEmail}
From: {$fromAddress} ({$fromName})
Subject: {$subject}

{$bodyHtml}

================================================================================

LOG;

        if (file_put_contents($logFile, $logEntry, FILE_APPEND)) {
            return ['success' => true, 'message' => 'Email logged to file (sender not verified in Brevo - add a domain to send real emails)'];
        }

        return ['success' => false, 'message' => 'Could not send or log email'];
    }
}
