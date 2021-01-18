<?php

namespace App\Helper;

class ArrayHelper
{
    public function toUniqArray(?string $value): array
    {
        return array_values(array_filter(array_unique(array_map('trim', explode(',', $value)))));
    }
}
