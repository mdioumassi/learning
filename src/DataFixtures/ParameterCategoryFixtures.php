<?php

namespace App\DataFixtures;

use App\Entity\ParameterCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ParameterCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new ParameterCategory();
        $category
            ->setId('CIVILITE')
            ->setLabel('Civilites');
        $manager->persist($category);

        $this->addReference('category_civilite', $category);
        $manager->flush();
    }
}
