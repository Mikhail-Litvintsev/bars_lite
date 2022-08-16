<?php

namespace App\Service;

class LpuFileService
{
    /**
     * @return string
     */
    public static function checkForm(array $file): bool
    {
        if (isset($file[array_key_first($file)])) {
            $item = $file[array_key_first($file)];
            return (array_key_exists('name', $item)
                && array_key_exists('address', $item)
                && array_key_exists('phone', $item)
                && array_key_exists('departments', $item));
        }
        return false;
    }
}
