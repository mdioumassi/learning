<?php

namespace App\DataFixtures;

use App\Entity\Parameter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ParameterFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $parametres = [
            'CIVILITE' => [
                '0' => [
                    'id' => 'CIVILITE-M',
                    'label' => 'Monsieur',
                    'active' => 1
                ],
                '1' => [
                    'id' => 'CIVILITE-MLLE',
                    'label' => 'Mademoiselle',
                    'active' => 1
                ],
                '2' => [
                    'id' => 'CIVILITE-MME',
                    'label' => 'Madame',
                    'active' => 1
                ],
            ],
            'DEBUTANT' => [
                '0' => [
                    'id' => 'COMPRENDRE-WEB',
                    'label' => 'Comprendre le web',
                    'active' => 1
                ],
                '1' => [
                    'id' => 'HTML-CSS',
                    'label' => 'Html5 & Css3',
                    'active' => 1
                ],
                '2' => [
                    'id' => 'JAVASCRIPT',
                    'label' => 'Javascript',
                    'active' => 1
                ],
            ],
            'NIVEAU' => [
                '0' => [
                    'id' => 'DEBUTANT',
                    'label' => 'debutant',
                    'active' => 1
                ],
                '1' => [
                    'id' => 'AVANCE',
                    'label' => 'avance',
                    'active' => 1
                ],
                '2' => [
                    'id' => 'EXPERT',
                    'label' => 'expert',
                    'active' => 1
                ],
            ]
        ];
     foreach ($parametres as $key => $parametre) {
        if ($key == 'CIVILITE') {
            $this->getData($parametre, $key, 'civilite', $manager);
        }
        if ($key == 'DEBUTANT') {
             $this->getData($parametre, $key, 'debutant', $manager);
        }
         if ($key == 'NIVEAU') {
             $this->getData($parametre, $key, 'niveau', $manager);
         }
     }
        $manager->flush();
    }

    public function getData(array $data, $groupe, $categorie, ObjectManager $manager)
    {
        $i = 0;
        while ($i < count($data)) {
            $param = new Parameter();
            $param->setId($data[$i]['id']);
            $param->setCategories($this->getReference($categorie));
            $param->setLabel($data[$i]['label']);
            $param->setActive($data[$i]['active']);
            $manager->persist($param);
            $i++;
        }
    }

    public function getDependencies()
    {
        return [
            ParameterCategoryFixtures::class
        ];
    }
}
