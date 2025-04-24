<?php

declare(strict_types=1);

class UUID
{
    /**
     * Generate a version 4 (random) UUID.
     *
     * @param string|null $data Optional data to use for generating the UUID.
     * @return string The generated UUID.
     */
    public static function v4($data = null): string
    {
        // Code getted from https://www.uuidgenerator.net/dev-corner/php

        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
