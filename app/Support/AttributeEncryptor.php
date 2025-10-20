<?php

namespace App\Support;

use RuntimeException;

class AttributeEncryptor
{
    /**
     * The encryption cipher to use.
     */
    private const CIPHER_METHOD = 'aes-256-cbc';

    /**
     * Cached instance to avoid re-reading config on every call.
     */
    private static ?self $instance = null;

    private string $key;
    private string $iv;
    private int $ivLength;

    public function __construct()
    {
        $this->ivLength = openssl_cipher_iv_length(self::CIPHER_METHOD);

        $key = config('encryption.key');
        $iv = config('encryption.iv');

        if (!is_string($key) || !is_string($iv)) {
            throw new RuntimeException('Both encryption key and IV must be specified in config/encryption.php.');
        }

        $decodedKey = base64_decode($key, true);
        $decodedIv = base64_decode($iv, true);

        if ($decodedKey === false || $decodedIv === false) {
            throw new RuntimeException('Encryption key and IV must be valid base64 strings.');
        }

        if (strlen($decodedKey) !== 32) {
            throw new RuntimeException('Attribute encryption key must be 32 bytes (256 bits) long after base64 decoding.');
        }

        if (strlen($decodedIv) !== $this->ivLength) {
            throw new RuntimeException('Attribute encryption IV is not the correct length for the selected cipher.');
        }

        $this->key = $decodedKey;
        $this->iv = $decodedIv;
    }

    private static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * Encrypt the given value.
     */
    public static function encrypt(string $value): ?string
    {
        $inst = self::getInstance();

        $encrypted = openssl_encrypt($value, self::CIPHER_METHOD, $inst->key, OPENSSL_RAW_DATA, $inst->iv);

        if ($encrypted === false) {
            throw new RuntimeException('Failed to encrypt the given value.');
        }

        return base64_encode($encrypted);
    }

    /**
     * Decrypt the given value.
     */
    public static function decrypt(string $value): ?string
    {
        $decoded = base64_decode($value, true);
        if ($decoded === false) {
            return null;
        }

        $inst = self::getInstance();

        $decrypted = openssl_decrypt($decoded, self::CIPHER_METHOD, $inst->key, OPENSSL_RAW_DATA, $inst->iv);

        return $decrypted === false ? null : $decrypted;
    }
}
