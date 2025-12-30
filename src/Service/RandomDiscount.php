<?php

namespace App\Service;

class RandomDiscount{

    public function __construct(private int $max, private int $min){
    }

    public function getRandomDiscount(): int
    {
        return random_int($this->min, $this->max);
    }
}