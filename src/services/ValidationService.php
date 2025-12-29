<?php 

class ValidationService {
    public static function required(string $value): bool {
        return trim($value) !== '';
    }

    public static function length(string $value, int $min, int $max): bool {
        $len = mb_strlen(trim($value));
        return $len >= $min && $len <= $max;
    }

    public static function price($value): bool {
        return is_numeric($value) && (float)$value > 0;
    }

    public static function phone(string $value): bool {
        return preg_match('/^\+[0-9]{1,3}[\s\-()]*(?:[0-9][\s\-()]*){6,14}$/', $value);
    }
}