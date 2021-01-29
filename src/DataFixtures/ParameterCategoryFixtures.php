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
        $this->addReference('civilite', $category);

        $category1 = new ParameterCategory();
        $category1
            ->setId('DEBUTANT')
            ->setLabel('Debutant');
        $manager->persist($category1);
        $this->addReference('debutant', $category1);

        $category2 = new ParameterCategory();
        $category2
            ->setId('AVANCE')
            ->setLabel('Avancé');
        $manager->persist($category2);
       $this->addReference('avance', $category2);

        $category3 = new ParameterCategory();
        $category3
            ->setId('EXPERT')
            ->setLabel('Expert');
        $manager->persist($category3);
        $this->addReference('expert', $category3);


        $manager->flush();
    }
}
