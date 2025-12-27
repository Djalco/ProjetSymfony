<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tech = new Category();
        $tech->setName('Category-tech');
        $manager->persist($tech);
        $this->addReference('Tech', $tech);

        $sport = new Category();
        $sport->setName('Category-sport');
        $manager->persist($sport);
        $this->addReference('Sport', $sport);


        $manager->flush();
    }
}
