<?php

class Auth
{
    /**
     * Hash a plain-text password using PHP's default algorithm (bcrypt/argon2).
     */
    public static function hashPassword(string $plain): string
    {
        return password_hash($plain, PASSWORD_DEFAULT);
    }

    /**
     * Verify a plain-text password against a stored hash and return rehash info.
     *
     * @return array ['valid' => bool, 'rehash' => string|null]
     */
    public static function verifyPassword(string $plain, string $storedHash): array
    {
        // Legacy fallback: if stored hash is not a bcrypt/argon hash, compare directly
        $isLegacy = !str_starts_with($storedHash, '$2y$') && !str_starts_with($storedHash, '$argon');

        if ($isLegacy) {
            $valid = hash_equals($storedHash, $plain);
            $rehash = $valid ? self::hashPassword($plain) : null;
            return ['valid' => $valid, 'rehash' => $rehash];
        }

        $valid = password_verify($plain, $storedHash);
        $rehash = ($valid && password_needs_rehash($storedHash, PASSWORD_DEFAULT))
            ? self::hashPassword($plain)
            : null;

        return ['valid' => $valid, 'rehash' => $rehash];
    }
}
