<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Deal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DealFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $deal1 = new Deal();
        $deal1->setName('Promo PC Portable');
        $deal1->setDescription('Promo PC Portable');
        $deal1->setPrice(100);
        $deal1->setEnable(true);
        $deal1->addCategory($this->getReference('Tech', Category::class));
        $manager->persist($deal1);

        $deal2 = new Deal();
        $deal2->setName('Reduction sur les TV');
        $deal2->setDescription('Reduction sur les TV');
        $deal2->setPrice(100);
        $deal2->setEnable(true);
        $deal2->addCategory($this->getReference('Tech', Category::class));
        $manager->persist($deal2);

        $deal3 = new Deal();
        $deal3->setName('Promo sur les maillots de sports');
        $deal3->setDescription('Promo sur les maillots de sports');
        $deal3->setPrice(100);
        $deal3->setEnable(true);
        $deal3->addCategory($this->getReference('Sport', Category::class));
        $manager->persist($deal3);

        $deal4 = new Deal();
        $deal4->setName('Bon plan equipement fitness');
        $deal4->setDescription('Bon plan equipement fitness');
        $deal4->setPrice(100);
        $deal4->setEnable(true);
        $deal4->addCategory($this->getReference('Sport', Category::class));
        $manager->persist($deal4);

        $manager->flush();
    }
}
