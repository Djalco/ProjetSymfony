<?php

namespace App\Twig\Components;

use App\Entity\Deal;
use App\Repository\DealRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('DealComponent')]
class DealComponent
{
    public int $id;
    public function __construct(private DealRepository $dealRepo ){}

    public function getDeal() :Deal
    {
        return $this->dealRepo->find($this->id);
    }
}
