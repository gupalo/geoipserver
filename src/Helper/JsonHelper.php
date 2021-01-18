<?php

namespace App\Helper;

use Throwable;

class JsonHelper
{
    public function encode(mixed $value): string
    {
        try {
            $result = json_encode($value, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            try {
                $result = json_encode(['error' => $e->getMessage()], JSON_THROW_ON_ERROR);
            } catch (Throwable) {
                $result = '{error:"error"}';
            }
        }

        return $result;
    }
}
