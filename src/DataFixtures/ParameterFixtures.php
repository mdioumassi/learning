<?php

namespace App\DataFixtures;

use App\Entity\Parameter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ParameterFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $parameter1 = new Parameter();
        $parameter1
            ->setId('CIVILITE-M')
            ->setCategories($this->getReference('category_civilite'))
            ->setLabel('Monsieur')
            ->setActive('1');
        $manager->persist($parameter1);

        $parameter2 = new Parameter();
        $parameter2
            ->setId('CIVILITE-MLLE')
            ->setCategories($this->getReference('category_civilite'))
            ->setLabel('Mademoiselle')
            ->setActive('1');
        $manager->persist($parameter2);

        $parameter3 = new Parameter();
        $parameter3
            ->setId('CIVILITE-MME')
            ->setCategories($this->getReference('category_civilite'))
            ->setLabel('Madame')
            ->setActive('1');
        $manager->persist($parameter3);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ParameterCategoryFixtures::class
        ];
    }
}
