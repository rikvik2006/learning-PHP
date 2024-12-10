<?php

declare(strict_types=1);

function fact(int $number): int
{
    if (!is_int($number)) {
        throw new Exception("insert a valid int number");
    }
    if ($number < 0) {
        throw new Exception("insert a valid positive number");
    }
    if ($number === 0 || $number === 1) {
        return 1;
    }
    return $number * fact($number - 1);
}
