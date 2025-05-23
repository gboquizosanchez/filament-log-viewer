<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Utils;

class Decoder
{
    public static function decode(mixed $data): mixed
    {
        if (is_string($data)) {
            $decodedAttempt = json_decode($data, true);

            if (json_last_error() === JSON_ERROR_NONE
                && (is_array($decodedAttempt) || is_object($decodedAttempt))
            ) {
                return self::decode($decodedAttempt);
            }

            return $data;
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::decode($value);
            }

            return $data;
        }

        if (is_object($data)) {
            $dataAsArray = (array) $data;

            foreach ($dataAsArray as $key => $value) {
                $dataAsArray[$key] = self::decode($value);
            }

            return (object) $dataAsArray;
        }

        return $data;
    }
}
