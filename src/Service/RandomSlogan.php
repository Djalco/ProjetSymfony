<?php

namespace App\Service;

class RandomSlogan
{
    private array $slogans = [
        "Quality you can trust!",
        "Best deals in town!",
        "Unbeatable prices every day!",
        "Your satisfaction, our priority!",
        "Deals that make you smile!"
    ];

    public function getSlogan(): string
    {
        return $this->slogans[array_rand($this->slogans)];
    }
}